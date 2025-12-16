{{-- <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-6 mb-4">
    <div class="card h-100 border">
        <div class="position-relative">
            <img src="{{ $product->image ?: asset('/default-png-min.png') }}" class="card-img-top p-3"
                alt="{{ $product->name }}" loading="lazy" style="height: 200px; object-fit: contain;">

            <div
                class="position-absolute top-0 start-0 m-2 d-flex gap-2 align-items-center justify-content-between w-100 px-2 py-1">
                @if ($product->feature)
                    <span class="badge bg-warning text-dark">
                        <i class="fas fa-star me-1"></i>Featured
                    </span>
                @endif
                <span class="badge {{ $product->stock ? 'bg-success' : 'bg-warning' }}">
                    {{ $product->stock ? 'In Stock' : 'Out of Stock' }}
                </span>
            </div>
        </div>

        <div class="card-body d-flex flex-column p-3 pt-0">
            <a href="{{ route('product.detail', $product->slug) }}">
                <h6 class="card-title fw-semibold hover:text-primary hover:underline transition-all duration-300    ">
                    {{ Str::limit($product->name, 50) }}
                </h6>
                <p class="card-text text-muted small mt-2 mb-3 flex-grow-1">
                    {{ Str::limit($product->description, 70) }}
                </p>
            </a>

            <div class="border-top pt-3">
                <livewire:cart-setup :product="$product" :detail="false" />
            </div>
            <div class="mt-auto">
                <a href="{{ route('product.detail', $product->slug) }}"
                    class="btn btn-outline-primary w-100">
                    View Details
                </a>
            </div>
        </div>
    </div>
</div> --}}


<div class="col-6 col-lg-4 col-xl-3 mb-4">
    <div class="card h-100 border">
        <div class="position-relative">
            <img src="{{ $product->image ?: asset('/default-png-min.png') }}" class="card-img-top p-3"
                alt="{{ $product->name }}" loading="lazy" style="height: 200px; object-fit: contain;">

            <div class="position-absolute top-0 start-0 end-0 m-2 d-flex align-items-center">
                @if ($product->feature)
                    <span class="badge bg-warning text-dark me-auto d-flex align-items-center">
                        <i class="fas fa-star"></i>
                        <span class="d-none d-sm-inline ms-1">Featured</span>
                    </span>
                @endif

                <span
                    class="badge {{ $product->stock ? 'bg-success' : 'bg-danger' }} d-flex align-items-center text-nowrap">
                    <i class="fas {{ $product->stock ? 'fa-check' : 'fa-times' }}"></i>
                    <span class="d-none d-sm-inline ms-1">
                        {{ $product->stock ? 'In Stock' : 'Out of Stock' }}
                    </span>
                </span>
            </div>

        </div>

        <div class="card-body d-flex flex-column p-3 pt-0">
            <a href="{{ route('product.detail', $product->slug) }}" class="text-decoration-none text-dark">
                <h6 class="card-title fw-semibold">
                    {{ Str::limit($product->name, 50) }}
                </h6>
                <p class="card-text text-muted small mt-2 mb-3 flex-grow-1">
                    {{ Str::limit($product->description, 70) }}
                </p>
            </a>

            <div class="border-top pt-3">
                <livewire:cart-setup :product="$product" :detail="false" />
            </div>
        </div>
    </div>
</div>
