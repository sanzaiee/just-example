<div>
    @push('custom-scripts')
        <script>
            document.addEventListener('livewire:init', () => {
                Livewire.on('open-modal', () => {
                    const modalEl = document.getElementById('exampleModalLong');
                    const modal = new bootstrap.Modal(modalEl,{
                        backdrop: 'static',
                        keyboard: false
                    });
                    
                    modal.show();

                    console.log("Modal shown");
                });
                Livewire.on('close-modal', () => {
                    location.reload();
                });
            });
        </script>
    @endpush

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="py-2"><span class="text-muted fw-light">Shipping Address /</span>
            @if($actionVal == "list")
            List
            @elseif($actionVal == "add")
            Create
            @else
            In Valid
            @endif

            <div class="text-muted float-end">
                <span wire:click="action('add')" class="btn btn-sm btn-primary">
                    Add
                </span>
                <span wire:click="action('list')" class="btn btn-sm btn-primary">
                    List
                </span>
            </div>
        </div>

        <div class="m-1 row g-3">
            @if($actionVal == "add")
            <div class="card p-2 col-md-12">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Shipping Address</h5>
                </div>

                <form wire:submit.prevent="validateAddress">
                    <div class="card-body pb-0">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="type">Save Address As</label>
                                <input type="text" class="form-control" wire:model.defer="type" placeholder="Home">
                                @error('type')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="address">Address Line</label>
                                <input type="text" class="form-control" wire:model.defer="address">
                                @error('address')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="city">City</label>
                                <input type="text" class="form-control" wire:model.defer="city">
                                @error('city')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="postal_code">Postal Code</label>
                                <input type="text" class="form-control" wire:model.defer="postal_code" placeholder="A1A 1A1">
                                @error('postal_code')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="province" class="form-label">Province</label>
                                <input type="text" id="province" class="form-control" value="Ontario" readonly
                                    style="background-color: lightgray">
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            {{-- <button class="btn btn-secondary" type="button">
                                Reset
                            </button> --}}
                            
                            <button type="submit" class="btn btn-primary" wire:click.prevent="validateAddress" wire:loading.attr="disabled"
                                wire:target="validateAddress">
                                <span wire:loading wire:target="validateAddress">
                                    Validating address . . .
                                </span>
                                <span wire:loading.remove wire:target="validateAddress">
                                    Validate Address
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
                
                <div class="modal fade" id="exampleModalLong" tabindex="-1" aria-labelledby="exampleModalLongTitle" aria-hidden="true"
                    data-backdrop="static" data-keyboard="false" wire:ignore.self>
                    <div class="modal-dialog modal-dialog-centered modal-md">
                        <div class="modal-content border-0 shadow-lg rounded-4">
                
                            <!-- Header -->
                            <div class="modal-header border-0">
                                <h5 class="modal-title fw-semibold" id="exampleModalLongTitle">
                                    {{$ValidatedValueOfType}}
                                </h5>
                            </div>
                
                            {{--
                            <!-- Text Input -->
                            <div class="mb-3">
                                <label class="form-label">Address search</label>
                                <input type="text" class="form-control" placeholder="Start typing address" id="lookup2" name="lookup2">
                            </div> --}}
                
                            <!-- Body -->
                            <div class="modal-body px-4">
                                <div wire:loading wire:target="selectSuggestion" class="text-center py-3">
                                    <div class="spinner-border text-primary" role="status"></div>
                                    <div class="mt-2 text-muted">Fetching address, please wait...</div>
                                </div>
                
                                <form wire:submit.prevent>
                                    {{-- Suggestions list --}}
                                    @if (!empty($suggestions))
                                    <ul class="list-group mb-3">
                                        @foreach ($suggestions as $suggestion)
                                        <li class="list-group-item p-0">
                                            <button type="button" class="d-flex align-items-center w-100 border-0 bg-transparent text-start p-3"
                                                wire:click="selectSuggestion('{{ $suggestion['id'] }}')" wire:loading.attr="disabled"
                                                style="cursor:pointer">
                                                <div class="me-3">
                                                    <div class="rounded-circle border" style="width:18px;height:18px;border-width:2px;"></div>
                                                </div>
                                
                                                <div class="flex-grow-1">
                                                    <div>{{ $suggestion['text'] }}</div>
                                                    
                                                    @php
                                                    $parts = explode('-', $suggestion['description'], 2);
                                                    @endphp
                                                    
                                                    <small class="d-flex align-items-center">
                                                        {{ trim($parts[0]) }}
                                                    
                                                        @if(isset($parts[1]))
                                                        <i class="bi bi-chevron-right mx-1"></i>
                                                        <strong class="fw-semibold">{{ trim($parts[1]) }}</strong>
                                                        @endif
                                                    </small>
                                                </div>
                                            </button>
                                        </li>
                                        @endforeach
                                    </ul>
                                    @else
                                    <div class="text-muted text-center mb-3">
                                        @if ($isLoading)
                                        <span></span>
                                        @else
                                        <div class="alert alert-info mb-3" style="text-align: justify">
                                            We couldn’t find any matching address suggestions.
                                            Please reset the form and try entering your address again.
                                        </div>
                                    
                                        <div>
                                            You can also verify your address here:
                                            <a href="https://www.canadapost-postescanada.ca/cpc/en/tools/find-a-postal-code.page" target="_blank"
                                                rel="noopener" style="cursor: pointer">
                                                <u>Canada Post Address Lookup</u>
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    @endif
                                
                                    {{-- Buttons --}}
                                    <div class="pb-3">
                                        <div class="d-flex justify-content-start">
                                            <a href="" class="btn btn-secondary" type="button" wire:loading.attr="disabled">Reset</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div> <!-- Modal end -->
               
            </div>

            @endif

            @if($actionVal == "list")
            <div class="card p-2 col-md-12">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Your Addresses</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse ($authAddresses as $item)
                        <div class="col-md-4 mb-3 col-sm-6" wire:key="address-{{ $item->id }}">
                            <div class="card shadow-sm">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <span class="fw-bold"> {{ ucfirst($item->type) }}</span>
                                    <div class="edit">
                                        {{-- <button wire:click="editAddress('{{ $item->id }}')"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fa fa-edit"></i>
                                        </button> --}}
                                        {{-- <button wire:click="removeAddress('{{ $item->id }}')"
                                            class="btn btn-sm btn-outline-danger">
                                            <i class="fa fa-trash"></i>
                                        </button> --}}
                                        <button wire:click="refreshShippingDistance('{{ $item->id }}')" wire:loading.attr="disabled"
                                            wire:target="refreshShippingDistance, removeAddress"
                                            class="btn btn-sm btn-outline-danger d-none"
                                            title="Refresh shipping distance">
                                            <span wire:loading.remove
                                                wire:target="refreshShippingDistance('{{ $item->id }}')">
                                                <i class="fa fa-exclamation-triangle"></i>
                                            </span>
                                            <span wire:loading wire:target="refreshShippingDistance('{{ $item->id }}')">
                                                <i class="fa fa-spinner fa-spin"></i>
                                            </span>
                                        </button>
                                        <button wire:click="removeAddress('{{ $item->id }}')" wire:loading.attr="disabled"
                                            wire:target="removeAddress, refreshShippingDistance"
                                            class="btn btn-sm btn-outline-danger"
                                            title="Remove address">
                                            <span wire:loading.remove
                                                wire:target="removeAddress('{{ $item->id }}')">
                                                <i class="fa fa-trash"></i>
                                            </span>
                                        
                                            <span wire:loading wire:target="removeAddress('{{ $item->id }}')">
                                                <i class="fa fa-spinner fa-spin"></i>
                                            </span>
                                        </button>

                                    </div>
                                </div>

                                <div class="card-body">
                                    {{-- <span wire:click="activeAddress('{{ $item->id }}')"
                                        class="badge {{ $item->active ? 'bg-success' : 'bg-danger' }} mb-3">
                                        {{ $item->active ? 'Active' : 'Inactive' }}
                                    </span> --}}
                                    <ul class="list-group">
                                        {{-- <li class="list-group-item"><strong>Name:</strong> {{ $item->name }}</li>
                                        <li class="list-group-item"><strong>Email:</strong> {{ $item->email ?? 'N/A'}}
                                        </li>
                                        <li class="list-group-item"><strong>Phone:</strong> {{ $item->phone ?? 'N/A'}}
                                        </li> --}}
                                        {{-- <li class="list-group-item"><strong>Apt / Suite / Floor:</strong> {{
                                            $item->house_no ?? ''}}</li> --}}
                                        <li class="list-group-item"><strong>Address Line:</strong> {{ $item->address }}</li>
                                        <li class="list-group-item"><strong>City:</strong> {{ $item->city ?? 'N/A'}}
                                        </li>
                                        <li class="list-group-item"><strong>Postal Code:</strong> {{ $item->postal_code
                                            ?? ''}}</li>
                                        {{-- <li class="list-group-item"><strong>Street:</strong> {{ $item->street ??
                                            'N/A'}}</li> --}}
                                        <li class="list-group-item"><strong>Province:</strong> Ontario</li>
                                        {{-- <li class="list-group-item"><strong>Description:</strong> {{
                                            $item->description ?? 'N/A'}}</li> --}}
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @empty
                        <p>Please add your shipping address</p>
                        @endforelse
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>