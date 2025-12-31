@extends('backend.master')

@section('content')
    <div class="container-xxl flex-grow-1 py-4">

        <!-- Filter Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">
                            <span class="text-muted fw-light">Brands / </span>{{ $query }}
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('brand.list') }}" method="get">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <input type="text" name="search" class="form-control" placeholder="Search brands"
                                        value="{{ $query }}">
                                </div>

                                <div class="col-md-2">
                                    <select class="form-select" name="per_page">
                                        <option value="6" {{ request('per_page') == 6 ? 'selected' : '' }}>6 per page
                                        </option>
                                        <option value="12" {{ request('per_page') == 12 ? 'selected' : '' }}>12 per page
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-3 d-flex align-items-end" style="gap: 1rem;">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-filter me-2"></i>
                                    </button>
                                    <a href="{{ route('brand.list') }}" class="btn btn-outline-danger w-100">
                                        <i class="fas fa-refresh me-2"></i>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Brands Grid -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row g-4">
                    @foreach ($brands as $brand)
                        <div class="col-6 col-sm-6 col-md-4 col-lg-3">
                            <div class="card h-100 text-center shadow-sm border-0">
                                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                    <a href="{{ route('user.dashboard', ['brand' => $brand->slug]) }}">
                                        <div class="rounded-circle overflow-hidden mb-3"
                                            style="width: 120px; height: 120px; ">
                                            <img src="{{ $brand->image ?: asset('/default-png-min.png') }}"
                                                alt="{{ $brand->name }}" class="w-100 h-100" style="object-fit: cover;"
                                                loading="lazy">
                                        </div>
                                    </a>
                                    <h6 class="fw-semibold mb-0">{{ $brand->name }}</h6>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <!-- Pagination -->
                @if ($brands->hasPages())
                    <div class=" text-center mt-3">
                        {{ $brands->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection
