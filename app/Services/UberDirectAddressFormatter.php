<?php

namespace App\Services;

use App\Models\ShippingAddress;

class UberDirectAddressFormatter
{
    //"pickup_address": "{\"street_address\":[\"100 Maiden Ln\"],\"city\":\"New York\",\"state\":\"NY\",\"zip_code\":\"10023\",\"country\":\"US\"}",
    public static function format(ShippingAddress $address): array
    {
        // Combine street and house_no properly
        $streetParts = [];

        if (!empty($address->street)) {
            $streetParts[] = trim($address->street);
        }

        if (!empty($address->house_no)) {
            $streetParts[] = trim($address->house_no);
        }

        return [
            'street_address' => array_filter($streetParts), // "street_address": ["100 Maiden Ln", "Apt 101"],
            'city'           => $address->city ?? '',
            'state'          => 'Ontario',
            'zip_code'       => $address->postal_code ?? '',
            'country'        => 'CA',                       // Uber requires country code
        ];
    }

    public static function pickupAddressformat(): array
    {
        // Create a dummy ShippingAddress object
        $address = new ShippingAddress([
            'street'      => '2780 Eglinton',
            'house_no'    => 'Unit 3',
            'city'        => 'Scarborough',
            'postal_code' => 'M1J 2C8',
        ]);

        // Combine street and house_no properly
        $streetParts = [];

        if (!empty($address->street)) {
            $streetParts[] = trim($address->street);
        }

        if (!empty($address->house_no)) {
            $streetParts[] = trim($address->house_no);
        }

        return [
            'street_address' => array_filter($streetParts), // "street_address": ["100 Maiden Ln", "Apt 101"],
            'city'           => $address->city ?? '',
            'state'          => 'Ontario',
            'postal_code'       => $address->postal_code ?? '',
            'country'        => 'CA',                       // Uber requires country code
        ];
    }

    public static function deliveryAddressformat(): array
    {
        // Create a dummy ShippingAddress object
        $address = new ShippingAddress([
            'street'      => '1071 Danforth Road',
            'house_no'    => '',
            'city'        => 'Scarborough',
            'postal_code' => 'M1J 1E4',
        ]);

        // Combine street and house_no properly
        $streetParts = [];

        if (!empty($address->street)) {
            $streetParts[] = trim($address->street);
        }

        if (!empty($address->house_no)) {
            $streetParts[] = trim($address->house_no);
        }

        return [
            'street_address' => array_filter($streetParts), // "street_address": ["100 Maiden Ln", "Apt 101"],
            'city'           => $address->city ?? '',
            'state'          => 'Ontario',
            'postal_code'       => $address->postal_code ?? '',
            'country'        => 'CA',                       // Uber requires country code
        ];
    }
}
