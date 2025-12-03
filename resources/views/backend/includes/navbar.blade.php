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
        @inject('categories', 'App\Models\Category')
        @inject('brands', 'App\Models\Brand')
        @php
            $categories = $categories->pluck('name', 'slug');
            $brands = $brands->pluck('name', 'slug');
        @endphp

        <div class="navbar-nav align-items-center">
            <form action="{{ route('admin.dashboard') }}" method="get">
                <div class="d-flex justify-content-between align-center">
                    <select class="form-control me-2" name="category" value="{{ request('category') }}">
                        <option value="">-- select category --</option>
                        @foreach ($categories as $index => $item)
                            <option value="{{ $index }}" @if ($index == request('category')) selected @endif>
                                {{ $item }}</option>
                        @endforeach
                    </select>

                    <select class="form-control me-2" name="brand" value="{{ request('brand') }}">
                        <option value="">-- select brand --</option>
                        @foreach ($brands as $index => $item)
                            <option value="{{ $index }}" @if ($index == request('brand')) selected @endif>
                                {{ $item }}</option>
                        @endforeach
                    </select>


                    <input type="text" name="query" value="{{ request('query') }}" class="form-control me-3"
                        placeholder="Search..">
                    <button class="btn btn-primary me-2 btn-sm">
                        <i class="ti ti-search"></i>
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-danger btn-sm">
                        <i class="ti ti-x"></i>
                    </a>
                </div>
            </form>
        </div>


        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ asset('') }}assets/img/avatars/1.png" alt class="h-auto rounded-circle" />
                    </div>
                </a>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="{{ asset('') }}assets/img/avatars/1.png" alt
                                            class="h-auto rounded-circle" />
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
                    <li>
                        <a class="dropdown-item" href="{{ route('site.view', 'general-information') }}">
                            <i class="ti ti-settings me-2 ti-sm"></i>
                            <span class="align-middle">Setting</span>
                        </a>
                    </li>
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

        <livewire:cart-dropdown />

    </div>
</nav>
