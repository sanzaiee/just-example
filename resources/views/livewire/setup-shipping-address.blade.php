<div>
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="py-3 mb-4"><span class="text-muted fw-light">Shipping Address/</span> {{ ($shippingAddress) ? "Update" : "Create" }}
            <div class="text-muted float-end">
                <span wire:click="action('add')" class="btn btn-sm btn-primary">
                    Add
                </span>
                <span wire:click="action('list')" class="btn btn-sm btn-primary">
                    List
                </span>
            </div>
        </div>

        <div class="row g-3">
            @if($actionVal == "add")
                <div class="card p-2 col-md-12">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Shipping Address</h5>

                    </div>

                    <form wire:submit="save">
                        <div class="card-body">
                            <div class="row g-3">
                                @foreach ([
                                        ['type','Address Type'],
                                        ['name','Name'],
                                        ['email','Email'],
                                        ['phone','Tel Phone'],
                                        ['address','Address'],
                                    ] as $item)
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
                                @endforeach

                                @foreach ([
                                        ['city','City'],
                                        ['street','Street'],
                                        ['house_no','Apartment Number / Unit'],
                                        ['description','Description'],
                                    ] as $item)
                                    @include('backend.form.livewire-collection', [
                                        'data' => [
                                            'name' => $item[0],
                                            'label' => $item[1],
                                        ],
                                        'required' => false,
                                        'model' => null,
                                        'type' => 'text',
                                        'div' => 'col-md-4'
                                    ])
                                @endforeach
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

            @if($actionVal == "list")
                <div class="card p-2 col-md-12">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Your Addresses</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @forelse ($authAddresses as $item)
                               <div class="col-md-4 mb-3">
                                    <div class="card shadow-sm">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <span class="fw-bold"> {{ ucfirst($item->type) }}</span>
                                             <div class="edit">
                                                <button wire:click="editAddress('{{ $item->id }}')" class="btn btn-sm btn-outline-primary">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button wire:click="removeAddress('{{ $item->id }}')" class="btn btn-sm btn-outline-danger">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="card-body">
                                            <span wire:click="activeAddress('{{ $item->id }}')"
                                                class="badge {{ $item->active ? 'bg-success' : 'bg-danger' }} mb-3">
                                                {{ $item->active ? 'Active' : 'Inactive' }}
                                            </span>
                                            <ul class="list-group">
                                                                                                <li class="list-group-item"><strong>Name:</strong> {{ $item->name }}</li>
                                                <li class="list-group-item"><strong>Email:</strong> {{ $item->email ?? 'N/A'}}</li>
                                                <li class="list-group-item"><strong>Phone:</strong> {{ $item->phone ?? 'N/A'}}</li>
                                                <li class="list-group-item"><strong>Address:</strong> {{ $item->address }}</li>
                                                <li class="list-group-item"><strong>City:</strong> {{ $item->city ?? 'N/A'}}</li>
                                                <li class="list-group-item"><strong>Street:</strong> {{ $item->street ?? 'N/A'}}</li>
                                                <li class="list-group-item"><strong>House No:</strong> {{ $item->house_no ?? 'N/A'}}</li>
                                                <li class="list-group-item"><strong>Description:</strong> {{ $item->description ?? 'N/A'}}</li>
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
