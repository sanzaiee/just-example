<div class="modal fade" wire:ignore.self id="autoSubmitModal" tabindex="-1" aria-labelledby="exampleModalLong"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="exampleModalLong">
                    <i class="fas fa-box me-2"></i> Ready for Pickup Order ?
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
                <div class="row">
                    <div class="col mb-3">
                        <label for="description" class="form-label">Customer Notes</label>
                        <p class="mb-0">{{ $order->notes ?? '' }}</p>
                    </div>
                </div>
                <hr>
                <form action="#" class="form form-inline">
                    <div class="row g-2 mb-2">
                        <div class="col mb-0">
                            <label for="nameBasic" class="form-label">LockBox Number:</label>
                            <input type="text" id="nameBasic" class="form-control" placeholder="" wire:model.defer="lockBoxNumber" />
                        </div>
                
                        <div class="col mb-0 d-flex align-items-end">
                            <button type="button" name="save" class="btn btn-secondary btn-sm" title="Save lock box number"
                                wire:click="saveLockBoxNumber" 
                                wire:loading.attr="disabled"
                                wire:target="saveLockBoxNumber,sendEmail"
                                >
                                <span wire:loading.remove wire:target="saveLockBoxNumber">Update</span>
                                <span wire:loading wire:target="saveLockBoxNumber">Updating ...</span>
                            </button>
                        </div>
                    </div>
                </form>

                {{-- Disclaimer --}}
                {{-- @if (!$apiResponse)
                    <div class="alert alert-warning mb-0" role="alert" style="text-align: justify">
                        <p>After clicking <strong>Start Delivery</strong>, the process cannot be undone.
                        Please do not close the window during the process.</p>
                    </div>
                @endif --}}

                @if ($response)
                    @php
                        $responseArray = json_decode($response, true);
                    @endphp

                    @if($responseArray['status'])
                        <div class="alert alert-success mt-2 mb-0" role="alert">
                            <p class="mb-0">Pickup email sent.</p>
                        </div>
                    @else
                        <div class="alert alert-info mt-2 mb-0" role="alert">
                            <p>Failed to send pickup email.</p>

                            <p>Here's the error message:</p>

                            <p>{{ $responseArray['message'] }}</p>
                        </div>
                    @endif
                @endif
            </div>
            <div class="modal-footer pt-0 d-flex justify-content-between">
                <button class="btn btn-label secondary m-0" data-bs-dismiss="modal" wire:loading.attr="disabled">Close</button>

                @if (!$response)
                    <button type="button" class="btn btn-primary m-0"
                        wire:click="sendEmail"
                        wire:loading.attr="disabled"
                        wire:target="sendEmail,saveLockBoxNumber"
                        >
                        <span wire:loading.remove wire:target="sendEmail">Send Email</span>
                        <span wire:loading wire:target="sendEmail">Processing...</span>
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>