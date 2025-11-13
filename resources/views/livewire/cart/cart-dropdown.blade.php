<div class="dropdown">
    <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
        <i class="bi bi-cart"></i> Cart ({{ Cart::count() }})
    </button>

    <ul class="dropdown-menu dropdown-menu-end p-3 shadow" style="min-width: 340px; max-height: 400px; overflow-y: auto;">
        <li class="d-flex justify-content-between mt-2">
            <button wire:click="clearCart"
                    class="btn btn-sm btn-outline-warning w-100"
                    onclick="event.stopPropagation()">
                <i class="bi bi-trash"></i> Clear All
            </button>
        </li>

        <li><hr class="dropdown-divider"></li>

        @forelse($items as $item)
        <li class="d-flex align-items-center mb-3">
            @php
            $product = App\Models\Product::find($item->id);
            @endphp
        
            {{-- Product Image --}}
            <img src="{{ $product->image }}" alt="{{ $item->name }}" class="rounded me-3"
                style="width: 50px; height: 50px; object-fit: cover;">
            {{-- Product Info --}}
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong> {{ $item->name }} </strong>
                        <div class="text-muted small">$ {{ $item->price }}</div>
        
                    </div>
                    <span wire:click="removeProductCart('{{ $item->rowId }}')" class="btn btn-outline-danger btn-xs">
                        X
                    </span>
                </div>
        
                {{-- Quantity Controls --}}
                <div class="d-flex align-items-center mt-1">
                    <button wire:click="decrease('{{ $item->rowId }}')" class="btn btn-xs btn-outline-danger me-1">
                        <i class="bi bi-dash"></i>
                    </button>
                    <span class="px-2">{{ $item->qty }}</span>
                    <button wire:click="increase('{{ $item->rowId }}')" class="btn btn-xs btn-outline-success ms-1">
                        <i class="bi bi-plus"></i>
                    </button>
        
                    <span class="ms-auto fw-semibold">$ {{ $item->subtotal }}</span>
                </div>
            </div>
        </li>
        <li>
            <hr class="dropdown-divider">
        </li>
        @empty
        <li class="text-center text-muted">Cart is empty</li>
        @endforelse

        <li class="d-flex justify-content-between">
            <strong>Total:</strong> <span class="fw-bold">$. {{ $total }}</span>
        </li>

        <li><hr class="dropdown-divider"></li>

        <li class="d-flex justify-content-between mt-2">
            <button type="button"
                    class="btn btn-sm btn-outline-success w-100"
                    {{ Cart::count() == 0 ? 'disabled' : '' }}
                    wire:click="goToCheckoutPage">
                <i class="bi bi-trash"></i> Checkout
            </button>
        </li>

    </ul>
</div>
