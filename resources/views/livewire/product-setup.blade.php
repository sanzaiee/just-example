<div>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Product/</span> {{ $product ? 'Update' : 'Create' }}
        </h4>
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

                            @foreach ([['name', 'Name'], ['code', 'Code']] as $item)
                                @include('backend.form.livewire-collection', [
                                    'data' => [
                                        'name' => $item[0],
                                        'label' => $item[1],
                                    ],
                                    'required' => true,
                                    'model' => $model ?? null,
                                    'type' => 'text',
                                    'div' => 'col-md-4',
                                ])
                            @endforeach


                            @include('backend.form.livewire-collection', [
                                'data' => [
                                    'name' => 'category_id',
                                    'label' => 'Category',
                                ],
                                'required' => true,
                                'model' => $model ?? null,
                                'type' => 'dynamic-select',
                                'arrayData' => $categories,
                                'div' => 'col-md-4',
                            ])

                            @include('backend.form.livewire-collection', [
                                'data' => [
                                    'name' => 'brand_id',
                                    'label' => 'Brand',
                                ],
                                'required' => true,
                                'model' => $model ?? null,
                                'type' => 'dynamic-select',
                                'arrayData' => $brands,
                                'div' => 'col-md-4',
                            ])


                            @foreach ([
                                    //['price', 'Price'],
                                    ['strike_price', 'Strike Price'],
                                    // ['delivery_charges','Delivery Charges'],['tax','Tax']
                                ] as $item)
                                @include('backend.form.livewire-collection', [
                                    'data' => [
                                        'name' => $item[0],
                                        'label' => $item[1],
                                    ],
                                    'required' => true,
                                    'model' => $model ?? null,
                                    'type' => 'number',
                                    'div' => 'col-md-4',
                                ])
                            @endforeach

                            @foreach ([
                                ['status', 'Status'], 
                                ] as $item)
                                @include('backend.form.livewire-collection', [
                                    'data' => [
                                        'name' => $item[0],
                                        'label' => $item[1],
                                    ],
                                    'required' => false,
                                    'model' => $model ?? null,
                                    'type' => 'status',
                                    'div' => 'col-md-4',
                                ])
                            @endforeach

                            <!-- Tiered Pricing Section -->
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">Tiered Pricing</h6>
                                        <button type="button" class="btn btn-primary" wire:click="addTier">
                                            <i class="fa fa-plus me-1"></i>Add Tier
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <p class="text-muted small">Set different prices for different quantities. The
                                            system will automatically apply the appropriate price based on the quantity
                                            ordered.</p>

                                        @foreach ($tiered_prices as $index => $tier)
                                            <div class="row g-2 mb-2" wire:key="tier-{{ $index }}">
                                                <div class="col-md-5">
                                                    <label class="form-label" for="min-qty-{{ $index }}">Minimum Quantity</label>
                                                    <input type="number" id="min-qty-{{ $index }}" class="form-control"
                                                        wire:model.live="tiered_prices.{{ $index }}.quantity"
                                                        placeholder="e.g., 1, 10, 50" min="1">
                                                </div>
                                                <div class="col-md-5">
                                                    <label class="form-label" for="price-{{ $index }}">Price</label>
                                                    <input type="number" id="price-{{ $index }}" class="form-control"
                                                        wire:model.live="tiered_prices.{{ $index }}.price"
                                                        placeholder="Price for this quantity" step="0.01"
                                                        min="0">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label" for="trash-btn-{{ $index }}">&nbsp;</label><br>
                                                    <button type="button" class="btn btn-danger" id="trash-btn-{{ $index }}"
                                                        wire:click="removeTier({{ $index }})">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach

                                        @if (count($tiered_prices) > 1)
                                            <div class="alert alert-info small mt-2">
                                                <i class="fa fa-info-circle"></i>
                                                Example: If you set quantity 1 = $10, quantity 10 = $8, and quantity 50
                                                = $6,
                                                then orders of 1-9 items will be $10 each, 10-49 items will be $8 each,
                                                and 50+ items will be $6 each.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- @foreach ([['video_url', 'Video Url']] as $item)
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

                            @foreach ([
                                // ['status', 'Status'], 
                                ['stock', 'Stock'], 
                                ['on_sale', 'On Sale'], 
                                // ['home_delivery', 'Home  Delivery'], 
                                ['best_rated', 'Best Rated'], 
                                // ['feature', 'Feature']
                                ] as $item)
                                @include('backend.form.livewire-collection', [
                                    'data' => [
                                        'name' => $item[0],
                                        'label' => $item[1],
                                    ],
                                    'required' => false,
                                    'model' => $model ?? null,
                                    'type' => 'status',
                                    'div' => 'col-md-4',
                                ])
                            @endforeach

                            {{-- @include('backend.form.livewire-collection', [
                                'data' => [
                                    'name' => 'tag',
                                    'label' => 'Tags',
                                ],
                                'required' => true,
                                'model' => $model ?? [],
                                'secondaryModel' => $tags ?? [],
                                'type' => 'normal-multiple-select',
                                'div' => 'col-md-4',
                            ]) --}}

                            <!-- Multiple Image Upload Section -->
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">Product Images</h6>
                                        <small class="text-muted">Upload up to 5 images (PNG, JPG, JPEG, GIF - Max 2MB
                                            each)</small>
                                    </div>
                                    <div class="card-body">
                                        <!-- Image Upload Input -->
                                        <div class="mb-3">
                                            <label class="form-label" for="images">Upload Images</label>
                                            <input type="file" class="form-control" wire:model.live="images" multiple
                                                accept="image/png,image/jpeg,image/jpg,image/gif">
                                            @error('images')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                            @error('images.*')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Preview Images (Newly Uploaded) -->
                                        @if (!empty($preview_images))
                                            <div class="mb-3">
                                                <h6 class="text-muted">New Images (Preview)</h6>
                                                <div class="row g-2">
                                                    @foreach ($preview_images as $index => $image)
                                                        <div class="col-md-3 col-sm-4 col-6">
                                                            <div class="card border position-relative">
                                                                <img src="{{ $image['url'] }}" class="card-img-top"
                                                                    style="height: 120px; object-fit: cover;"
                                                                    alt="{{ $image['name'] }}">
                                                                <div class="card-body p-2">
                                                                    <small class="d-block text-truncate"
                                                                        title="{{ $image['name'] }}">
                                                                        {{ $image['name'] }}
                                                                    </small>
                                                                    <small class="text-muted">
                                                                        {{ number_format($image['size'] / 1024, 2) }}
                                                                        KB
                                                                    </small>
                                                                </div>
                                                                <button type="button"
                                                                    class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1"
                                                                    wire:click="removePreviewImage({{ $index }})"
                                                                    title="Remove image">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Existing Images -->
                                        @if (!empty($existing_images))
                                            <div class="mb-3">
                                                <h6 class="text-muted">Existing Images</h6>
                                                <div class="row g-2">
                                                    @foreach ($existing_images as $index => $image)
                                                        <div class="col-md-3 col-sm-4 col-6">
                                                            <div class="card border position-relative">
                                                                <img src="{{ $image['url'] }}" class="card-img-top"
                                                                    style="height: 120px; object-fit: cover;"
                                                                    alt="{{ $image['name'] }}">
                                                                <div class="card-body p-2">
                                                                    <small class="d-block text-truncate"
                                                                        title="{{ $image['name'] }}">
                                                                        {{ $image['name'] }}
                                                                    </small>
                                                                </div>
                                                                <button type="button"
                                                                    class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1"
                                                                    wire:click="removeExistingImage({{ $index }})"
                                                                    title="Delete image">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif


                                        <!-- Image Upload Input -->
                                        <div class="mb-3">
                                            <label class="form-label" for="image">Feature Image</label>
                                            <input type="file" class="form-control" wire:model.live="image"
                                                accept="image/png,image/jpeg,image/jpg,image/gif">
                                            @error('image')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Legacy Single Image (for backward compatibility) -->
                                        @if (!empty($image) && !is_array($image))
                                            <div class="mb-3">
                                                <h6 class="text-muted">Legacy Single Image</h6>
                                                <div class="d-flex justify-content-between border border-shadow-2 p-1">
                                                    <div class="p-1">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <img src="{{ $image->temporaryUrl() }}" width="60"
                                                                height="60">
                                                            <span class="btn btn-xs btn-danger m-2"
                                                                wire:click="clearImage">
                                                                X
                                                            </span>
                                                        </div>
                                                        <div class="text-center p-1">
                                                            <small>Temp Image</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($preview_image)
                                            <div class="mb-3">
                                                <h6 class="text-muted">Preview Image</h6>
                                                <div class="p-1">
                                                    <img src="{{ $preview_image }}" width="60" height="60">
                                                    <div class="text-center"><small>Preview Image</small></div>
                                                </div>
                                            </div>
                                        @endif

                                        @if (empty($preview_images) && empty($existing_images) && (empty($image) || is_array($image)) && !$preview_image)
                                            <div class="text-center text-muted py-3">
                                                <i class="bx bx-image-alt" style="font-size: 3rem;"></i>
                                                <p class="mb-0">No images uploaded yet</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Force next column to start on a new line -->
                            <div class="w-100 d-none d-md-block"></div>

                            @foreach ([['short', 'Short Description'], ['description', 'Description']] as $item)
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
