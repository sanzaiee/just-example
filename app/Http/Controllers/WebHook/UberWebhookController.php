<?php

namespace App\Http\Controllers\WebHook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderFulfillment;
use App\Models\OrderFulfillmentEvents;
use Illuminate\Support\Facades\DB;
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
            return "Missing delivery status event";
        }

        $deliveryId = $request->input('delivery_id');
        $Id = $request->input('id');
        $status     = strtolower($request->input('status'));

        if (! $deliveryId || ! $status) {
            logger()->info('Webhook: missing fields', $request->all());
            return "Webhook: missing fields";
        }

        $orderTracking = OrderFulfillment::where('tracking_number', $deliveryId)->first();
        if (!$orderTracking) {
            logger()->info('Delivery status not found in system', [
                'delivery_id' => $deliveryId,
                'status' => $status,
            ]);
            return "Webhook: ignored status";
        }

        DB::transaction(function () use ($orderTracking, $status) {
            // Update fulfillment 
            $orderTracking->update(['status' => $status]);

            // If status is one of the final states, update the order 
            $finalStatuses = ['delivered', 'canceled', 'returned', 'shopping_completed'];

            if (in_array($status, $finalStatuses, true)) {
                $orderTracking->order->update(['order_status' => 3, 'delivery_status' => 1,]);
            }
        });

        // Log delivery status update
        OrderFulfillmentEvents::create([
            'order_id'          => $orderTracking->order_id,
            'event_type'   => (string) $Id,
            'status'            => $status,
            'raw_payload'           => $request->all(),
        ]);

        logger()->info('Done..');
        return response()->json(null, 200);

        // return response()->noContent();
        // return response('', 204);

        // Update order status
        // Order::where('id', $orderTracking->order_id)
        //     ->update([
        //         'order_status' => 3, // marked complete
        //         'delivery_status' => 1 // delivery status
        //     ]);
    }
}
