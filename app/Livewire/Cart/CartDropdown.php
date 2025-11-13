<?php

namespace App\Livewire\Cart;

use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Attributes\On;
use Livewire\Component;

class CartDropdown extends Component
{
    protected $listeners = ['cartUpdated' => 'render'];

    public $total;

    public function mount()
    {
        $this->refreshCart();
    }

    #[On('cartUpdated')]
    public function refreshCart()
    {
        // $this->items = Cart::content();
        $this->total = Cart::total();
        $this->dispatch('refreshCart');
    }
    public function render()
    {
        return view('livewire.cart.cart-dropdown',[
            'items' => Cart::content(),
            'total' => $this->total,
        ]);
    }

    public function increase($rowId)
    {
        $item = Cart::get($rowId);
        Cart::update($rowId, $item->qty + 1);

        $this->refreshCart();

    }

    public function decrease($rowId)
    {
        $item = Cart::get($rowId);
        if ($item->qty > 1) {
            Cart::update($rowId, $item->qty - 1);
        } else {
            Cart::remove($rowId);
        }

        $this->refreshCart();
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

        // return redirect(route('checkout'));
        return redirect()->route('checkout');
    }
}
