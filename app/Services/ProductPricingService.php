<?php

namespace App\Services;

use App\Models\Product;

class ProductPricingService
{
    /**
     * Get the price for a product based on quantity
     * 
     * @param Product $product
     * @param int $quantity
     * @return float
     */
    public static function getPriceForQuantity(Product $product, int $quantity = 1): float
    {
        return $product->getPriceForQuantity($quantity);
    }
    
    /**
     * Get the total price for a product based on quantity
     * 
     * @param Product $product
     * @param int $quantity
     * @return float
     */
    public static function getTotalPriceForQuantity(Product $product, int $quantity = 1): float
    {
        $unitPrice = self::getPriceForQuantity($product, $quantity);
        return $unitPrice * $quantity;
    }
    
    /**
     * Get all available price tiers for a product
     * 
     * @param Product $product
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getPriceTiers(Product $product)
    {
        return $product->getPriceTiers();
    }
    
    /**
     * Format price tiers for display
     * 
     * @param Product $product
     * @return array
     */
    public static function getFormattedPriceTiers(Product $product): array
    {
        $tiers = self::getPriceTiers($product);
        $formatted = [];
        
        foreach($tiers as $tier) {
            $formatted[] = [
                'min_quantity' => $tier->quantity,
                'price' => number_format($tier->price, 2),
                'formatted' => "{$tier->quantity}+ units: $" . number_format($tier->price, 2)
            ];
        }
        
        return $formatted;
    }
}
