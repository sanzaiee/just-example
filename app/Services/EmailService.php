<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendOrderConfirmation;
use App\Mail\SendPickupOrderInstruction;
use App\Models\Order;
use App\Models\OrderFulfillment;

class EmailService
{
    public function PickupOrderInstruction(Order $order)
    {
        if (! $order) {
            throw new \Exception('Order object is empty');
        }

        $data =
            [
                'status' => true,
                'message' => ''
            ];
        $isSuccess = false;
        try {
            Mail::to($order->orderDeliveryAddress->email)->send(new SendPickupOrderInstruction($order));
            OrderFulfillment::create([
                'order_id' => $order->id,
                'created_by' => auth()->id(),
                'lockbox_number' => $order->admin_notes,
                'delivery_partner' => 0
            ]);

            $isSuccess = true;
        } catch (\Exception $e) {
            logger()->error('Error sending pickup email: ' . $e->getMessage());
            $data['message'] = $e->getMessage();
        } finally {
            $data['isSuccess'] = $isSuccess;
        }
        return $data;
    }

    public function OrderConfirmation(Order $order)
    {
        if (! $order) {
            throw new \Exception('Order object is empty');
        }

        $data =
            [
                'status' => true,
                'message' => ''
            ];
        $isSuccess = false;
        try {
            Mail::to($order->orderDeliveryAddress->email)->send(new SendOrderConfirmation($order));
            $isSuccess = true;
        } catch (\Exception $e) {
            logger()->error('Error sending pickup email: ' . $e->getMessage());
            $data['message'] = $e->getMessage();
        } finally {
            $data['isSuccess'] = $isSuccess;
        }
        return $data;
    }
}
