<?php

namespace App\Livewire\Cart;

use App\Models\Order;
use App\Models\OrderProductList;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;

class Checkout extends Component
{
    public $subTotal = 0;
    public $cartCount = 0;
    public $discount = 0;
    public $total = 0;
    public $pid;

    // Cache product data to avoid repeated queries
    public $products = [];
    public $cartItems = [];

    public function render()
    {
        return view('livewire.cart.checkout', [
            'cartItems' => $this->cartItems,
            'subTotal' => $this->subTotal,
            'cartCount' => $this->cartCount,
        ]);
    }

    public function mount()
    {
        $this->loadCartData();
        $this->pid = 'P-id'.'-'.Str::random(5);
    }

    #[On('refreshCart')]
    public function refreshCart()
    {
        $this->loadCartData();
        $this->dispatch('cartUpdated');
    }

    /**
     * Load all cart data efficiently with eager loading
     */
    private function loadCartData(): void
    {
        $cartContent = Cart::content();

        if ($cartContent->isEmpty()) {
            $this->cartItems = [];
            $this->subTotal = 0;
            $this->cartCount = 0;
            return;
        }

        // Get all product IDs from cart
        $productIds = $cartContent->pluck('id')->unique()->toArray();

        // Eager load all products with their relationships and tiered prices
        $this->products = Product::with(['user', 'category', 'brand', 'tieredPrices'])
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        // Prepare cart items with product data and calculated prices
        $this->cartItems = [];
        $subtotal = 0;

        foreach ($cartContent as $index => $item) {
            $product = $this->products[$item->id] ?? null;

            if (!$product) {
                continue;
            }

            // Calculate price once and store it
            $unitPrice = $this->getCachedPriceForQuantity($product->id, $item->qty);
            $itemSubtotal = $unitPrice * $item->qty;

            // Use array instead of DTO for Livewire compatibility
            $this->cartItems[$index] = [
                'rowId' => $item->rowId,
                'id' => $item->id,
                'name' => $item->name,
                'slug' => $product->slug,
                'qty' => $item->qty,
                'unitPrice' => $unitPrice,
                'subtotal' => $itemSubtotal,
                'image' => $product->image,
            ];

            $subtotal += $itemSubtotal;
        }

        // Calculate totals
        $this->subTotal = $subtotal;
        $this->cartCount = count($this->cartItems);
    }

    /**
     * Cached price calculation to avoid repeated database queries
     */
    private function getCachedPriceForQuantity(int $productId, int $quantity): float
    {
        static $priceCache = [];

        $cacheKey = "{$productId}_{$quantity}";

        if (!isset($priceCache[$cacheKey])) {
            $product = $this->products[$productId] ?? null;
            if ($product) {
                $priceCache[$cacheKey] = $product->getPriceForQuantity($quantity);
            } else {
                $priceCache[$cacheKey] = 0;
            }
        }

        return $priceCache[$cacheKey];
    }

    /**
     * Update cart item with new quantity and corresponding price
     */
    private function updateCartItemPrice($rowId, $newQuantity)
    {
        $cartItem = Cart::get($rowId);
        if (!$cartItem) {
            return;
        }

        // Get the product from the database if not in cached products
        $product = $this->products[$cartItem->id] ?? null;
        if (!$product) {
            $product = Product::with(['tieredPrices'])->find($cartItem->id);
            if (!$product) {
                return;
            }
        }

        $newPrice = $product->getPriceForQuantity($newQuantity);

        // Update both quantity and price
        Cart::update($rowId, [
            'qty' => $newQuantity,
            'price' => $newPrice,
        ]);
    }

    public function decreaseQty($rowId, $qty)
    {
        // Debug: Log the incoming parameters
        logger()->info('decreaseQty called', ['rowId' => $rowId, 'qty' => $qty]);

        $cartItem = Cart::get($rowId);
        if (!$cartItem) {
            logger()->warning('Cart item not found', ['rowId' => $rowId]);
            return;
        }

        if ($qty > 1) {
            $newQty = --$qty;
            $this->updateCartItemPrice($rowId, $newQty);
        }

        $this->refreshCart();
        $this->dispatch('alert', ['type' => 'success', 'message' => 'Item Decreased!']);
    }

    public function increaseQty($rowId, $qty)
    {
        // Debug: Log the incoming parameters
        logger()->info('increaseQty called', ['rowId' => $rowId, 'qty' => $qty]);

        $cartItem = Cart::get($rowId);
        if (!$cartItem) {
            logger()->warning('Cart item not found', ['rowId' => $rowId]);
            return;
        }

        $newQty = ++$qty;
        $this->updateCartItemPrice($rowId, $newQty);

        $this->refreshCart();
        $this->dispatch('alert', ['type' => 'success', 'message' => 'Item Increased!']);
    }

    public function removeFromCart($rowId)
    {
        // Debug: Log the incoming parameter
        logger()->info('removeFromCart called', ['rowId' => $rowId]);

        Cart::remove($rowId);
        $this->refreshCart();
        $this->dispatch('alert', ['type' => 'success', 'message' => 'Item Removed!']);
    }

    public function orderStore()
    {
        $db['pid'] = $this->pid;
        $db['quantity'] = $this->cartCount;
        $db['user_id'] = auth()->id();
        $db['amount'] = $this->subTotal;
        $db['discount'] = $this->discount;
        $db['shipping_address_id'] = auth()->user()->shippingAddress->id;

        $x_order = Order::where('pid', $this->pid)->where('order_status', 0)->first();
        if ($x_order) {
            $x_order->forceDelete();
        }
        $order = Order::create($db);

        if ($order) {
            foreach ($this->cartItems as $item) {
                OrderProductList::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['qty'],
                    'price' => $item['unitPrice'],
                ]);
            }
        }

        return $order;
    }

    public function checkout()
    {
        if (auth()->user()->shippingAddress == null) {
            $this->dispatch('alert', ['type' => 'error', 'message' => 'Please add shipping address first!']);
            return redirect(route('checkout'));
        }

        $order = $this->orderStore();
        $order_check = Order::where('pid', $this->pid)->first();

        if ($order_check) {
            $order_check->update(['order_status' => 1]);
            Cart::destroy();

            $this->dispatch('alert', ['type' => 'success', 'message' => 'Order successfully placed. Please wait for response!!!!']);
            return redirect(route('success.page', $order_check->pid));
        } else {
            $this->dispatch('alert', ['type' => 'error', 'message' => 'Order placement Failed!']);
            return redirect(route('frontend.index'));
        }
    }
}
