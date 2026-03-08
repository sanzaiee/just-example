<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use Livewire\Attributes\Layout;

#[Layout('backend.master')]
class ShowOrder extends Component
{
    public Order $order;

    public function mount($pid)
    {
        $this->order = Order::select('*')->with(
            'orderDeliveryAddress',
            'orderProductLists',
            'orderProductLists.product',
            'latestFulfillment'
        )->where('pid', $pid)->firstOrFail();
    }

    #[\Livewire\Attributes\On('order-updated')]
    public function refreshOrder()
    {
        $this->order->refresh(); // reload base model 
        $this->order->load('latestFulfillment');
        // $this->order = Order::with(
        //     'orderDeliveryAddress',
        //     'orderProductLists',
        //     'orderProductLists.product',
        //     'latestFulfillment'
        // )->find($orderId);
    }

    public function render()
    {
        return view('livewire.show-order');
    }
}

