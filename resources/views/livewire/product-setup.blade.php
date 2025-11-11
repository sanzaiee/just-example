<div>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Product/</span> {{ ($product) ? "Update" : "Create" }}</h4>
        <div class="row g-3">
            <div class="card p-2 col-md-12">

                 <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Product Setup</h5>
                    <small class="text-muted float-end">
                        <a href="{{ route('product.index') }}" class="btn btn-primary">
                            List
                        </a>
                    </small>
                </div>

                <form wire:submit="save">
                    <div class="card-body">
                        <div class="row g-3">

                            {{-- @include('backend.form.collection', [
                                'data' => [
                                    'name' => 'image',
                                    'label' => 'Please select your image',
                                ],
                                'required' => true,
                                'model' => $image ?? null,
                                'type' => 'file'
                            ]) --}}

                            @foreach ([
                                    ['name','Name'],
                                    ['code','Code'],
                                ] as $item)
                                @include('backend.form.livewire-collection', [
                                    'data' => [
                                        'name' => $item[0],
                                        'label' => $item[1],
                                    ],
                                    'required' => true,
                                    'model' => $model ?? null,
                                    'type' => 'text',
                                    'div' => 'col-md-4'
                                ])
                            @endforeach


                            @include('backend.form.livewire-collection', [
                                        'data' => [
                                            'name' =>'category_id',
                                            'label' => 'Category',
                                        ],
                                        'required' => true,
                                        'model' => $model ?? null,
                                        'type' => 'dynamic-select',
                                        'arrayData' => $categories,
                                        'div' => 'col-md-4'
                                    ])

                            @include('backend.form.livewire-collection', [
                                        'data' => [
                                            'name' =>'brand_id',
                                            'label' => 'Brand',
                                        ],
                                        'required' => true,
                                        'model' => $model ?? null,
                                        'type' => 'dynamic-select',
                                        'arrayData' => $brands,
                                        'div' => 'col-md-4'
                                    ])


                            @foreach ([
                                    ['price','Price'], ['strike_price','Strike Price'],
                                    // ['delivery_charges','Delivery Charges'],['tax','Tax']
                                ] as $item)
                                @include('backend.form.livewire-collection', [
                                    'data' => [
                                        'name' => $item[0],
                                        'label' => $item[1],
                                    ],
                                    'required' => true,
                                    'model' => $model ?? null,
                                    'type' => 'text',
                                    'div' => 'col-md-4'
                                ])
                            @endforeach

                            {{-- @foreach ([
                                ['video_url','Video Url'],
                                ] as $item)
                                @include('backend.form.livewire-collection', [
                                    'data' => [
                                        'name' => $item[0],
                                        'label' => $item[1],
                                    ],
                                    'required' => false,
                                    'model' => $model ?? null,
                                    'type' => 'text',
                                    'div' => 'col-md-4'

                                ])
                            @endforeach --}}

                            {{-- @foreach ([
                                ['status','Status'],['stock','Stock'],
                                ['on_sale','On Sale'],['home_delivery','Home  Delivery'],
                                ['best_rated','Best Rated'],['feature','Feature'],
                            ] as $item)
                                @include('backend.form.livewire-collection', [
                                    'data' => [
                                        'name' => $item[0],
                                        'label' => $item[1],
                                    ],
                                    'required' => false,
                                    'model' => $model ?? null,
                                    'type' => 'status',
                                    'div' => 'col-md-4'

                                ])
                            @endforeach --}}

                            @include('backend.form.livewire-collection', [
                                'data' => [
                                    'name' => 'tag',
                                    'label' => 'Tags',
                                ],
                                'required' => true,
                                'model' => $model ?? [],
                                'secondaryModel' => $tags ?? [],
                                'type' => 'normal-multiple-select',
                                'div' => 'col-md-4'
                            ])

                            <div class="col-md-4">
                                <label class="form-label" for="image">Upload Image</label>
                                <input type="file" class="form-control" wire:model.live="image">
                                @error('image')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                @if ($image || $preview_image)
                                    <div class="d-flex justify-content-between border border-shadow-2 p-1">
                                        @if ($image)
                                            <div class="p-1">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <img src="{{ $image->temporaryUrl() }}"width="60" height="60">
                                                    <span class="btn btn-xs btn-danger m-2" wire:click="clearImage">
                                                        X
                                                    </span>
                                                </div>
                                                <div class="text-center p-1">
                                                    <small>Temp Image</small>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($preview_image)
                                            <div class="p-1">
                                                <img src="{{ $preview_image }}" width="60" height="60">
                                                <div class="text-center"><small>Preview Image</small></div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <!-- Force next column to start on a new line -->
                            <div class="w-100 d-none d-md-block"></div>

                            @foreach ([['short','Short Description'],['description','Description']] as $item)
                                @include('backend.form.livewire-collection', [
                                    'data' => [
                                        'name' => $item[0],
                                        'label' => $item[1],
                                    ],
                                    'required' => false,
                                    'model' => $model ?? null,
                                    'type' => 'normal-textarea',
                                ])
                            @endforeach

                            {{-- @include('backend.form.livewire-collection', [
                                'data' => [
                                    'name' => 'related_product',
                                    'label' => 'Related Product',
                                ],
                                'required' => true,
                                'model' => $model ?? [],
                                'secondaryModel' => $related_products ?? [],
                                'type' => 'normal-multiple-select',
                                'div' => 'col-md-12'
                            ]) --}}
                        </div>
                    </div>

                    <div class="card-footer m-3">
                        <div class="d-flex justify-content-end gap-4">
                            <button class="btn btn-primary" type="submit" name="submit">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
