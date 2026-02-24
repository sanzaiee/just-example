<div>
<div class="modal fade" id="reviewUberDeliveryModal" tabindex="-1" aria-labelledby="exampleModalLong" aria-hidden="true" wire:ignore.self >
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="exampleModalLong">
                    <i class="fas fa-shipping-fast me-2"></i> Ready to Start Delivery ?
                </h5>
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
                <div class="row g-2 mb-2">
                    <div class="col mb-0">
                        <label for="nameBasic" class="form-label">Address Line:</label>
                        <p class="mb-0">{{ $order->orderDeliveryAddress->address ?? '' }}</p>
                    </div>
                {{-- </div>

                <div class="row g-2"> --}}
                    <div class="col mb-0">
                        <label for="nameBasic" class="form-label">City</label>
                        <p class="mb-0">{{ $order->orderDeliveryAddress->city ?? '' }}</p>
                    </div>
                    <div class="col mb-0">
                        <label for="nameBasic" class="form-label">Postal Code</label>
                        <p class="mb-0">{{ $order->orderDeliveryAddress->postal_code ?? '' }}</p>
                    </div>
                </div>

                <!-- Notes Section -->
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label fw-semibold">Notes</label>
                        <div class="border rounded p-2 bg-light">
                            <p class="mb-0">{{ $order->notes ?? '—' }}</p>
                        </div>
                    </div>
                </div>
                
                <hr class="my-3">
                
                <!-- Schedule Section -->
                <div class="row align-items-center">
                    <div class="col-auto mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_scheduled" 
                            wire:change="toggleSchedule($event.target.checked)" 
                            wire:loading.attr="disabled" id="is_scheduled">
                            <label class="form-check-label fw-semibold" for="is_scheduled">
                                Schedule courier pickup ?
                            </label>
                        </div>
                    </div>
                
                    <div class="col-auto mb-3">
                        <input type="time" class="form-control @error('scheduled_time') is-invalid @enderror" name="scheduled_time"
                            id="scheduled_time" 
                            wire:model="scheduled_time" 
                            @disabled(!$is_scheduled) 
                            >
                        @error('scheduled_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Disclaimer --}}
                @if (!$apiResponse)
                    <div class="alert alert-warning mb-0" role="alert" style="text-align: justify">
                        <p class="m-0 p-0">After clicking <strong>Start Delivery</strong>, the process cannot be undone.</p>
                    </div>
                @endif

                @if ($apiResponse)
                    @php
                        $response = json_decode($apiResponse, true);
                    @endphp

                    @if(($response['code'] ?? null) == 200)
                    <div class="alert alert-success mt-2 mb-0" role="alert">
                        <p class="mb-1 fw-semibold">Delivery created successfully</p>
                    
                        <p class="mb-1">
                            <a href="{{ $response['tracking_url'] }}" target="_blank" rel="noopener">
                                Click here to track
                            </a>
                        </p>
                    
                        <p class="small text-muted mb-2">
                            {{ $response['tracking_url'] }}
                        </p>
                    
                        <p class="mb-0">
                            <span class="fw-semibold text-dark">Order ID:</span>
                            {{ $response['uuid'] }}
                        </p>
                    </div>
                    @else
                    <div class="alert alert-info mt-2 mb-0" role="alert">
                        <p>{{ $apiResponse }}</p>
                    </div>  
                    @endif
                @endif
            </div>
            <div class="modal-footer pt-0 d-flex justify-content-between">
                <button class="btn btn-label secondary" data-bs-dismiss="modal" wire:loading.attr="disabled" wire:click="closeModal">Close</button>

                @if (!$apiResponse)
                    <button type="button" class="btn btn-primary"
                        wire:click="createDelivery"
                        wire:loading.attr="disabled"
                        wire:target="toggleSchedule,createDelivery"
                        >
                        <span wire:loading.remove wire:target="createDelivery">Start Delivery</span>
                        <span wire:loading wire:target="createDelivery">Processing...</span>
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

@push('custom-scripts')
<script>
    document.addEventListener('close-modal', () => { const modalEl = document.getElementById('reviewUberDeliveryModal'); if (!modalEl) return; let modal = bootstrap.Modal.getInstance(modalEl); if (!modal) { modal = new bootstrap.Modal(modalEl); } modal.hide(); });
</script>
@endpush
</div>