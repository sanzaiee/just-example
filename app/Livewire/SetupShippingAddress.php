<?php

namespace App\Livewire;

use App\Models\ShippingAddress;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use App\Services\CanadaPostService;
use App\Services\CanadaPostService_mock;

#[Layout('backend.master')]
class SetupShippingAddress extends Component
{
    public $shippingAddress, $authAddresses;

    #[Validate('required|min:3|max:80')]
    public $address="", $city="", $type="";
    // public $name, $address, $phone, $type, $city;
    #[Validate('nullable|min:1|max:80')]
    public $name, $phone, $email;

    // #[Validate('required|min:3|email|max:80')]
    // public $email;

    #[Validate('nullable|min:1|max:80')]
    public $street, $description, $house_no;

    // #[Validate('nullable|regex:Apt|Suite|Floor|Unit [A-Za-z0-9-]+')]
    // public $house_no;
    // public function rules(): array
    // {
    //     return [
    //         'house_no' => [
    //             'nullable',
    //             function ($attribute, $value, $fail) {
    //                 if (!empty($value) && !preg_match('/^(apt|suite|floor|unit) [A-Za-z0-9-]+$/i', $value)) {
    //                     $fail("Must start with apt, suite, floor, or unit.");
    //                 }
    //             }
    //         ],
    //     ];
    // }

    public function updatedHouseNo($value)
    {
        $this->validateOnly('house_no');
    }

    #[Validate('required|regex:/^[ABCEGHJ-NPRSTVXY]\d[ABCEGHJ-NPRSTV-Z] \d[ABCEGHJ-NPRSTV-Z]\d$/i')] #with space only
    public $postal_code="";

    protected $messages = [
        'postal_code.regex' => 'The postal code must be in the format A1A 1A1.',
        'house_no.regex' => 'The house number must be in the format Apt 101, Suite 202, Floor 3, Unit 4.',
        'type.required' => 'Please provide a name for the address.',
        'type.min' => 'The name must be at least 3 characters long.',
        'type.max' => 'The name must be no more than 80 characters long.',
    ];

    public function render()
    {
        return view('livewire.setup-shipping-address');
    }

    public function mount()
    {
        $this->authAddresses = ShippingAddress::where('user_id', auth()->id())->get();
    }

    public $actionVal = "list";

    public function action($action)
    {
        if ($action === 'add') {
            $count = $this->authAddresses->count();
            if ($count == 5) {
                $this->dispatch('alert', [
                    'type' => 'danger',
                    'message' => 'Shipping address exceed not more than 5!',
                ]);
                return;
            }
        }

        $this->actionVal = $action;
    }


    public function removeAddress($id)
    {
        $address = ShippingAddress::find($id);
        if (! $address) {
            $this->dispatch('alert', [
                'type' => 'warning',
                'message' => 'Address not found or already removed.',
            ]);
            return;
        }

        $address->delete();
        $this->dispatch('alert', [
            'type' => 'danger',
            'message' => 'Shipping address removed!',
        ]);

        $this->mount();
    }


    public $suggestions = [];
    public $isValidated = false;
    public bool $isLoading = false;
    public string $ValidatedValueOfType = '';
    public $selectedSuggestion='';

    protected function validateAddressFields()
    {
        $this->validateOnly('type');
        $this->validateOnly('house_no');
        $this->validateOnly('address');
        $this->validateOnly('city');
        $this->validateOnly('postal_code');
    }

    public function validateAddress()
    {
        $this->validateAddressFields();
        $this->ValidatedValueOfType = $this->type;

        $this->fetchAddressData();
        $this->dispatch('open-modal');
    }

    public function fetchAddressData()
    {
        if ($this->isLoading) return;
        $this->isLoading = true;

        try {
            $province = 'ON'; // Default province, can be made dynamic if needed
            $search = implode(' ', array_filter([
                $this->house_no,
                $this->address,
                $this->city,
                $this->postal_code,
                $province,
            ]));
            // $response = app(CanadaPostService::class)->find($search);
            $response = app(CanadaPostService_mock::class)->find($search);

            if ($response->hasSuggestions()) {
                $this->suggestions = $response->suggestions();
                // $this->suggestions = [];
            }
        } finally {
            $this->isLoading = false;
        }
    }

    public function selectSuggestion($value)
    {
        if (!$value) {
            $this->dispatch('alert', [
                'type' => 'danger',
                'message' => 'Internal Server Error. Please reload the page.',
            ]);
            return;
        }

        // Find suggestion by id
        $suggestion = collect($this->suggestions)
            ->first(fn($s) => (string)$s['id'] === (string)$value);

        if ($suggestion) {
            $this->fetchNewSuggestion($suggestion['id'], $suggestion['next']);
        } else {
            $this->dispatch('alert', [
                'type' => 'danger',
                'message' => 'Internal Server Error. Please reload the page.',
            ]);
        }
    }


    public function fetchNewSuggestion(string $id, string $next)
    {
        if ($this->isLoading) {
            return;
        }

        $this->isLoading = true;

        try {
            // ________________________
            // HANDLE "RETRIEVE" ACTION
            // ________________________

            if ($next === 'Retrieve') {
                // $result = app(CanadaPostService::class)->retrieve($id);
                $result = app(CanadaPostService_mock::class)->retrieve($id);
                $normalized = $result->normalized();

                // Fill fields from normalized data 
                // $this->type = $this->ValidatedValueOfType;
                // 
                $this->fill([
                    'name' => '',
                    'email' => '',
                    'address' => $normalized['address'] ?? '',
                    'street' => '',
                    'city' => $normalized['city'] ?? '',
                    'house_no' => $normalized['house_no'] ?? '',
                    'phone' => '',
                    'description' => $normalized['description'] ?? '',
                    'postal_code' => $normalized['postal_code'] ?? ''
                ]);

                try {
                    $data = $this->validate();
                    $data['user_id'] = auth()->id();
                } catch (\Illuminate\Validation\ValidationException $e) {
                    $this->dispatch('alert', [
                        'type' => 'danger',
                        'message' => 'Validation failed: ' . $e->getMessage(),
                    ]);
                    return;
                }

                $newAddress = ShippingAddress::create($data);

                // Dispatch job (runs immediately with sync queue) 
                $isSuccess = $this->shippingDistance($newAddress->id);
                if (! $isSuccess) {
                    $this->dispatch('alert', [
                        'type' => 'warning',
                        'message' => 'Failed to calculate shipping distance. Please try refreshing the distance after a few moments.',
                    ]);
                } else {
                    $this->dispatch('alert', [
                        'type' => 'success',
                        'message' => 'Shipping address added successfully!',
                    ]);
                }

                $this->dispatch('close-modal');

                $this->reset();
                $this->mount();
                return;
            }

            $this->suggestions = [];
            // Next = Find
            // $result = app(CanadaPostService::class)->findByLastId($id);
            $result = app(CanadaPostService_mock::class)->findByLastId($id);
            if ($result->hasSuggestions()) {
                $this->suggestions = $result->suggestions();
                // $this->suggestions = $result->suggestions();
            }
        } finally {
            $this->isLoading = false;
        }
    }

    private function shippingDistance(int $AddressId)
    {
        try {
            \App\Jobs\CalculateDistanceJob::dispatch($AddressId);
            return true;
        } catch (\Throwable $e) {
            logger()->error('Distance job failed: ' . $e->getMessage());
            return false;
        }
    }

    public function refreshShippingDistance($id)
    {
        $isSuccess = $this->shippingDistance($id);
        if (! $isSuccess) {
            $this->dispatch('alert', [
                'type' => 'danger',
                'message' => 'Failed to calculate shipping distance. Please report to admin.',
            ]);
        } else {
            $this->dispatch('alert', [
                'type' => 'success',
                'message' => 'Shipping distance calculated successfully!',
            ]);
        }
    }
}
