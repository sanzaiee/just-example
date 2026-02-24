<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Http\Client\RequestException;
use App\Models\User;
class UberTokenHelper
{
    private static string $clientId;
    private static string $clientSecret;
    private static string $customerId;
    private static string $scope = 'eats.deliveries';

    public static function init()
    {
        self::$clientId = config('services.uber.client_id'); 
        self::$clientSecret = config('services.uber.client_secret'); 
        self::$customerId = config('services.uber.customer_id');
    }

    /**
     * Get a valid Uber Direct API token
     */
    public static function getToken(): string
    {
        self::init();

        $user = User::first(); // or however you want to associate the token
        $tokenRecord = $user->tokens()
            ->where('name', 'uber_direct')
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now()->subMinute()); // 1 min buffer
            })
            ->first();

        if ($tokenRecord) {
            return $tokenRecord->token;
        }

        return self::fetchAndStoreToken();
    }

    /**
     * Fetch a new token from Uber API and store it in DB
     */
    private static function fetchAndStoreToken(): string
    {
        try {
            $response = Http::asForm()
                ->post('https://auth.uber.com/oauth/v2/token', [
                    'client_id' => self::$clientId,
                    'client_secret' => self::$clientSecret,
                    'grant_type' => 'client_credentials',
                    'scope' => self::$scope,
                ])
                ->throw(); // throws if 4xx or 5xx

            $data = $response->json();

            // optional sanity check
            if (!isset($data['access_token'])) {
                throw new \Exception('Uber token response missing access_token');
            }

        } catch (RequestException $e) {
            // HTTP error (4xx/5xx)
            throw new \Exception(
                'Failed to fetch Uber Direct token: ' . $e->response->body(),
                $e->getCode(),
                $e
            );
        } catch (\Throwable $e) {
            // Network errors, JSON issues, etc.
            throw new \Exception(
                'Unexpected error while fetching Uber Direct token: ' . $e->getMessage(),
                0,
                $e
            );
        }

        $accessToken = data_get($data, 'access_token');

        if (!$accessToken) {
            throw new \Exception('Invalid Uber token response: ' . json_encode($data));
        }

        $expiresIn = data_get($data, 'expires_in') ?? 3600; // seconds
        $expiresAt = Carbon::now()->addSeconds($expiresIn);

        $user = User::first(); // or however you want to associate the token
        $user->tokens()->updateOrCreate(
            ['name' => 'uber_direct'],
            [
                'token'      => $accessToken,
                'expires_at' => $expiresAt,
            ]
        );

        return $accessToken;
    }

    public static function createDelivery(array $payload): \Illuminate\Http\Client\Response
    {
        if (config('app.debug') && app()->environment('local')) {
            throw new \Exception('App is in local testing mode');
        }

        $token = self::getToken();

        // Request headers
        $options = [
            'headers' => [
                'Authorization' => "Bearer $token",
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json', // important
            ]
        ];

        $url = "https://api.uber.com/v1/customers/" . self::$customerId . "/deliveries";
        // Make the request
        $response = Http::withHeaders($options['headers'])->post($url, $payload);

        // If unauthorized, retry once with new token
        if ($response->status() === 401) {
            $token = self::fetchAndStoreToken(); // force new token
            $options['headers']['Authorization'] = "Bearer $token";

            $response = Http::withHeaders($options['headers'])
                ->post($url, $payload);
        }

        return $response;
    }

    public static function getDeliveryUpdate(string $deliveryId): \Illuminate\Http\Client\Response
    {
        if (!is_string($deliveryId) || trim($deliveryId) === '') {
            throw new \InvalidArgumentException('Uber delivery ID must be a non-empty string.');
        }

        $token = self::getToken();
        $headers = ['Authorization' => "Bearer $token", 'Accept' => 'application/json', 'Content-Type' => 'application/json',];

        $url = "https://api.uber.com/v1/customers/" . self::$customerId . "/deliveries/" . $deliveryId;
        $response = Http::withHeaders($headers)->get($url);

        // If unauthorized, retry once with new token
        if ($response->status() === 401) {
            $token = self::fetchAndStoreToken(); // force new token
            $headers['Authorization'] = "Bearer $token";

            $response = Http::withHeaders($headers)->get($url);
        }

        return $response;
    }

}
