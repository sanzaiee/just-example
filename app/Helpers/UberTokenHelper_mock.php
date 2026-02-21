<?php

namespace App\Helpers;

use Illuminate\Http\Client\Response;
use GuzzleHttp\Psr7\Response as GuzzleResponse;

class UberTokenHelper_mock
{
    public static function createDelivery(): Response
    {
        $path = base_path('uber_create_delivery.json');
        $json = file_get_contents($path);

        if ($json === false) {
            throw new \RuntimeException('Unable to read uber_create_delivery.json');
        }

        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Invalid JSON in uber_create_delivery.json');
        }

        // Build a real PSR-7 response
        $guzzle = new GuzzleResponse(
            200,
            ['Content-Type' => 'application/json'],
            json_encode($data)
        );

        // Wrap it in Laravel's Response class
        return new Response($guzzle);
    }

    public static function getDeliveryUpdate(): Response
    {
        $path = base_path('uber_update_status_response.json');
        $json = file_get_contents($path);

        if ($json === false) {
            throw new \RuntimeException('Unable to read uber_update_status_response.json');
        }

        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Invalid JSON in uber_update_status_response.json');
        }

        // Build a real PSR-7 response
        $guzzle = new GuzzleResponse(
            200,
            ['Content-Type' => 'application/json'],
            json_encode($data)
        );

        // Wrap it in Laravel's Response class
        return new Response($guzzle);
    }
}
