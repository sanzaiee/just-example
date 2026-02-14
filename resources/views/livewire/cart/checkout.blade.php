<div>
    <div class="row g-3">
        @if ($cartCount == 0 || $subTotal == 0 )
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Note:</strong> Items in cart does not look right.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="col-md-8">
            <div class="table-responsive">
                <table class="table">
                    <tbody>
                        @forelse ($cartItems as $item)
                            <tr class="" wire:key="cart-item-{{ $item['rowId'] }}">
                                <td
                                    class="d-flex flex-column align-items-center justify-content-center text-center text-nowrap">
                                    <a href="{{ route('product.detail', $item['slug']) }}">
                                        <img src="{{ $item['image'] }}"
                                            class="rounded float-start img-thumbnail blur-up lazyload" alt=""
                                            width="100px" height="100px">
                                    </a>
                                    <a href="{{ route('product.detail', $item['slug']) }}"
                                        class="text-decoration-none text-dark fw-bold mt-2">{{ $item['name'] }}</a>
                                </td>

                                <td class="price">
                                    <h6 class="fw-bold">Price</h6>
                                    <p>${{ number_format($item['unitPrice'], 2) }}</p>
                                </td>

                                <td class="quantity px-0">
                                    <h6 class="table-title text-content" style="text-align: center">Qty</h6>
                                    <div class="input-group input-group-sm">
                                        <button type="button" class="btn btn-outline-primary"
                                            wire:click="decreaseQty('{{ $item['rowId'] }}')">
                                            <i class="fa fa-minus"></i>
                                        </button>

                                        <input type="number" class="form-control text-center" min="1"
                                            max="5" value="{{ $item['qty'] }}" readonly>

                                        <button type="button" class="btn btn-outline-primary"
                                            wire:click="increaseQty('{{ $item['rowId'] }}')">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </td>

                                <td class="subtotal" style="text-align: center">
                                    <h6 class="fw-bold">Sub Total</h6>
                                    <p>${{ number_format($item['subtotal'], 2) }}</p>
                                </td>

                                <td class="save-remove px-0">
                                    <button class="btn btn-outline-danger btn-xs text-danger"
                                        wire:click="removeFromCart('{{ $item['rowId'] }}')">
                                        <i class="fa fa-trash fa-2x"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <p class="text-muted">Your cart is empty</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3">
                <div class="">
                    <h6 class="mb-3 fw-semibold">Delivery Method</h6>
        
                    <!-- Store Pickup -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" wire:model.live="delivery_method" name="delivery_method" value="pickup">
                            <label class="form-check-label" for="storePickup">
                                Store Pickup
                            </label>
                        </div>
                        <span class="badge bg-primary">9 AM – 9 PM</span>
                    </div>
        
                    <!-- Delivery -->
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input class="form-check-input" for="homeDelivery" type="radio" wire:model.live="delivery_method" name="delivery_method" value="delivery">
                            <label class="form-check-label" name="homeDelivery">
                                Ship to
                            </label>
                        </div>

                        <select class="form-select w-50" name="shipping_id" wire:model.live="shipping_id" @disabled($delivery_method !== 'delivery')>
                            <option value="" selected>Select Address</option>
                            @foreach ($shippings as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->type }}
                                </option>
                            @endforeach
                        </select>
                        
                    </div>
                    
                    <div class="d-flex align-items-center justify-content-center">
                        @if ($this->shippingDescription)
                            <small class="text-muted d-block mt-1">
                                {{ $this->shippingDescription }}
                            </small>
                        @endif
                    </div>

                    <div class="align-items-center justify-content-center mt-2">
                        <label class="form-label" for="notes">Notes</label>
                        <textarea wire:model.defer="deliveryNotes" id="notes" maxlength="255" class="form-control"></textarea>
                        <small class="text-muted" id="notes-count">max 255 characters</small>
                    </div>

                </div>
        
            </div>
            <br>
            <div class="card p-3">
                <div class="card-title">
                    <h5>Summary</h5>
                </div>
                <div class="">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0">Total Items:</h6>
                        <span class="badge bg-primary">{{ $cartCount }}</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0">Subtotal:</h6>
                        <p class="mb-0 fw-bold">${{ number_format($subTotal, 2) }}</p>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0">Shipping Cost:</h6>
                        <p class="mb-0 fw-bold">${{ number_format($shipping_cost, 2) }}</p>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0">Total:</h6>
                        <p class="mb-0 fw-bold text-success">$ {{ number_format($subTotal + $shipping_cost, 2) }}</p>
                    </div>

                    <hr>

                    @if ($cartCount > 0)
                        <button class="btn btn-primary w-100"
                                @disabled(! $this->getCanEnableCheckoutButton())
                                wire:click="checkout"
                                wire:loading.attr="disabled"
                                wire:target="checkout">
                            <i class="fa fa-lock me-2"></i>
                            <span wire:loading.remove wire:target="checkout">
                                Proceed to Checkout
                            </span>
                            <span wire:loading wire:target="checkout">
                                Processing...
                            </span>
                        </button>
                    @else
                        <button class="btn btn-secondary w-100" disabled>
                            <i class="fa fa-shopping-cart me-2"></i>Cart is Empty
                        </button>
                    @endif
                    
                </div>
            </div>
        </div>
    </div>
</div>

{{-- @push('custom-scripts')
<script>
    const textarea = document.getElementById('notes');
    const counter = document.getElementById('notes-count');

    textarea.addEventListener('change', () => {
        const length = textarea.value.length;   
        counter.textContent = `${length} / 255`;

        // Optional: disable the checkout button locally if too long
        const btn = document.querySelector('button[wire\\:click="checkout"]');
        if (length > 255) {
            counter.style.color = 'red';
        } else {
            counter.style.color = 'inherit';
        }
    });
</script>
@endpush --}}
