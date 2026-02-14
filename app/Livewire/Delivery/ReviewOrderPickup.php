<?php

namespace App\Livewire\Delivery;

use Livewire\Component;
use App\Models\Order;
use App\Services\EmailService;

class ReviewOrderPickup extends Component
{
    public $response = null;

    public Order $order;

    #[Validate('required|min:3|max:80')]
    public string|int $lockBoxNumber = '';

    public function mount(Order $order)
    {
        $this->order = $order;
        $this->lockBoxNumber = $order->admin_notes?? '';
    }

    public function saveLockBoxNumber()
    {
        $this->order->admin_notes = $this->lockBoxNumber;
        $this->order->save();
        $this->dispatch('alert', [
            'type' => 'info',
            'message' => 'Lock Box number updated successfully.',
        ]);
    }

    protected function rules(): array
    {
        return [
            'lockBoxNumber' => [
                'required',
                'min:3',
                'max:80',
                function ($attribute, $value, $fail) {
                    if ($value != $this->order->admin_notes) {
                        $fail('The lockbox number does not match our records. Please click on update');
                    }
                },
            ],
        ];
    }

    protected function messages(): array
    {
        return [
            'lockBoxNumber.required' => 'Please enter the lockbox number.',
            'lockBoxNumber.min'      => 'The lockbox number must be at least 3 characters.',
            'lockBoxNumber.max'      => 'The lockbox number cannot exceed 80 characters.',
            // The mismatch message is inside the closure rule
        ];
    }


    public function sendEmail(EmailService $emailService)
    {
        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {

            // Get the first error message for lockBoxNumber
            $message = $e->validator->errors()->first('lockBoxNumber');

            // Dispatch your event with the message
            // $this->dispatch('lockbox-validation-error', message: $message);
            
            $this->dispatch('alert', [
                'type' => 'warning',
                'message' => $message,
            ]);

            // Re-throw if you still want Livewire to show inline errors
            //throw $e;
            return;
        }

        $responses = $emailService->PickupOrderInstruction($this->order);

        $this->response = json_encode($responses);
        if ($responses['status']) {
            
            $this->dispatch('order-updated');

            $this->dispatch('alert', [
                'type' => 'info',
                'message' => 'Pickup email sent successfully.',
            ]);
        } else {
            $this->dispatch('alert', [
                'type' => 'danger',
                'message' => 'We have encountered an error.',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.delivery.review-order-pickup-admin');
    }
}
