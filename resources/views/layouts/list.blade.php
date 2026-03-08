<div class="col-6 col-lg-4 col-xl-3">
    <div class="card h-100 border">
        <div class="position-relative">
            <img src="{{ $product->image ?: asset('/default-png-min.png') }}" class="card-img-top p-3"
                alt="{{ $product->name }}" loading="lazy" style="height: 200px; aspect-ratio: 5 / 6; object-fit: cover;">

            <div class="position-absolute top-0 start-0 end-0 m-2 d-flex align-items-center">
                @if (isset($product->feature) && $product->feature)
                    <span class="badge bg-warning text-dark me-auto d-flex align-items-center">
                        <i class="fas fa-star"></i>
                        <span class="d-none d-sm-inline ms-1">Featured</span>
                    </span>
                @endif

                @if (isset($product->stock) && $product->stock)
                <span
                    class="badge {{ $product->stock ? 'bg-success' : 'bg-danger' }} d-flex align-items-center text-nowrap">
                    <i class="fas {{ $product->stock ? 'fa-check' : 'fa-times' }}"></i>
                    <span class="d-none d-sm-inline ms-1">
                        {{ $product->stock ? 'In Stock' : 'Out of Stock' }}
                    </span>
                </span>
                @endif
            </div>

        </div>

        <div class="card-body d-flex flex-column p-2 pt-0">
            <a href="{{ route('product.detail', $product->slug) }}" class="text-decoration-none text-dark">
                <h6 class="card-title fw-semibold">
                    {{ Str::limit($product->name, 50) }}
                </h6>
                @if (isset($product->description) && $product->description)
                <p class="card-text text-muted small mt-2 mb-2 flex-grow-1">
                        {{ Str::limit($product->description, 70) }}
                    </p>
                @endif
            </a>

            <div class="border-top pt-2">
                <livewire:cart-setup :product="$product" :detail="false" />
            </div>
        </div>
    </div>
</div>
