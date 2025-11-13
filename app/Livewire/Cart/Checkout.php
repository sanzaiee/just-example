<?php

namespace App\Livewire\Cart;

use App\Models\Order;
use App\Models\OrderProductList;
use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Str;

class Checkout extends Component
{
    public $subTotal =0,$cartCount = 0,$discount =0, $total = 0, $pid;
    public function render()
    {
         return view('livewire.cart.checkout',[
            'list' => Cart::content(),
            // 'total' => $this->total,
            'subTotal' => $this->subTotal,
            'cartCount' =>$this->cartCount

        ]);
    }

    public function mount()
    {
        $this->subTotal =  Cart::subtotal();
        $this->cartCount = Cart::content()->count();
        // $this->total = Cart::total();
        $this->pid = "P-id" . "-" . Str::random(5);

    }

     #[On('refreshCart')]
    public function refreshCart()
    {
        $this->subTotal =  Cart::subtotal();
        $this->cartCount = Cart::content()->count();
        // $this->total = Cart::total();
    }


    public function decreaseQty($id, $qty)
    {
        $cartContents = Cart::content();
        $arrayKeys = array_keys($cartContents->toArray());
        Cart::update($arrayKeys[$id], --$qty);

        $this->refreshCart();

        $this->dispatch('cartUpdated');

        $this->dispatch(
            'alert',
            ['type' => 'success',  'message' => 'Item Decreased!']
        );    }

    public function increaseQty($id, $qty)
    {
        $cartContents = Cart::content();
        $arrayKeys = array_keys($cartContents->toArray());
        Cart::update($arrayKeys[$id], ++$qty);

        $this->refreshCart();

        $this->dispatch('cartUpdated');

        $this->dispatch(
            'alert',
            ['type' => 'success',  'message' => 'Item Increased!']
        );
    }

    public function removeFromCart($id)
    {
        $cartContents = Cart::content();
        $arrayKeys = array_keys($cartContents->toArray());
        Cart::remove($arrayKeys[$id]);

        $this->refreshCart();

        $this->dispatch('cartUpdated');

        $this->dispatch(
            'alert',
            ['type' => 'success',  'message' => 'Item Removed!']
        );

    }

    public function orderStore()
    {
        $db['pid'] = $this->pid;
        $db['quantity'] = Cart::content()->count();
        $db['user_id'] = auth()->id();
        // $db['amount'] =  $this->total;
        $db['amount'] =  $this->subTotal;
        $db['discount'] = $this->discount;
        $db['shipping_address_id'] = auth()->user()->shippingAddress->id;

        $x_order = Order::where('pid', $this->pid)->where('order_status', 0)->first();
        if ($x_order) {
            $x_order->forceDelete();
        }
        $order = Order::create($db);

        if ($order) {
            $list = Cart::content();
            foreach ($list as $index => $item) {
                OrderProductList::create([
                    'order_id' => $order->id,
                    'product_id' => $item->id,
                    'quantity' => $item->qty,
                    'price' => $item->price,
                ]);
            }
        }

        return $order;
    }

    public function checkout()
    {
        if(auth()->user()->shippingAddress == null){
            $this->dispatch(
                'alert',
                ['type' => 'error',  'message' => 'Please add shipping address first!']
            );
            return redirect(route('checkout'));
        }   

        $order = $this->orderStore();
        //re-checking the process
        $order_check = Order::where('pid', $this->pid)->first();
        if ($order_check) {
            $order_check->update([
                'order_status' => 1
            ]);

            // $this->sendSMS($order->pid, $order->user->display_name);
            // Mail::to($order->user->email)->send(new ClientOrderMail($order));
            // Mail::to(get_email())->send(new AdminOrderMail($order));

            Cart::destroy();

            $this->dispatch(
                'alert',
                ['type' => 'success',  'message' => 'Order successfully placed. Please wait for response!!!!']
            );
            return redirect(route('success.page', $order_check->pid));
        } else {

            $this->dispatch(
                'alert',
                ['type' => 'error',  'message' => 'Order placement Failed!']
            );
            return redirect(route('landing.page'));
        }
    }

}
