<div>
    @push('css')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="anonymous" />
    @endpush
    @push('custom-scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin="anonymous"></script>
        <script src="{{ asset('js/address-map-picker.js') }}"></script>
    @endpush
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="py-2"><span class="text-muted fw-light">Shipping Address /</span>
            @if ($actionVal == 'list')
                List
            @elseif($actionVal == 'add')
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
            @if ($actionVal == 'add')
                <div class="card p-2 col-md-12">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Shipping Address</h5>

                    </div>

                    <form wire:submit="save">
                        <div class="card-body">
                            <div class="row g-3">
                                @foreach ([['type', 'Save Address As']] as $item)
                                    @include('backend.form.livewire-collection', [
                                        'data' => [
                                            'name' => $item[0],
                                            'label' => $item[1],
                                        ],
                                        'required' => true,
                                        'model' => null,
                                        'type' => 'text',
                                        'div' => 'col-md-4',
                                    ])
                                @endforeach
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row g-3">
                                {{-- @foreach ([['type', 'Address Type'], ['name', 'Name'], ['email', 'Email'], ['phone', 'Tel Phone'], ['address', 'Address']] as $item)
                                    @include('backend.form.livewire-collection', [
                                        'data' => [
                                            'name' => $item[0],
                                            'label' => $item[1],
                                        ],
                                        'required' => true,
                                        'model' => null,
                                        'type' => 'text',
                                        'div' => 'col-md-4'
                                    ])
                                @endforeach --}}

                                @foreach ([['house_no', 'Apt / Suite / Floor / Unit']] as $item)
                                    @include('backend.form.livewire-collection', [
                                        'data' => [
                                            'name' => $item[0],
                                            'label' => $item[1],
                                            'placeholder' => 'E.g. apt 101, suite 202',
                                        ],
                                        'required' => false,
                                        'model' => null,
                                        'type' => 'text',
                                        'div' => 'col-md-4',
                                    ])
                                @endforeach

                                {{-- Address: select from map or type --}}
                                <div class="col-12">
                                    <label class="form-label">Address (search or click on map)</label>
                                    <div class="input-group mb-2">
                                        <input type="text" id="address-map-search" class="form-control"
                                            placeholder="Search address (e.g. street, city, postal code)..."
                                            autocomplete="off">
                                        <button type="button" class="btn btn-outline-primary"
                                            data-address-map-search-btn>
                                            Search
                                        </button>
                                    </div>
                                    <div id="address-map"
                                        data-address-map-config="{{ e(json_encode($this->addressMapConfig)) }}"
                                        style="height: 320px; width: 100%; border-radius: 0.375rem; border: 1px solid var(--bs-border-color);"
                                        class="mb-2"></div>
                                    <input type="text" wire:model.live="address" id="address" class="form-control"
                                        placeholder="Selected address (or type manually)"
                                        value="{{ old('address', $address ?? '') }}">
                                    @error('address')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                @foreach ([['city', 'City'], ['postal_code', 'Postal Code']] as $item)
                                    @include('backend.form.livewire-collection', [
                                        'data' => [
                                            'name' => $item[0],
                                            'label' => $item[1],
                                        ],
                                        'required' => true,
                                        'model' => null,
                                        'type' => 'text',
                                        'div' => 'col-md-4',
                                    ])
                                @endforeach

                                <div class="col-md-4">
                                    <label for="province" class="form-label">Province</label>
                                    <input type="text" id="city" class="form-control" value="Ontario" readonly
                                        style="background-color: lightgray">
                                </div>
                            </div>
                        </div>

                        <div class="card-footer m-3">
                            <div class="d-flex justify-content-end gap-4">
                                <button class="btn btn-primary" type="submit" name="submit">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            @endif

            @if ($actionVal == 'list')
                <div class="card p-2 col-md-12">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Your Addresses</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @forelse ($authAddresses as $item)
                                <div class="col-md-4 mb-3 col-sm-6">
                                    <div class="card shadow-sm">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <span class="fw-bold"> {{ ucfirst($item->type) }}</span>
                                            <div class="edit">
                                                <button wire:click="editAddress('{{ $item->id }}')"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                {{-- <button wire:click="removeAddress('{{ $item->id }}')" class="btn btn-sm btn-outline-danger">
                                                    <i class="fa fa-trash"></i>
                                                </button> --}}
                                                <button wire:click="removeAddress('{{ $item->id }}')"
                                                    wire:loading.attr="disabled"
                                                    wire:target="removeAddress('{{ $item->id }}')"
                                                    class="btn btn-sm btn-outline-danger">
                                                    <span wire:loading.remove
                                                        wire:target="removeAddress('{{ $item->id }}')">
                                                        <i class="fa fa-trash"></i>
                                                    </span>

                                                    <span wire:loading
                                                        wire:target="removeAddress('{{ $item->id }}')">
                                                        <i class="fa fa-spinner fa-spin"></i>
                                                    </span>
                                                </button>

                                            </div>
                                        </div>

                                        <div class="card-body">
                                            <span wire:click="activeAddress('{{ $item->id }}')"
                                                class="badge {{ $item->active ? 'bg-success' : 'bg-danger' }} mb-3">
                                                {{ $item->active ? 'Active' : 'Inactive' }}
                                            </span>
                                            <ul class="list-group">
                                                {{-- <li class="list-group-item"><strong>Name:</strong> {{ $item->name }}</li>
                                                <li class="list-group-item"><strong>Email:</strong> {{ $item->email ?? 'N/A'}}</li>
                                                <li class="list-group-item"><strong>Phone:</strong> {{ $item->phone ?? 'N/A'}}</li> --}}
                                                <li class="list-group-item"><strong>Apt / Suite / Floor:</strong>
                                                    {{ $item->house_no ?? '' }}</li>
                                                <li class="list-group-item"><strong>Address:</strong>
                                                    {{ $item->address }}</li>
                                                <li class="list-group-item"><strong>City:</strong>
                                                    {{ $item->city ?? 'N/A' }}</li>
                                                <li class="list-group-item"><strong>Postal Code:</strong>
                                                    {{ $item->postal_code ?? '' }}</li>
                                                {{-- <li class="list-group-item"><strong>Street:</strong> {{ $item->street ?? 'N/A'}}</li> --}}
                                                <li class="list-group-item"><strong>Province:</strong> Ontario</li>
                                                {{-- <li class="list-group-item"><strong>Description:</strong> {{ $item->description ?? 'N/A'}}</li> --}}
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
