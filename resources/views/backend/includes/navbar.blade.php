<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="ti ti-menu-2 ti-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        {{-- <div class="navbar-nav align-items-center">
            <a class="nav-link style-switcher-toggle hide-arrow" href="javascript:void(0);">
                <i class="ti ti-sm"></i>
            </a>
        </div> --}}
        {{-- @inject('categories', 'App\Models\Category')
        @inject('brands', 'App\Models\Brand')
        @php
            $categories = $categories->pluck('name', 'slug');
            $brands = $brands->pluck('name', 'slug');
        @endphp --}}

        {{-- <div class="navbar-nav align-items-center"> --}}

        {{-- <form method="GET" action="{{ route('user.dashboard') }}" id="searchForm">
                <div class="row g-3">
                    <!-- Search Bar -->
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control" name="search" placeholder="Search products..."
                                value="{{ request()->get('search', '') }}" id="searchInput">
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div class="col-md-2">
                        <select class="form-select" name="category" id="categoryFilter">
                            <option value="">All Categories</option>
                            @foreach ($categories as $index => $cat)
                                <option value="{{ $index }}"
                                    {{ request()->get('category') == $index ? 'selected' : '' }}>
                                    {{ $cat }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Brand Filter -->
                    <div class="col-md-2">
                        <select class="form-select" name="brand" id="brandFilter">
                            <option value="">All Brands</option>
                            @foreach ($brands as $index => $brand)
                                <option value="{{ $index }}"
                                    {{ request()->get('brand') == $index ? 'selected' : '' }}>
                                    {{ $brand }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sort Filter -->
                    <div class="col-md-2">
                        <select class="form-select" name="sort" id="sortFilter">
                            <option value="latest" {{ request()->get('sort', 'latest') == 'latest' ? 'selected' : '' }}>
                                Latest First
                            </option>
                            <option value="oldest" {{ request()->get('sort') == 'oldest' ? 'selected' : '' }}>
                                Oldest First
                            </option>
                        </select>
                    </div>

                    <!-- Page Size Filter -->
                    <div class="col-md-1">
                        <select class="form-select" name="per_page" id="perPageFilter">
                            <option value="6" {{ request()->get('per_page') == 6 ? 'selected' : '' }}>6
                                per page</option>
                            <option value="12" {{ request()->get('per_page') == 12 ? 'selected' : '' }}>12
                                per page</option>
                            <option value="24" {{ request()->get('per_page') == 24 ? 'selected' : '' }}>24
                                per page</option>
                            <option value="48" {{ request()->get('per_page') == 48 ? 'selected' : '' }}>48
                                per page</option>
                        </select>
                    </div>
                    <!-- Search Button -->
                    <div class="col-md-2">
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
                                        {{ $allCategories->find(request()->get('category'))->name ?? '' }}
                                        <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}"
                                            class="text-decoration-none ms-1">×</a>
                                    </span>
                                @endif
                                @if (request()->get('brand'))
                                    <span class="badge bg-light text-dark me-2 mb-1">
                                        Brand:
                                        {{ $allBrands->find(request()->get('brand'))->name ?? '' }}
                                        <a href="{{ request()->fullUrlWithQuery(['brand' => null]) }}"
                                            class="text-decoration-none ms-1">×</a>
                                    </span>
                                @endif
                                <a href="{{ url('/') }}" class="btn btn-sm btn-outline-secondary ms-2">Clear
                                    all</a>
                            </div>
                        </div>
                    </div>
                @endif
            </form> --}}



        {{-- <form action="{{ route('user.dashboard') }}" method="get">
                <div class="d-flex justify-content-between align-center">

                    <div class="input-group me-2">
                        <span class="input-group-text bg-primary text-white">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" class="form-control" name="search" placeholder="Search products..."
                            value="{{ request()->get('search', '') }}" id="searchInput">
                    </div>

                    <select class="form-select me-2" name="category" id="categoryFilter">
                        <option value="">All Categories</option>
                        @foreach ($categories as $index => $cat)
                            <option value="{{ $index }}"
                                {{ request()->get('category') == $index ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                        @endforeach
                    </select>

                    <select class="form-select me-2" name="brand" id="brandFilter">
                        <option value="">All Brands</option>
                        @foreach ($brands as $index => $brand)
                            <option value="{{ $index }}"
                                {{ request()->get('brand') == $index ? 'selected' : '' }}>
                                {{ $brand }}
                            </option>
                        @endforeach
                    </select>

                    <button class="btn btn-primary me-2 btn-sm">
                        <i class="fas fa-filter me-2"></i>
                    </button>
                    <a href="{{ route('user.dashboard') }}" class="btn btn-danger btn-sm">
                        <i class="ti ti-refresh"></i>
                    </a>
                </div>
            </form> --}}
        {{-- </div> --}}



        <ul class="navbar-nav flex-row align-items-center ms-auto gap-1">
            <li class="nav-item">
                <livewire:cart-dropdown />
            </li>
            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <span class="avatar-initial rounded-circle bg-label-primary">
                            {{ auth()->user()->name[0] }}
                        </span>
                        {{-- <img src="{{ asset('') }}assets/img/avatars/1.png" alt class="h-auto rounded-circle" /> --}}
                    </div>
                </a>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <span class="avatar-initial rounded-circle bg-label-primary">
                                            {{ strtoupper(auth()->user()->name[0]) ?? '' }}
                                        </span>
                                        {{-- <img src="{{ asset('') }}assets/img/avatars/1.png" alt
                                            class="h-auto rounded-circle" /> --}}
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">{{ auth()->user()->name }}</span>
                                    <small class="text-muted">{{ auth()->user()->name }}</small>
                                </div>
                            </div>
                        </a>
                    </li>

                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    @can('admin-only')
                        <li>
                            <a class="dropdown-item" href="{{ route('site.view', 'general-information') }}">
                                <i class="ti ti-settings me-2 ti-sm"></i>
                                <span class="align-middle">Setting</span>
                            </a>
                        </li>
                    @endcan
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                            <i class="ti ti-logout me-2 ti-sm"></i>
                            <span class="align-middle">Log Out</span>
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>


    </div>
</nav>
