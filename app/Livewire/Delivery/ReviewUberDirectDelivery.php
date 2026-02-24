<?php

namespace App\Livewire\Delivery;

use Livewire\Component;
use App\Models\Order;
Use App\Helpers\UberTokenHelper;
Use App\Helpers\UberTokenHelper_mock;
use App\Models\OrderFulfillment;
use App\Models\Setting;
use Carbon\Carbon;

class ReviewUberDirectDelivery extends Component
{
    public ?string $apiResponse = null;

    public Order $order;
    public function mount(Order $order)
    {
        $this->order = $order;
    }

    public bool $is_scheduled = false;
    public $scheduled_time=null;
    protected function rules()
    {
        return [
            'is_scheduled'   => ['boolean'],
            'scheduled_time' => [
                function ($attribute, $value, $fail) {
                    if (!$this->is_scheduled) {
                        return; // no validation needed
                    }

                    if (!$value) {
                        return $fail('Please select a pickup time.');
                    }

                    $now = now('America/Toronto');
                    $pickup = \Carbon\Carbon::parse($value, 'America/Toronto')
                        ->setDate($now->year, $now->month, $now->day);

                    if ($pickup->isPast()) {
                        $fail('Pickup time cannot be earlier than the current Toronto time.');
                    }
                }
            ],
        ];
    }

    public function toggleSchedule($value)
    {
        $this->is_scheduled = (bool) $value;
    }

    public function createDelivery()
    {
        $this->validate();
        try {
            // sleep(5);
            // $this->apiResponse = json_encode([
            //         'code' => 200,
            //         'delivery_id' => '123',
            //         'status' => 'success',
            //         'tracking_url' => '123.com',
            //         'uuid' => '1123',
            //     ]);
            // $this->dispatch('order-updated');
            // return;

            //  sleep(10); // Simulate processing delay
            //  $this->apiResponse = 'Simulated delivery creation successful.';
            //  return;

            //$response=UberTokenHelper::createDelivery($this->createDeliveryPayload($this->order));
            $response=UberTokenHelper_mock::createDelivery();

            if ($response->successful()) {
                $json = $response->json();
                logger()->info('Uber Delivery Created:', $json);

                $orderUUID = $json['uuid'] ?? null;
                $lastFive = $orderUUID
                    ? substr($orderUUID, -5)
                    : null;
                
                OrderFulfillment::create([
                    'order_id' => $this->order->id,
                    'tracking_number' => $json['id'] ?? null,
                    'delivery_partner' => 0, //for uber
                    'status' => $json['status'] ?? null,
                    'message' => 'Delivery created successfully',
                    'tracking_url' => $json['tracking_url'] ?? null,
                    'created_by' => auth()->id() //for auditing
                ]);

                $this->order->update(['order_status' => 4, 'pending_status' => 1, 'admin_notes' => $lastFive]);

                // Extract only the fields you care about
                $this->apiResponse = json_encode([
                    'code' => 200,
                    'delivery_id' => $json['id'] ?? null,
                    'status' => $json['status'] ?? null,
                    'tracking_url' => $json['tracking_url'] ?? null,
                    'uuid' => $lastFive,
                ]);

                $this->dispatch('alert', [
                    'type' => 'info',
                    'message' => 'Delivery created.',
                ]);

            } else {
                $status = $response->status();
                $json = $response->json();

                logger()->info('_________________');
                logger()->info('Uber Delivery Failed:', $json);
                $payload = $this->createDeliveryPayload($this->order);
                $payloadString = json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                logger()->info($payloadString);
                logger()->info('_________________');

                if (isset($json['code'], $json['message'])) {
                    $tempResponse = [
                        'code' => $json['code'],
                        'message' => $json['message'],
                    ];
                    // Add "kind" only if present 
                    if (isset($json['kind'])) $tempResponse['kind'] = $json['kind']; 
                    
                    // Add "metadata" only if present 
                    if (isset($json['metadata'])) $tempResponse['metadata'] = $json['metadata'];

                    $this->apiResponse = json_encode($tempResponse);
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
        }
    }

    private bool $isExecuted = false;
    public function closeModal()
    {
        logger()->info('I am called');
        if($this->isExecuted) return;

        $this->dispatch('close-modal');

        if($this->order->order_status == 4)
            $this->dispatch('order-updated');

        $this->isExecuted = true;
    }

    private function createDeliveryPayload(Order $order): array
    {   
        $pickup  = \App\Services\UberDirectAddressFormatter::pickupAddress();
        $dropoff = \App\Services\UberDirectAddressFormatter::format($order->orderDeliveryAddress);

        $required = [
            'uber_direct_pickup_instructions',
            'uber_direct_return_label_instructions',
        ];

        $settings = Setting::whereIn('attribute', $required)
            ->get()
            ->keyBy('attribute');

        foreach ($required as $attribute) {
            if (!isset($settings[$attribute])) {
                throw new \Exception("Missing required setting: {$attribute}. Please create it in the settings table.");
            }
        }

        $PickUpInstructions = $settings['uber_direct_pickup_instructions']->value;
        $ReturnInstructions = $settings['uber_direct_return_label_instructions']->value;

        #phone_number string ^\+[0-9]+$
        #{{ $order->user->name ?? '' }} {{ $order->user->lname ?? '' }}

        // 'street_address' => array_filter($streetParts), // "street_address": ["100 Maiden Ln", "Apt 101"],
        //     'city'           => $address->city ?? '',
        //     'state'          => 'Ontario',
        //     'zip_code'       => $address->postal_code ?? '',
        //     'country'

        if (!$order->user->mobile)
            throw new \RuntimeException('User phone number is missing');

        $payload = [
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
            'pickup_notes' => $PickUpInstructions, 
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
                    'size'     => 'medium',
                ],
            ],
            'manifest_total_value' => (int) round($order->amount * 100),
            'dropoff_notes' => $this->order->notes ?? '',
            'dropoff_seller_notes' => 'Thank you for shopping with us',
            'return_notes' => $ReturnInstructions
        ];
        //'pickup_ready_dt' => now()->addMinutes(10)->toIso8601String(),
        //'dropoff_deadline_dt' => now()->addHours(2)->toIso8601String(),
        if ($this->is_scheduled) {
            $payload['pickup_ready_dt'] = $this->pickup_ready_dt($this->scheduled_time);
        }
        return $payload;
    }

    private function pickup_ready_dt(string $timeInput): string
    {
        // Always use Toronto timezone 
        $now = Carbon::now('America/Toronto'); 
        
        // Build the pickup time for today in Toronto 
        $pickup = Carbon::parse($timeInput, 'America/Toronto') ->setDate($now->year, $now->month, $now->day);

        // Create a Carbon instance for today with the given time 
        $pickup = Carbon::now()->setTimeFromTimeString($timeInput);

        // If the selected time is already past, it would roll over to tomorrow 
        if ($pickup->isPast()) {
            throw new Exception("Currior Pickup time cannot be in the past. It will roll over to tomorrow.");
        }

        // Return RFC 3339 format required by Uber Direct 
        return $pickup->toRfc3339String();
    }

    public function render()
    {
        return view('livewire.delivery.review-uber-direct-delivery-admin');
    }
}
