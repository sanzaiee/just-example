<?php

namespace App\Http\Controllers\WebHook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class UberWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Ignore anything that isn't a delivery status event
        logger()->info('Webhook Recieved');
        if ($request->input('kind') !== 'event.delivery_status') {
        //if ($request->input('kind', '') !== 'event.delivery_status')     
            // return response()->json(['ignored' => true]);
            logger()->info('Webhook Recieved: kind ' . $request->input('kind'));
            return;
        }

        $deliveryId = $request->input('delivery_id');
        $status     = $request->input('status');

        if (! $deliveryId || ! $status) {
            logger()->info('Webhook: missing fields', $request->all());
            return;
        }


        if (strtolower($status) !== 'delivered') {
            return;
        }

        //Uber_delievery_tracking::where('delivery_id', $deliveryId)

        // Example: update your delivery (order status)
        // Fetch delivery_id from database then fetch order using that
        //Order::where('id', $this->id)->first();
        // OR
        // Order::where('id', $deliveryId)
        //     ->update(['order_status' => 3]); // Mark Complete
    }
}