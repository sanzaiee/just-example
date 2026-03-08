@extends('backend.master')
@section('content')
    {{-- <div class="container-xxl flex-grow-1 container-p-y"> --}}
    <div class="container-xxl flex-grow-1">
        <!-- Header Section -->
        {{-- <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h1 class="fw-bold mb-2">Welcome</h1>
                                <p class="mb-0">Discover amazing products from our curated collection</p>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-md-end mt-3 mt-md-0">
                                    <div class="badge bg-primary  p-2 me-2">
                                        <i class="fas fa-box me-1"></i>
                                        <span id="totalProducts">{{ $products->total() }}</span>
                                        Products
                                    </div>
                                    <div class="badge bg-primary p-2">
                                        <i class="fas fa-tags me-1"></i>
                                        <span>{{ $allCategories->count() }}</span> Categories
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        <!-- Search and Filter Section -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('user.dashboard') }}" id="searchForm">
                            <div class="row g-3">
                                <!-- Search Bar -->
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text bg-primary text-white">
                                            <i class="fas fa-search"></i>
                                        </span>
                                        <input type="text" class="form-control" name="search"
                                            placeholder="Search products..." value="{{ request()->get('search', '') }}"
                                            id="searchInput">
                                    </div>
                                </div>

                                <!-- Category Filter -->
                                <div class="col-md-3">
                                    <select class="form-select" name="category" id="categoryFilter">
                                        <option value="">All Categories</option>
                                        @foreach ($allCategories as $cat)
                                            <option value="{{ $cat->slug }}"
                                                {{ request()->get('category') == $cat->slug ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Brand Filter -->
                                <div class="col-md-3">
                                    <select class="form-select" name="brand" id="brandFilter">
                                        <option value="">All Brands</option>
                                        @foreach ($allBrands as $brand)
                                            <option value="{{ $brand->slug }}"
                                                {{ request()->get('brand') == $brand->slug ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Sort Filter -->
                                <div style="display: none !important;" class="col-md-2">
                                    <select class="form-select" name="sort" id="sortFilter">
                                        <option value="latest"
                                            {{ request()->get('sort', 'latest') == 'latest' ? 'selected' : '' }}>
                                            Latest First
                                        </option>
                                        <option value="oldest" {{ request()->get('sort') == 'oldest' ? 'selected' : '' }}>
                                            Oldest First
                                        </option>
                                    </select>
                                </div>

                                <!-- Page Size Filter -->
                                <div style="display: none !important;" class="col-md-1">
                                    <select class="form-select" name="per_page" id="perPageFilter">
                                        <option value="8" {{ request()->get('per_page') == 8 ? 'selected' : '' }}>8
                                            per page</option>
                                        <option value="16" {{ request()->get('per_page') == 16 ? 'selected' : '' }}>16
                                            per page</option>
                                        <option value="24" {{ request()->get('per_page') == 24 ? 'selected' : '' }}>24
                                            per page</option>
                                    </select>
                                </div>
                                <!-- Search Button -->
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-filter me-2"></i>Apply Filters
                                    </button>
                                </div>
                            </div>

                            <!-- Active Filters Display -->
                            @if (request()->hasAny(['search', 'category', 'brand', 'sort']))
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="d-flex align-items-center flex-wrap">
                                            <span class="text-muted me-2">Active filters:</span>
                                            @if (request()->get('search'))
                                                <span class="badge bg-light text-dark me-2 mb-1">
                                                    Search: {{ request()->get('search') }}
                                                    <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}"
                                                        class="text-decoration-none ms-1">×</a>
                                                </span>
                                            @endif
                                            @if (request()->get('category'))
                                                <span class="badge bg-light text-dark me-2 mb-1">
                                                    Category:
                                                    {{ $allCategories->firstWhere('slug', request()->get('category'))->name ?? '' }}
                                                    <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}"
                                                        class="text-decoration-none ms-1">×</a>
                                                </span>
                                            @endif
                                            @if (request()->get('brand'))
                                                <span class="badge bg-light text-dark me-2 mb-1">
                                                    Brand: {{ $allBrands->firstWhere('slug', request()->get('brand'))->name ?? '' }}
                                                    <a href="{{ request()->fullUrlWithQuery(['brand' => null]) }}"
                                                        class="text-decoration-none ms-1">×</a>
                                                </span>
                                            @endif
                                            <span class="mt-1">
                                                <a href="{{ route('user.dashboard') }}" class="btn btn-sm btn-outline-secondary m-0">Clear all</a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Best Sellers Section -->
        @if (!empty($bestSellers))
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body py-2.5">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 text-dark">
                                    <i class="fas fa-crown text-success me-2"></i>Best Sellers
                                </h5>
                                <a style="display: none !important;" href="{{ route('user.dashboard', ['status' => 'best_rated']) }}"
                                    class="btn btn-primary btn-sm">View All</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="margin-top: 1rem;">
                        <div class="row g-3" id="bestSellerProducts">
                            @foreach ($bestSellers as $product)
                            @include('layouts.list', ['product' => $product])
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- On Sale Products Section -->
        @if (!empty($onSaleProducts))
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body py-2.5">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 text-dark">
                                    <i class="fas fa-tags text-info me-2"></i>On Sale Products
                                </h5>
                                <a style="display: none !important;" href="{{ route('user.dashboard', ['status' => 'on_sale']) }}"
                                    class="btn btn-primary btn-sm">View All</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="margin-top: 1rem;">
                        <div class="row g-3" id="onSaleProducts">
                            @foreach ($onSaleProducts as $product)
                            @include('layouts.list', ['product' => $product])
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- All Products -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body py-2.5">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-dark">
                                <i class="fas fa-box text-primary me-2"></i>All Products
                                <span class="badge badge-primary ms-2">{{ $products->total() }} products</span>
                            </h5>
                            <a style="display: none !important" href="{{ route('user.dashboard', ['status' => 'all']) }}"
                                class="btn btn-primary btn-sm">View All</a>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="margin-top: 1rem;">
                    <div class="row g-3">
                        @forelse ($products as $product)
                            @include('layouts.list', ['product' => $product])
                        @empty 

                        <div class="col">
                            <div class="card h-100 border">
                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title fw-semibold mb-0">
                                        <p class="text-muted mb-0">No products found.</p>
                                    </h6>
                                </div>
                            </div>
                        </div>


                        @endforelse
                    </div>
                    <!-- Pagination -->
                    @if ($products->hasPages())
                    <div class=" text-center mt-2">
                        {{ $products->links('pagination::bootstrap-5') }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @push('custom-scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Auto-submit form on filter change
                const searchForm = document.getElementById('searchForm');
                const filterElements = ['categoryFilter', 'brandFilter', 'sortFilter'];

                filterElements.forEach(id => {
                    const element = document.getElementById(id);
                    if (element) {
                        element.addEventListener('change', function() {
                            searchForm.submit();
                        });
                    }
                });

                // Debounced search input
                let searchTimeout;
                const searchInput = document.getElementById('searchInput');
                if (searchInput) {
                    searchInput.addEventListener('input', function() {
                        clearTimeout(searchTimeout);
                        searchTimeout = setTimeout(() => {
                            searchForm.submit();
                        }, 500);
                    });
                }

                // Toggle category visibility
                document.querySelectorAll('.toggle-category').forEach(button => {
                    button.addEventListener('click', function() {
                        const categorySlug = this.dataset.category;
                        const categoryDiv = document.getElementById('category-' + categorySlug);
                        const icon = this.querySelector('i');

                        if (categoryDiv.style.display === 'none') {
                            categoryDiv.style.display = 'flex';
                            icon.classList.remove('fa-chevron-right');
                            icon.classList.add('fa-chevron-down');
                            this.classList.remove('collapsed');
                        } else {
                            categoryDiv.style.display = 'none';
                            icon.classList.remove('fa-chevron-down');
                            icon.classList.add('fa-chevron-right');
                            this.classList.add('collapsed');
                        }
                    });
                });

                // Lazy loading for images
                if ('IntersectionObserver' in window) {
                    const imageObserver = new IntersectionObserver((entries, observer) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                const img = entry.target;
                                img.src = img.dataset.src || img.src;
                                img.classList.remove('lazy');
                                imageObserver.unobserve(img);
                            }
                        });
                    });

                    document.querySelectorAll('img[loading="lazy"]').forEach(img => {
                        imageObserver.observe(img);
                    });
                }
            });
        </script>
    @endpush
@endsection
