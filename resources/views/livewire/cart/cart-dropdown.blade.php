<div class="dropdown" wire:target="increase,decrease,clearCart,removeProductCart">
    <button class="btn btn-outline-primary btn-sm dropdown-toggle d-flex align-items-center" type="button"
        data-bs-toggle="dropdown">
        <i class="bi bi-cart me-1"></i>
        <span>Cart ({{ count($items) }})</span>
    </button>

    <div class="dropdown-menu dropdown-menu-end p-0 shadow-lg" style="width: 360px;">
        {{-- Header with Clear Cart --}}
        <div class="dropdown-header d-flex justify-content-between align-items-center py-2 px-3 border-bottom">
            <h6 class="mb-0 fw-semibold">Shopping Cart</h6>
            @if(count($items) > 0)
                <button wire:click="clearCart" class="btn btn-sm btn-outline-danger" onclick="event.stopPropagation()">
                    <i class="bi bi-x-circle"></i>
                </button>
            @endif
        </div>

        {{-- Cart Items --}}
        <div class="dropdown-body" style="max-height: 300px; overflow-y: auto;">
            @forelse($items as $item)
                <div class="dropdown-item p-3 border-bottom">
                    <div class="d-flex align-items-start">
                        {{-- Product Image --}}
                        <div class="flex-shrink-0">
                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="rounded border"
                                style="width: 60px; height: 60px; object-fit: cover;">
                        </div>

                        {{-- Product Info --}}
                        <div class="flex-grow-1 ms-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="me-2">
                                    <h6 class="mb-1 fw-semibold text-truncate" style="max-width: 180px;">
                                        {{ $item['name'] }}</h6>
                                    <div class="text-muted small">
                                        <span class="fw-medium">${{ number_format($item['price'], 2) }}</span>
                                        <span class="mx-1">×</span>
                                        <span>{{ $item['qty'] }}</span>
                                    </div>
                                </div>
                                <button wire:click="removeProductCart('{{ $item['rowId'] }}')"
                                    class="btn btn-sm btn-link text-danger p-0" onclick="event.stopPropagation()"
                                    title="Remove item">
                                    <i class="bi bi-x-circle"></i>
                                </button>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <small class="text-muted">Subtotal:</small>
                                <span class="fw-bold text-success">${{ number_format($item['subtotal'], 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="dropdown-item text-center py-4">
                    <i class="bi bi-cart-x display-4 text-muted d-block mb-2"></i>
                    <p class="text-muted mb-0">Your cart is empty</p>
                </div>
            @endforelse
        </div>

        {{-- Footer with Total and Checkout --}}
        @if(count($items) > 0)
            <div class="dropdown-footer p-3 border-top bg-light">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 fw-semibold">Total:</h5>
                    <h5 class="mb-0 fw-bold text-primary">${{ number_format($total, 2) }}</h5>
                </div>

                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-primary btn-sm" wire:click="goToCheckoutPage"
                        onclick="event.stopPropagation()">
                        <i class="bi bi-cart-check me-2"></i> View Cart & Checkout
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>
