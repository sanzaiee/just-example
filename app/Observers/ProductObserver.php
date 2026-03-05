<?php

namespace App\Observers;

use App\Models\OrderProductList;
use App\Models\Product;
use Illuminate\Validation\ValidationException;

class ProductObserver
{
    /**
     * Handle the Product "deleting" event.
     *
     * Prevents deletion of products that are associated with existing orders.
     * This protects order history and data integrity.
     */
    public function deleting(Product $product): void
    {
        $orderProductLists = OrderProductList::where('product_id', $product->id)->get();

        if ($orderProductLists->isNotEmpty()) {
            $orderIds = $orderProductLists->pluck('order_id')->unique()->values()->toArray();

            throw ValidationException::withMessages([
                'product' => [
                    'Cannot delete product "'.$product->name.'" (ID: '.$product->id.') '.
                    'because it is used in existing orders (Order IDs: '.implode(', ', $orderIds).'). ',
                    // 'Please use soft delete instead.'
                ],
            ]);
        }
    }

    /**
     * Handle the Product "force deleted" event.
     *
     * Additional protection for force delete operations.
     */
    public function forceDeleted(Product $product): void
    {
        // This is a safety measure - though the "deleting" event should have
        // already caught this, we log it for audit purposes
        logger()->warning('Product force deleted', [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'timestamp' => now(),
        ]);
    }

    /**
     * Handle the Product "restoring" event.
     *
     * Log when a soft-deleted product is restored.
     */
    public function restoring(Product $product): void
    {
        logger()->info('Product restored', [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'timestamp' => now(),
        ]);
    }
}
