@extends('backend.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div
            class="d-flex justify-content-end align-items-center gap-2 flex-wrap flex-md-nowrap mt-3 mb-3 bg-light p-3 rounded-3">
            <select class="form-select form-select-sm shadow-sm border-0 bg-light w-1/2" onchange="updatePerPage(this.value)">
                <option value="10" @if (request()->get('per_page') == 10) selected @endif>10 per page</option>
                <option value="20" @if (request()->get('per_page') == 20) selected @endif>20 per page</option>
                <option value="30" @if (request()->get('per_page') == 30) selected @endif>30 per page</option>
                <option value="100" @if (request()->get('per_page') == 100) selected @endif>100 per page</option>
            </select>

            <select class="form-select form-select-sm shadow-sm border-0 bg-light w-1/2" onchange="updateSort(this.value)">
                <option value="asc" @if (request()->get('sort') == 'asc') selected @endif>Oldest</option>
                <option value="desc" @if (request()->get('sort') == 'desc') selected @endif>Newest</option>
                </option>
            </select>

            <select class="form-select form-select-sm shadow-sm border-0 bg-light w-1/2"
                onchange="updateStatus(this.value)">
                <option value="all" @if (request()->get('status') == 'all') selected @endif>All</option>
                <option value="best_rated" @if (request()->get('status') == 'best_rated') selected @endif>Best Rated</option>
                <option value="on_sale" @if (request()->get('status') == 'on_sale') selected @endif>On Sale</option>
                </option>
            </select>

            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-primary w-1/2">
                <i class="bi bi-arrow-repeat"></i>

            </a>
        </div>

        <!-- Products Grid -->
        <div class="row g-4">
            @foreach ($products as $item)
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="card h-100 shadow-sm border-0 product-card hover-lift transition-all duration-300">
                        <!-- Stock Badge - Fixed Positioning -->
                        <div class="position-absolute top-0 end-0 m-2 z-3" style="z-index: 10;">
                            <span
                                class="badge {{ $item->stock ? 'bg-success' : 'bg-danger' }} rounded-pill px-3 py-2 shadow-sm">
                                <i class="bi {{ $item->stock ? 'bi-check-circle' : 'bi-x-circle' }} me-1"></i>
                                {{ $item->stock ? 'In Stock' : 'Out of Stock' }}
                            </span>
                        </div>

                        <!-- Product Image -->
                        <div class="position-relative overflow-hidden product-image-container">
                            <div class="image-placeholder bg-light d-flex align-items-center justify-content-center"
                                style="height: 250px;">
                                <img src="{{ $item->image }}" alt="{{ $item->slug }}"
                                    class="img-fluid product-image transition-transform duration-300"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="text-muted text-center" style="display: none;">
                                    <i class="bi bi-image display-1"></i>
                                    <p class="mb-0 mt-2">No Image</p>
                                </div>
                            </div>
                        </div>

                        <!-- Product Details -->
                        <div class="card-body d-flex flex-column">
                            <div class="flex-grow-1">
                                <a href="{{ route('product.show', $item->slug) }}" class="text-decoration-none text-dark">
                                    <h5 class="card-title fw-semibold mb-2 text-truncate">
                                        {{ $item->name }}
                                    </h5>
                                    <p class="text-muted small mb-3 line-clamp-2">
                                        {{ $item->short }}
                                    </p>
                                </a>
                            </div>

                            <!-- Action Section -->
                            <div class="border-top pt-3">
                                <livewire:cart-setup :product="$item" :detail="false" />
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="mt-4 ">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        </div>

        <!-- Empty State -->
        @if ($products->isEmpty())
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-inbox display-1 text-muted"></i>
                </div>
                <h4 class="text-muted mb-2">No Products Available</h4>
                <p class="text-muted">Check back later for new arrivals</p>
            </div>
        @endif
    </div>

    <!-- Custom Styles -->
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
        }

        .product-image-container:hover .product-image {
            transform: scale(1.05);
        }

        .product-image-container:hover .hover-overlay {
            background-color: rgba(0, 0, 0, 0.1) !important;
        }

        .product-image-container:hover .hover-btn {
            opacity: 1 !important;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .hover-lift:hover {
            transform: translateY(-8px);
        }

        /* Animation Classes */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate__fadeInDown {
            animation: fadeInDown 0.6s ease-out;
        }

        .animate__fadeInUp {
            animation: fadeInUp 0.6s ease-out 0.2s both;
        }
    </style>
@endsection
<script>
    updatePerPage = (value) => {
        window.location.href = `?per_page=${value}`;
    };

    updateSort = (value) => {
        window.location.href = `?sort=${value}`;
    };

    updateStatus = (value) => {
        if (value == 'all') {
            window.location.href = `?status=feature`;
        } else {
            window.location.href = `?status=${value}`;
        }
    };
</script>
