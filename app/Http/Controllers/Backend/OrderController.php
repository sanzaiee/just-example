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
    //order_status = 0 => item in cart
    //order_status = 1 => order placed
    //order_status = 3 => Marked as Completed
    //order_status = 4 => Shipping in Progress
    public function index(Request $request)
    {
        $query = Order::with('user', 'orderProductLists', 'orderProductLists.product', 'shippingAddress');

        // Apply filters
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('pid')) {
            $query->where('pid', 'like', '%'.$request->pid.'%');
        }

        // For non-admin users, only show their orders
        if (! auth()->user()->is_admin) {
            $query->where('user_id', auth()->id());
        }

        $orders = $query->latest()->paginate(request('per_page', 10));

        if (auth()->user()->is_admin) {
            return view('backend.checkout.orders', compact('orders'));
        }

        return view('backend.checkout.user_orders', compact('orders'));
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
        $title = 'Cancelled Order';

        $order = Order::where('cancel_status', 1)->orderBy('id', 'desc')->paginate(20);

        return view('backend.order.cancle', compact('order', 'title'));
    }

    public function delivery($id)
    {
        $delivery = Order::findorFail($id);
        $delivery['delivery_status'] = ! $delivery['delivery_status'];
        $delivery->update();

        return back()->with('success', 'Delivery Status Changed.');
    }

    public function pending($id)
    {
        $pending = Order::findorFail($id);
        $pending['pending_status'] = ! $pending['pending_status'];
        $pending->update();

        return back()->with('success', 'Order Status Changed.');
    }

    public function payStatus($id)
    {
        $pay = Order::findorFail($id);
        $pay['pay_status'] = ! $pay['pay_status'];
        $pay->update();

        return back()->with('success', 'Pay Status Changed.');
    }

    public function orderCancel(Request $request)
    {
        $order = Order::findorFail($request->order_id);
        $order->update(['cancel_status' => 1]);
        OrderCancel::create([
            'order_id' => $order->id,
            'user_id' => auth()->id(),
            'reason' => $request->reason,
        ]);

        return back()->withSuccess('Order Cancelled Successfully!!');
    }

    public function complete($id)
    {
        $delivery = Order::findorFail($id);
        $delivery['order_status'] = 3;
        $delivery->update();

        return redirect()->route('order.index')->with('success', 'Order Status Changed.');
    }

    public function show($pid)
    {
        $order = Order::where('pid', $pid)->first();

        return view('backend.order.show', compact('order'));
    }

    public function updateNotes(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'notes' => 'nullable|string|max:1000',
        ]);
        
        $order = Order::find($request->order_id);
        $order->notes = $request->notes;
        $order->save();

        return back()->with('success', 'Order notes updated successfully.');
    }

    // public function show($pid)
    // {
    //     $order = Order::wherePid($pid)->first();
    //     $productList = OrderProductList::with('product')->where('order_id', $order->id)->get();
    //     $deliveryAddress = ShippingAddress::find($order->shipping_address_id);
    //     return view('backend.checkout.orderSuccess', compact('order', 'productList', 'deliveryAddress'));
    // }
}
