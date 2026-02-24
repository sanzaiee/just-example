<?php

namespace App\Observers;

use App\Models\OrderProductList;
use App\Models\Product;

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
        // Check if this product is used in any order
        // This applies to both soft deletes and force deletes
        $hasOrders = OrderProductList::where('product_id', $product->id)->exists();

        if ($hasOrders) {
            $orderId = OrderProductList::where('product_id', $product->id)
                ->select('order_id')
                ->first()
                ?->order_id;

            throw new \Exception(
                'Cannot delete product "'.$product->name.'" (ID: '.$product->id.') '.
                'because it is associated with existing orders (Order ID: '.$orderId.'). '.
                'Please use soft delete instead or check with system administrator.'
            );
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
