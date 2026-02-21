<?php

namespace App\Jobs;

use App\Models\ShippingAddress;
use App\Services\GoogleService;
use App\Services\GoogleService_mock;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CalculateDistanceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public ShippingAddress $address;
    public int $addressId;

    public function __construct(int $addressId)
    {
        $this->addressId = $addressId;
    }

    private function sanitizeAddress(string $address): string
    {
        // Trim whitespace
        $address = trim($address);

        // If address starts with "number-number"
        if (preg_match('/^(\d+)-(\d+)\s+(.*)$/', $address, $matches)) {
            // $matches[2] = street number
            // $matches[3] = rest of address
            return $matches[2] . ' ' . $matches[3];
        }

        return $address;
    }


    public function handle(GoogleService_mock $googleService)
    {
        // Re-fetch the address to ensure it still exists
        $this->address = ShippingAddress::find($this->addressId);
        if (!$this->address) {
            // Address was deleted before job ran — exit safely
            return;
        }
        
        /////////////////////////////////////////////////////////
        $cleanAddress = $this->address->description;
        // $cleanAddress = $this->sanitizeAddress($raw);
        
        logger()->info("Requesting geocode: {$cleanAddress}");

        // 1. Geocode
        $coords = $googleService->geocode($cleanAddress); //description contains the full address string

        // 2. Store location (example)
        $store = [
            'lat' => 43.66963252,
            'lng' => -79.38474131,
        ];//43.67051795, -79.38165406 
        //43.73837530, -79.24351120

        logger()->info("Requesting driving distance: lat {$coords['lat']} long {$coords['lng']}");
        // 3. Driving distance
        $km = $googleService->drivingDistanceKm($store, $coords);
        
        logger()->info("Driving distance: {$km} km");

        // 4. Save results
        $this->address->update([
            'latitude' => $coords['lat'],
            'longitude' => $coords['lng'],
            'driving_distance_km' => $km,
        ]);
    }
}
