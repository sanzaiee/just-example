<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderCancel;
use App\Models\OrderProductList;
use App\Models\OrderDeliveryAddress;
use Illuminate\Http\Request;
Use App\Helpers\UberTokenHelper;
Use App\Helpers\UberTokenHelper_mock;

class OrderController extends Controller
{
    //order_status = 0 => item in cart
    //order_status = 1 => order placed
    //order_status = 3 => Marked as Completed
    //order_status = 4 => Shipping in Progress
    public function index(Request $request)
    {
        $query = Order::with('orderProductLists', 'orderProductLists.product', 'orderDeliveryAddress');

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
        // return $orders;

        if (auth()->user()->is_admin) {
            return view('backend.checkout.orders', compact('orders'));
        }

        return view('backend.checkout.user_orders', compact('orders'));
    }

    public function successPage($pid)
    {
        $order = Order::wherePid($pid)->first();
        $productList = OrderProductList::with('product')->where('order_id', $order->id)->get();
        $deliveryAddress = OrderDeliveryAddress::where('order_id', $order->id)->get()->first();

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
        $delivery['pending_status'] = 1; // Set pending_status to true when marking as complete
        $delivery->update();

        return redirect()->route('order.index')->with('success', 'Order Status Changed.');
    }

    public function show($pid)
    {
        $order = Order::with('orderDeliveryAddress','orderProductLists', 'orderProductLists.product','latestFulfillment')->where('pid', $pid)->first();
        //return $order;
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

    public function ResendOrderConfirmationEmail($orderId, \App\Services\EmailService $emailServices)
    {
        $order = Order::with('user', 'orderProductLists.product', 'orderDeliveryAddress')->where('pid', $orderId)->first();
        if (! $order) {
            return "Order with number {$orderId} not found.";
        }

        // return $order;

        $response = $emailServices->OrderConfirmation($order);
        return "Order confirmation email resent {$response['status']} for order number {$orderId}";
    }

    public function ResendPickupEmail($orderId, \App\Services\EmailService $emailServices)
    {
        $order = Order::where('pid', $orderId)->first();
        if (! $order) {
            return "Order with number {$orderId} not found.";
        }

        $response = $emailServices->PickupOrderInstruction($order);
        return "Pickup Order Instruction sent {$response['status']} for Order: {$orderId}";
    }

    public function UpdateUberDeliveryStatus($orderId)
    {
        $order = Order::with('latestFulfillment')->where('pid', $orderId)->first();

        if (!$order) {
            return "Order with number {$orderId} not found.";
        }

        if ($order->is_store_pickup) {
            return "Order type is store pickup.";
        }

        $tracking = $order->latestFulfillment->tracking_number ?? null;

        if (!$tracking || !is_string($tracking) || trim($tracking) === '') {
            return "Uber delivery is not created yet.";
        }

        // Now call Uber API
        // $response = UberTokenHelper::getDeliveryUpdate($tracking);
        $response = UberTokenHelper_mock::getDeliveryUpdate($tracking);

        return $response->json();
    }


    // public function show($pid)
    // {
    //     $order = Order::wherePid($pid)->first();
    //     $productList = OrderProductList::with('product')->where('order_id', $order->id)->get();
    //     $deliveryAddress = OrderDeliveryAddress::find($order->shipping_address_id);
    //     return view('backend.checkout.orderSuccess', compact('order', 'productList', 'deliveryAddress'));
    // }
}
