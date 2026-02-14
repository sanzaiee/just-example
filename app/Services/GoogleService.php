<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GoogleService
{
    public function geocode(string $address): array
    {
        $url = "https://maps.googleapis.com/maps/api/geocode/json";

        $response = Http::get($url, [
            'address' => $address,
            'components' => 'administrative_area:ON|country:CA',
            'key' => env('GOOGLE_MAPS_KEY'),
        ]);

        if (!$response->successful()) {
            throw new \Exception('Geocoding request failed: ' . $response->status());
        }

        $data = $response->json();

        if (!isset($data['results']) || empty($data['results']) || !isset($data['results'][0]['geometry']['location'])) {
            throw new \Exception('No geocoding results found in Ontario.');
        }
        // 6. Extract lat/lng safely 

        return ['lat' => $data['results'][0]['geometry']['location']['lat'], 'lng' => $data['results'][0]['geometry']['location']['lng']];
    }

    public function drivingDistanceKm(array $origin, array $destination) : float
    {
        // Build origin/destination strings
        $originStr = "{$origin['lat']},{$origin['lng']}";
        $destinationStr = "{$destination['lat']},{$destination['lng']}";

        // Call Google Distance Matrix API
        $response = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json', [
            'origins'      => $originStr,
            'destinations' => $destinationStr,
            'mode'         => 'driving',
            'units'        => 'metric',
            'avoid'        => 'tolls|highways',
            'key'          => env('GOOGLE_MAPS_KEY'),
        ]);

        // 1. Ensure HTTP request succeeded
        if (!$response->successful()) {
            throw new \Exception('Distance Matrix request failed: ' . $response->status());
        }

        $data = $response->json();
        logger()->info('Distance Matrix response: ' . json_encode($data));

        // 2. Ensure Google returned valid rows/elements
        if (
            empty($data['rows']) ||
            empty($data['rows'][0]['elements']) ||
            $data['rows'][0]['elements'][0]['status'] !== 'OK'
        ) {
            throw new \Exception('No valid driving distance found.');
        }

        // 3. Extract distance in meters
        $meters = $data['rows'][0]['elements'][0]['distance']['value'];

        // 4. Convert to km
        return number_format($meters / 1000, 2, '.', '');
    }
}
