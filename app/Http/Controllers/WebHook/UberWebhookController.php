<?php

namespace App\Http\Controllers\WebHook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\UberDeliveryTracking;

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

        switch ($status) {
            case 'delivered':
            case 'canceled':
            case 'returned':
            case 'shopping_completed':

                $orderTracking = UberDeliveryTracking::where('delivery_id', $deliveryId)->first();
                
                if ($orderTracking) {

                    // Update order status
                    Order::where('id', $orderTracking->order_id)
                        ->update([
                            'order_status' => 3, // marked complete
                            'delivery_status' => 1 // delivery status
                        ]);

                    // Log delivery status update
                    UberDeliveryTracking::create([
                        'order_id'          => $orderTracking->order_id,
                        'tracking_number'   => $Id ?? null,
                        'status'            => $status ?? null,
                        'message'           => $status,
                        'tracking_url'      => null,
                        'delivery_id'       => $deliveryId,
                        'delivery_status'   => $status,
                        'delivery_message'  => '',
                    ]);

                    logger()->info(
                        'Webhook: order completed',
                        ['order_id' => $orderTracking->order_id, 'delivery_id' => $deliveryId]
                    );

                } else {
                    logger()->warning(
                        'Webhook: order not found for delivery_id',
                        ['delivery_id' => $deliveryId]
                    );
                }

                
                break;
            default:
                // 🚫 Ignore all other statuses
                logger()->info('Ignored delivery status update', [
                    'delivery_id' => $deliveryId,
                    'status' => $status,
                ]);
                return "Webhook: ignored status";
                break;
                
        }
    }
}