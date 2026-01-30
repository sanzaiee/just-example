<div class="modal fade" wire:ignore.self id="autoSubmitModal" tabindex="-1" aria-labelledby="exampleModalLong"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="exampleModalLong">
                    <i class="fas fa-shipping-fast me-2"></i> Ready to Start Delivery ?
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4">
                <div class="row g-2 mb-2">
                    <div class="col mb-0">
                        <label for="nameBasic" class="form-label">Name</label>
                        <p class=" mb-0">{{ $order->user->name ?? '' }} {{ $order->user->lname ?? '' }}</p>
                    </div>

                    <div class="col mb-0">
                        <label for="post" class="form-label">Phone</label>
                        <p class=" mb-0">{{ $order->user->mobile ?? '' }}</p>
                    </div>
                </div>
                @if ($order->shipping_address_id != getStorePickupShippingId())
                <hr>
                <div class="row g-2 mb-2">
                    <div class="col mb-0">
                        <label for="nameBasic" class="form-label">Apt / Suite / Floor:</label>
                        <p class="mb-0">{{ $order->shippingAddress->house_no ?? '' }}</p>
                    </div>
                    <div class="col mb-0">
                        <label for="nameBasic" class="form-label">Street</label>
                        <p class="mb-0">{{ $order->shippingAddress->address ?? '' }}</p>
                    </div>
                </div>

                <div class="row g-2">
                    <div class="col mb-0">
                        <label for="nameBasic" class="form-label">City</label>
                        <p class="mb-0">{{ $order->shippingAddress->city ?? '' }}</p>
                    </div>
                    <div class="col mb-0">
                        <label for="nameBasic" class="form-label">Postal Code</label>
                        <p class="mb-0">{{ $order->shippingAddress->postal_code ?? '' }}</p>
                    </div>
                </div>
                @endif
                <br>

                <div class="row">
                    <div class="col mb-3">
                        <label for="description" class="form-label">Notes</label>
                        <p class="mb-0">{{ $order->notes ?? '' }}</p>
                    </div>
                </div>

                {{-- Disclaimer --}}
                @if (!$apiResponse)
                    <div class="alert alert-warning mb-0" role="alert" style="text-align: justify">
                        After clicking <strong>Start Delivery</strong>, the process cannot be undone.
                        Please do not close the window during the process.
                    </div>
                @endif

                @if ($apiResponse)
                    <div class="alert alert-info mt-2 mb-0" role="alert">
                        {{ $apiResponse }}
                    </div>
                @endif
                
            </div>
            <div class="modal-footer pt-0">
                <button class="btn btn-label secondary" data-bs-dismiss="modal" :disabled="$wire.processing">Close</button>

                @if (!$isComplete)
                    <button type="button" class="btn btn-primary" id="submitButton"
                        wire:click="createDelivery"
                        wire:loading.attr="disabled"
                        wire:target="createDelivery"
                        >
                        <span wire:loading.remove wire:target="createDelivery">Start Delivery</span>
                        <span wire:loading wire:target="createDelivery">Processing...</span>
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>