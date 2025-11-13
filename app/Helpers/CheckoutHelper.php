<?php

namespace App\Helpers;

use App\Models\DeliveryAddress;
use App\Models\Order;
use App\Models\ShippingAddress;
use Illuminate\Support\Str;

class CheckoutHelper
{
    private  $address, $order;
    public function __construct(ShippingAddress $address, Order $order)
    {
        $this->address = $address;
        $this->order = $order;
    }

    public function deliveryAddresses($limit)
    {
        if ($limit) {
            return  $this->address->orderBy('created_at', 'desc')->limit($limit)->get();
        }
        return  $this->address->get();
    }

    public function pid()
    {
        return $pid = "P-id" . "-" . Str::random(5);
    }

    public function orderList($number)
    {
        if ($number) {
            return  $this->order->where('user_id', auth()->id())->orderBy('created_at', 'desc')->where('order_status', 1)->paginate($number);
        }
        return $this->order->where('user_id', auth()->id())->where('order_status', 1)->get();
    }

    public function invoice($pid)
    {
        return Order::with('shippingAddress', 'orderProductLists', 'user')->where('pid', $pid)->where('order_Status', 1)->first();
    }
}
