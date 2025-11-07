<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderCancel;
use App\Models\OrderProductList;
use App\Models\ShippingAddress;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user','orderProductLists','orderProductLists.product','shippingAddress')->where('user_id',auth()->id())->latest()->get();
        if(auth()->user()->is_admin){
            return view('backend.checkout.orders',compact('orders'));
        }
            return view('backend.checkout.user_orders',compact('orders'));

    }
    public function successPage($pid)
    {
        $order = Order::wherePid($pid)->first();
        $productList = OrderProductList::with('product')->where('order_id', $order->id)->get();
        $deliveryAddress = ShippingAddress::find($order->shipping_address_id);
        return view('backend.checkout.orderSuccess', compact('order', 'productList', 'deliveryAddress'));
    }

    public function cancelOrder()
    {
        $title = "Cancelled Order";

        $order = Order::where('cancel_status', 1)->orderBy('id', 'desc')->paginate(20);
        return view('admin.order.cancel', compact('order', 'title'));
    }

    public function delivery($id)
    {
        $delivery = Order::findorFail($id);
        $delivery['delivery_status'] = !$delivery['delivery_status'];
        $delivery->update();

        return back()->with('success', 'Delivery Status Changed.');
    }


    public function pending($id)
    {
        $pending = Order::findorFail($id);
        $pending['pending_status'] = !$pending['pending_status'];
        $pending->update();

        return back()->with('success', 'Order Status Changed.');
    }

    public function payStatus($id)
    {
        $pay = Order::findorFail($id);
        $pay['pay_status'] = !$pay['pay_status'];
        $pay->update();

        return back()->with('success', 'Pay Status Changed.');
    }

    public function orderCancel(Request $request)
    {
        $order = Order::findorFail($request->order_id);
        $order->update(['cancel_status' => 1]);
        OrderCancel::create([
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'reason' => $request->reason
        ]);

        return back()->withSuccess('Order Cancelled Successfully!!');
    }

}
