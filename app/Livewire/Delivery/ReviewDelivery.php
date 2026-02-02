<?php

namespace App\Livewire\Delivery;

use Livewire\Component;
use App\Models\Order;
Use App\Helpers\UberTokenHelper;
use App\Models\UberDeliveryTracking;

class ReviewDelivery extends Component
{
    public bool $processing = false;
    public bool $isComplete = false;
    public ?string $apiResponse = null;

    public Order $order;
    public function mount(Order $order)
    {
        $this->order = $order;
        // $this->apiResponse = json_encode([
        //             'code' => 200,
        //             'delivery_id' => $json['id'] ?? null,
        //             'status' => $json['status'] ?? null,
        //             'tracking_url' => 'https://delivery.uber.com/ca/orders/f52d2144-ed91-4101-8f34-d026a1050b24?tenancyOverride=uber%2Ftesting',
        //         ]);
    }

    public function createDelivery()
    {
        // HARD stop: prevents duplicate submission
        if ($this->processing) {
            return;
        }

        $this->processing = true;
        $this->apiResponse = null;
        
        try {
            //  sleep(5); // Simulate processing delay
            //  $this->apiResponse = 'Simulated delivery creation successful.';
            //  return;

            // $this->order->update(['order_status' => 4]); // Shipping in Progress
            // $this->order->update();
            // return;
            $response=UberTokenHelper::createDelivery($this->createDeliveryPayload($this->order));

            //  $this->apiResponse = 'Simulated delivery creation successful.';
            //  return;
            if ($response->successful()) {
                $json = $response->json();
                logger()->info('Uber Delivery Created:', $json);

                // Extract only the fields you care about
                $this->apiResponse = json_encode([
                    'code' => 200,
                    'delivery_id' => $json['id'] ?? null,
                    'status' => $json['status'] ?? null,
                    'tracking_url' => $json['tracking_url'] ?? null,
                ]);

                UberDeliveryTracking::create([
                    'order_id' => $this->order->id,
                    'tracking_number' => $json['id'] ?? null,
                    'status' => $json['status'] ?? null,
                    'message' => 'Delivery created successfully',
                    'tracking_url' => $json['tracking_url'] ?? null,
                    'delivery_id' => $json['id'] ?? null,
                    'delivery_status' => $json['status'] ?? null,
                    'delivery_message' => '',
                ]);

                $this->order->update(['order_status' => 4, 'pending_status' => 1]); // Shipping in Progress
            } else {
                $status = $response->status();
                $json = $response->json();

                // Special handling for address undeliverable
                if ($status === 400 && isset($json['code']) && $json['code'] === 'address_undeliverable') {
                    $this->apiResponse = json_encode([
                        'code' => 'address_undeliverable',
                        'message' => 'Delivery failed: The address cannot be delivered to. Please check the address.',
                    ]);
                }
                // Generic Uber API errors
                elseif (isset($json['code'], $json['message'])) {
                    $this->apiResponse = json_encode([
                        'code' => $json['code'],
                        'message' => $json['message'],
                    ]);
                }
                // Fallback for unknown response
                else {
                    $this->apiResponse = json_encode([
                        'code' => 'unknown_error',
                        'message' => "API returned error (status $status): " . ($response->body() ?? 'Unknown error'),
                    ]);
                }
            }
        } catch (\Throwable $e) {
            $this->apiResponse = 'Request failed: ' . $e->getMessage();
        } finally {
            $this->processing = false;
            $this->isComplete = true;
        }
    }

    private function createDeliveryPayload(Order $order): array
    {   
        $pickup  = \App\Services\UberDirectAddressFormatter::pickupAddress();
        $dropoff = \App\Services\UberDirectAddressFormatter::format($order->orderDeliveryAddress);

        #phone_number string ^\+[0-9]+$
        #{{ $order->user->name ?? '' }} {{ $order->user->lname ?? '' }}

        // 'street_address' => array_filter($streetParts), // "street_address": ["100 Maiden Ln", "Apt 101"],
        //     'city'           => $address->city ?? '',
        //     'state'          => 'Ontario',
        //     'zip_code'       => $address->postal_code ?? '',
        //     'country'


        return [
            'pickup_name' => 'Bayview Jug Milk',
            'pickup_address' => json_encode([
                'street_address' => $pickup['street_address'],
                'city'           => $pickup['city'],
                'state'          => $pickup['state'],
                'zip_code'    => $pickup['zip_code'],
                'country'        => $pickup['country']      
                ]),
            'pickup_phone_number' => '6475566452',
            'pickup_business_name' => 'Bayview Jug Milk',
            'pickup_notes' => 'PLEASE READ! Pickup at unit #101, which is on the "OUTSIDE" towards the East-side of the Condo Hayden Street entrance. Go through the black gate and ring the doorbell OR call 647-556-6452.', 
            'dropoff_name' => $this->order->user->name . ', ' . substr($this->order->user->lname ?? '', 0, 1), 
            'dropoff_address' => json_encode([
                    'street_address' => $dropoff['street_address'],
                    'city'           => $dropoff['city'],
                    'state'          => $dropoff['state'],
                    'zip_code'    => $dropoff['zip_code'],
                    'country'        => $dropoff['country'],
                ]),
            'dropoff_phone_number' => $order->user->mobile ?? '12345678990',
            'manifest_items' => [
                [
                    'name'     => 'Package',
                    'quantity' => 1,
                    'size'     => 'small',
                ],
            ],
            'dropoff_notes' => $this->order->notes ?? '',
            'dropoff_seller_notes' => '',
            'return_notes' => 'Return package to Unit #101. Leave inside the black storage bin and send photo image proof of return to: 647-556-6452. Thank you',
        ];
        //'pickup_ready_dt' => now()->addMinutes(10)->toIso8601String(),
        //'dropoff_deadline_dt' => now()->addHours(2)->toIso8601String(),
    }


    public function render()
    {
        return view('livewire.delivery.review-delivery-admin');
    }
}
