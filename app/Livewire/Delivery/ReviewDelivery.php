<?php

namespace App\Livewire\Delivery;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use App\Models\Order;
Use App\Helpers\UberTokenHelper;

class ReviewDelivery extends Component
{
    public bool $processing = false;
    public bool $isComplete = false;
    public ?string $apiResponse = null;

    public Order $order;
    public function mount(Order $order)
    {
        $this->order = $order;
    }

    public function createDelivery1()
    {
        $response = UberTokenHelper::request('post', 'https://example.com', [
        // $response = UberTokenHelper::request('post', 'https://api.uber.com/v1/deliveries', [
            'json' => [
                'pickup' => [
                    'address' => '123 Main St',
                    'contact_name' => 'John Doe'
                ],
                'dropoff' => [
                    'address' => '456 Elm St',
                    'contact_name' => 'Jane Smith'
                ]
            ]
        ]);

        return $response->json();
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
            // sleep(2); // Simulate processing delay
            // $this->order->update(['order_status' => 4]); // Shipping in Progress
            // $this->order->update();
            // return;
            $response=UberTokenHelper::createDelivery($this->createDeliveryPayload());

            if ($response->successful()) {
                $json = $response->json();

                // Extract only the fields you care about
                $this->apiResponse = json_encode([
                    'delivery_id' => $json['delivery_id'] ?? null,
                    'status' => $json['status'] ?? null,
                    'tracking_url' => $json['tracking_url'] ?? null,
                    'message' => 'Delivery created successfully',
                ]);
                //Uber_delievery_tracking
                //order->id
                //delivery->id

                $this->order->update(['order_status' => 4]); // Shipping in Progress
                $this->order->update();
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

    private function createDeliveryPayload(): array
    {   
        $pickup  = \App\Services\UberDirectAddressFormatter::pickupAddressformat();
        $dropoff = \App\Services\UberDirectAddressFormatter::deliveryAddressformat();

        #phone_number string ^\+[0-9]+$
        #{{ $order->user->name ?? '' }} {{ $order->user->lname ?? '' }}
        return [
            'pickup_name' => 'Bayview Jug Milk',
            'pickup_address' => json_encode([
                'street_address' => $pickup['street_address'],
                'city'           => $pickup['city'],
                'state'          => $pickup['state'],
                'postal_code'    => $pickup['postal_code'],
                'country'        => $pickup['country']      
                ]),
            'pickup_phone_number' => '14165551234', 
            'dropoff_name' => $this->order->user->name . ', ' . substr($this->order->user->lname ?? '', 0, 1), 
            'dropoff_address' => json_encode([
                    'street_address' => $dropoff['street_address'],
                    'city'           => $dropoff['city'],
                    'state'          => $dropoff['state'],
                    'postal_code'    => $dropoff['postal_code'],
                    'country'        => $dropoff['country'],
                ]),
            'dropoff_phone_number' => '14165559876',
            'manifest_items' => [
                [
                    'name'     => 'Package',
                    'quantity' => 1,
                    'size'     => 'small',
                ],
            ],
        ];
        //'pickup_ready_dt' => now()->addMinutes(10)->toIso8601String(),
        //'dropoff_deadline_dt' => now()->addHours(2)->toIso8601String(),
    }


    public function render()
    {
        return view('livewire.delivery.review-delivery-admin');
    }
}
