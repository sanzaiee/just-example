<?php

namespace App\Livewire\Cart;

use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class CartSetup extends Component
{
    public $product;

    public $prices;

    public $selected_quantity = 1;

    public $quantity = 1;

    public $detail = false;

    public $items = [];

    // protected $listeners = ['cartUpdated' => 'refreshCart'];

    public function mount($product, $detail = 1)
    {
        $this->product = $product;
        $this->detail = $detail;
        $this->prices = $this->product->tieredPrices;

    }

    public function render()
    {
        return view('livewire.cart.cart-setup');
    }

    public function cartSubmit()
    {
        $this->addToCart();
    }

    /**
     * Get the appropriate price for a given quantity
     */
    private function getPriceForQuantity($quantity)
    {
        // Use the exact tier price when user selects from dropdown
        return $this->product->getExactTierPrice($quantity);
    }

    public function addToCart()
    {
        if ($this->product->stock) {
            // Check if product already in cart
            $existing = Cart::search(function ($cartItem, $rowId) {
                return $cartItem->id === $this->product->id;
            });

            $currentQty = $existing->isNotEmpty() ? $existing->first()->qty : 0;
            $totalQty = $currentQty + $this->quantity;

            // Get price based on total quantity
            $price = $this->getPriceForQuantity($totalQty);

            if ($existing->isNotEmpty()) {
                $rowId = $existing->first()->rowId;
                Cart::update($rowId, $totalQty);
                // Update the price in cart to reflect the new tiered price
                $cartItem = Cart::get($rowId);
                Cart::update($rowId, [
                    'price' => $price,
                    'qty' => $totalQty,
                ]);
            } else {
                Cart::add(
                    $this->product->id,
                    $this->product->name,
                    $this->quantity,
                    $price,
                );
            }

            $this->dispatch('alert', [
                'type' => 'success',
                'message' => 'Product Added To Cart!',
            ]);
        } else {
            $this->dispatch('alert', [
                'type' => 'danger',
                'message' => 'Out of stock!',
            ]);
        }

        $this->dispatch('cartUpdated');
    }

    public function add($id, $name, $price, $qty = 1)
    {
        Cart::add($id, $name, $qty, $price);
        $this->refreshCart();

    }

    public function decreaseQty()
    {
        $this->quantity = $this->quantity - 1;
        $this->resetQuantity();

    }

    public function increaseQty()
    {
        $this->quantity = $this->quantity + 1;
        $this->resetQuantity();
    }

    public function resetQuantity()
    {
        if ($this->quantity < 1) {
            $this->quantity = 1;
        } elseif ($this->quantity > 5) {
            $this->quantity = 5;
        }
    }

    public function updateQty($rowId, $qty)
    {
        // Get the cart item to identify the product
        $cartItem = Cart::get($rowId);
        if ($cartItem) {
            $product = Product::find($cartItem->id);
            if ($product) {
                // Get the new price based on the updated quantity
                $newPrice = $product->getPriceForQuantity($qty);

                // Update both quantity and price
                Cart::update($rowId, [
                    'qty' => $qty,
                    'price' => $newPrice,
                ]);
            }
        }

        $this->refreshCart();
        $this->emit('cartUpdated');
    }

    public function remove($rowId)
    {
        Cart::remove($rowId);
        $this->refreshCart();
        $this->emit('cartUpdated');
    }

    public function clear()
    {
        Cart::destroy();
        $this->refreshCart();
        $this->emit('cartUpdated');
    }

    public function refreshCart()
    {
        $this->items = Cart::content();
    }

    public function cartSelectSubmit()
    {
        if (! $this->selected_quantity) {
            $this->dispatch('alert', [
                'type' => 'danger',
                'message' => 'Please select a quantity!',
            ]);

            return;
        }

        if ($this->product->stock) {
            // Check if product already in cart
            $existing = Cart::search(function ($cartItem, $rowId) {
                return $cartItem->id === $this->product->id;
            });

            $currentQty = $existing->isNotEmpty() ? $existing->first()->qty : 0;
            $totalQty = $currentQty + $this->selected_quantity;

            // Get price based on total quantity
            // $price = $this->getPriceForQuantity($totalQty);
            $price = $this->product->getPriceForQuantity($totalQty);

            if ($existing->isNotEmpty()) {
                $rowId = $existing->first()->rowId;
                Cart::update($rowId, $totalQty);
                // Update the price in cart to reflect the new tiered price
                Cart::update($rowId, [
                    'price' => $price,
                    'qty' => $totalQty,
                ]);
            } else {
                Cart::add(
                    $this->product->id,
                    $this->product->name,
                    $this->selected_quantity,
                    $this->product->getExactTierPrice($this->selected_quantity),
                );
            }

            $this->dispatch('alert', [
                'type' => 'success',
                'message' => 'Product Added To Cart!',
            ]);
        } else {
            $this->dispatch('alert', [
                'type' => 'danger',
                'message' => 'Out of stock!',
            ]);
        }

        $this->dispatch('cartUpdated');
    }
}
