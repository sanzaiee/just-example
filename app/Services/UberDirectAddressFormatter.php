<?php

namespace App\Services;

use App\Models\OrderDeliveryAddress;

class UberDirectAddressFormatter
{
    //"pickup_address": "{\"street_address\":[\"100 Maiden Ln\"],\"city\":\"New York\",\"state\":\"NY\",\"zip_code\":\"10023\",\"country\":\"US\"}",
    public static function format(OrderDeliveryAddress $deliveryAddress): array
    {
        // Combine street and house_no properly
        $streetParts = [];

        if (!empty($deliveryAddress->address)) {
            $streetParts[] = trim($deliveryAddress->address);
        }

        // if (!empty($deliveryAddress->house_no)) {
        //     $streetParts[] = trim($deliveryAddress->house_no);
        // }

        return [
            'street_address' => array_filter($streetParts), // "street_address": ["100 Maiden Ln", "Apt 101"],
            'city'           => $deliveryAddress->city ?? '',
            'state'          => 'Ontario',
            'zip_code'       => $deliveryAddress->postal_code ?? '',
            'country'        => 'CA',                       // Uber requires country code
        ];
    }

    public static function pickupAddressDummy(): array
    {
        // Create a dummy OrderDeliveryAddress object
        $address = new OrderDeliveryAddress([
            'address'      => '1071 Danforth Rd',
            'house_no'    => 'Unit 2',
            'city'        => 'Scarborough',
            'postal_code' => 'M1J 2C8',
        ]);
        return self::format($address);
    }

    public static function pickupAddress(): array
    {
        // Create a dummy OrderDeliveryAddress object
        $address = new OrderDeliveryAddress([
            'address'      => '35 Hayden St',
            'house_no'    => '',
            'city'        => 'Toronto',
            'postal_code' => 'M4Y 3C3',
        ]);
        return self::format($address);
    }

    // public static function deliveryAddressformat(): array
    // {
    //     // Create a dummy ShippingAddress object
    //     $address = new ShippingAddress([
    //         'street'      => '1071 Danforth Road',
    //         'house_no'    => '',
    //         'city'        => 'Scarborough',
    //         'postal_code' => 'M1J 1E4',
    //     ]);

    //     // Combine street and house_no properly
    //     $streetParts = [];

    //     if (!empty($address->street)) {
    //         $streetParts[] = trim($address->street);
    //     }

    //     if (!empty($address->house_no)) {
    //         $streetParts[] = trim($address->house_no);
    //     }

    //     return [
    //         'street_address' => array_filter($streetParts), // "street_address": ["100 Maiden Ln", "Apt 101"],
    //         'city'           => $address->city ?? '',
    //         'state'          => 'Ontario',
    //         'postal_code'       => $address->postal_code ?? '',
    //         'country'        => 'CA',                       // Uber requires country code
    //     ];
    // }
}
