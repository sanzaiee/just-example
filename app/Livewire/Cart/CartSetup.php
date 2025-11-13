<?php

namespace App\Livewire\Cart;

use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class CartSetup extends Component
{
    public $product, $quantity= 1, $detail = false;

    public $items = [];

    // protected $listeners = ['cartUpdated' => 'refreshCart'];

    public function mount($product, $detail = 1)
    {
        $this->product = $product;
        $this->detail = $detail;

    }

    public function render()
    {
        return view('livewire.cart.cart-setup');
    }

    public function cartSubmit()
    {
        $this->addToCart();
    }

    public function addToCart()
    {
        if($this->product->stock){
            // Check if product already in cart
            $existing = Cart::search(function ($cartItem, $rowId) {
                return $cartItem->id === $this->product->id;
            });

            if ($existing->isNotEmpty()) {
                $rowId = $existing->first()->rowId;
                Cart::update($rowId, $existing->first()->qty + $this->quantity);
            } else {
                Cart::add(
                    $this->product->id,
                    $this->product->name,
                    $this->quantity,
                    $this->product->price,
                );
            }

            $this->dispatch('alert', [
                'type' => 'success',
                'message' => 'Product Added To Cart!',
            ]);
        }else{
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
        if($this->quantity < 1){
            $this->quantity = 1;
        }elseif($this->quantity > 5){
            $this->quantity = 5;
        }
    }

    public function updateQty($rowId, $qty)
    {
        Cart::update($rowId, $qty);
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

}
