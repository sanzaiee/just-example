<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ProductPricingService;
use Illuminate\Http\Request;

class ProductPricingController extends Controller
{
    /**
     * Display product with tiered pricing information
     */
    public function show(Product $product)
    {
        // Get all price tiers for display
        $priceTiers = ProductPricingService::getFormattedPriceTiers($product);
        
        // Example: Calculate price for different quantities
        $examplePrices = [
            1 => ProductPricingService::getTotalPriceForQuantity($product, 1),
            5 => ProductPricingService::getTotalPriceForQuantity($product, 5),
            10 => ProductPricingService::getTotalPriceForQuantity($product, 10),
            25 => ProductPricingService::getTotalPriceForQuantity($product, 25),
            50 => ProductPricingService::getTotalPriceForQuantity($product, 50),
        ];
        
        return view('frontend.product.pricing', compact('product', 'priceTiers', 'examplePrices'));
    }
    
    /**
     * API endpoint to get price for specific quantity
     */
    public function getPrice(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);
        
        $quantity = $request->quantity;
        $unitPrice = ProductPricingService::getPriceForQuantity($product, $quantity);
        $totalPrice = ProductPricingService::getTotalPriceForQuantity($product, $quantity);
        
        return response()->json([
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total_price' => $totalPrice,
            'formatted_unit_price' => '$' . number_format($unitPrice, 2),
            'formatted_total_price' => '$' . number_format($totalPrice, 2),
        ]);
    }
}
