<?php

namespace App\Livewire\Cart;

use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Attributes\On;
use Livewire\Component;

class CartDropdown extends Component
{
    protected $listeners = ['cartUpdated' => 'render'];

    public $total = 0;

    public $cartItems = [];

    public $products = [];

    public function mount()
    {
        $this->refreshCart();
    }

    #[On('cartUpdated')]
    public function refreshCart()
    {
        $this->loadCartData();
    }

    /**
     * Load all cart data efficiently with eager loading
     */
    private function loadCartData(): void
    {
        $cartContent = Cart::content();

        if ($cartContent->isEmpty()) {
            $this->cartItems = [];
            $this->total = 0;

            return;
        }

        // Get all product IDs from cart
        $productIds = $cartContent->pluck('id')->unique()->toArray();

        // Eager load all products with their relationships
        $this->products = Product::with(['tieredPrices'])
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        // Prepare cart items with product data
        $this->cartItems = [];
        $total = 0;

        foreach ($cartContent as $item) {
            $product = $this->products[$item->id] ?? null;

            if (! $product) {
                continue;
            }

            // Use array instead of DTO for Livewire compatibility
            $this->cartItems[] = [
                'rowId' => $item->rowId,
                'id' => $item->id,
                'name' => $item->name,
                'price' => $item->price,
                'qty' => $item->qty,
                'subtotal' => $item->subtotal,
                'image' => $product->image,
                'slug' => $product->slug,
            ];

            $total += $item->subtotal;
        }

        $this->total = $total;
    }

    public function render()
    {
        return view('livewire.cart.cart-dropdown', [
            'items' => $this->cartItems,
            'total' => $this->total,
        ]);
    }

    public function increase($rowId)
    {
        $item = Cart::get($rowId);
        if (! $item) {
            return;
        }

        $totalQty = $item->qty + 1;
        $product = $this->products[$item->id] ?? Product::find($item->id);

        if ($product) {
            $price = $product->getPriceForQuantity($totalQty);

            Cart::update($rowId, [
                'price' => $price,
                'qty' => $totalQty,
            ]);
        }

        $this->refreshCart();
        $this->dispatch('keepDropdownOpen');
    }

    public function decrease($rowId)
    {
        $item = Cart::get($rowId);
        if (! $item) {
            return;
        }

        if ($item->qty > 1) {
            $totalQty = $item->qty - 1;
            $product = $this->products[$item->id] ?? Product::find($item->id);

            if ($product) {
                $price = $product->getPriceForQuantity($totalQty);
                Cart::update($rowId, [
                    'price' => $price,
                    'qty' => $totalQty,
                ]);
            }
        } else {
            Cart::remove($rowId);
        }

        $this->refreshCart();
        $this->dispatch('keepDropdownOpen');
    }

    public function clearCart()
    {
        Cart::destroy();

        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'Cart cleared!',
        ]);

        $this->refreshCart();
    }

    public function removeProductCart($rowId)
    {
        Cart::remove($rowId);

        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'Product cleared from cart!',
        ]);

        $this->refreshCart();
    }

    public function goToCheckoutPage()
    {
        if (Cart::count() == 0) {
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => 'Your cart is empty!',
            ]);

            return;
        }

        return redirect()->route('checkout');
    }
}
