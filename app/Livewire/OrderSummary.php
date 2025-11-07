<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('backend.master')]
class OrderSummary extends Component
{
    public function render()
    {
        return view('livewire.order-summary');
    }
}
