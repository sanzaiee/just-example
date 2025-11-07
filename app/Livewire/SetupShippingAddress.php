<?php

namespace App\Livewire;

use App\Models\ShippingAddress;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('backend.master')]
class SetupShippingAddress extends Component
{
    public $shippingAddress, $authAddresses;

    #[Validate('required|min:3|max:80')]
    public $name, $address, $phone, $type;

    #[Validate('required|min:3|email|max:80')]
    public $email;

    #[Validate('nullable|min:1|max:80')]
    public $city, $street, $house_no, $description;


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
        $this->actionVal = $action;
    }

    public function save()
    {
        $data = $this->validate();
        $data['user_id'] = auth()->id();
        if($this->shippingAddress){
            $this->shippingAddress->update($data);

            $this->dispatch('alert', [
                'type' => 'success',
                'message' => 'Shipping address updated!',
            ]);

        }else{
            $count = $this->authAddresses->count();
            if($count < 3){
                ShippingAddress::create($data);

                $this->dispatch('alert', [
                    'type' => 'success',
                    'message' => 'Shipping address updated!',
                ]);

            }else{
                 $this->dispatch('alert', [
                    'type' => 'danger',
                    'message' => 'Shipping address exceed not more than 3!',
                ]);
            }
        }
        $this->reset();
        $this->mount();
    }

    public function removeAddress($id)
    {
        $address = ShippingAddress::find($id);
        $address->delete();

        $this->dispatch('alert', [
            'type' => 'danger',
            'message' => 'Shipping address removed!',
        ]);

        $this->mount();
    }

    public function activeAddress($id)
    {
        $address = ShippingAddress::find($id);
        $address->update(['active' => !$address->active]);

        $this->dispatch('alert', [
            'type' => 'danger',
            'message' => 'Shipping address status marked!',
        ]);

        $this->mount();
    }

    public function editAddress($id)
    {
        $this->shippingAddress = ShippingAddress::find($id);
        $this->setupAddress($this->shippingAddress);
        $this->actionVal = "add";

    }

    public function setupAddress($address)
    {
        $this->type =$address->type;
        $this->name =$address->name;
        $this->email =$address->email;
        $this->address =$address->address;
        $this->street =$address->street;
        $this->city =$address->city;
        $this->house_no =$address->house_no;
        $this->phone =$address->phone;
        $this->description =$address->description;
    }
}
