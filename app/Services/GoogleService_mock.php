<?php

namespace App\Services;

class GoogleService_mock
{
    public function geocode(string $address): array
    {
        $path = base_path('google-geocodeAPI.json');
        $json = file_get_contents($path);

        if ($json === false) {
            throw new \RuntimeException('Unable to read google-geocodeAPI.json');
        }

        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Invalid JSON in find-response.json');
        }

        if (!isset($data['results']) || empty($data['results']) || !isset($data['results'][0]['geometry']['location'])) {
            throw new \Exception('No geocoding results found in Ontario.');
        }
        // 6. Extract lat/lng safely 

        return ['lat' => $data['results'][0]['geometry']['location']['lat'], 'lng' => $data['results'][0]['geometry']['location']['lng']];
    }

    public function drivingDistanceKm(array $origin, array $destination): float
    {
        $path = base_path('google-distanceAPI.json');
        $json = file_get_contents($path);

        if ($json === false) {
            throw new \RuntimeException('Unable to read google-distanceAPI.json');
        }

        $data = json_decode($json, true);

        logger()->info('MOCK: Distance Matrix response: ' . json_encode($data));

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
