@extends('backend.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-2">
            <span class="text-muted fw-light">Orders </span>
        </h4>

        <!-- Filter Form -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="fas fa-filter me-2"></i>Filter Orders
                </h5>
                <form method="GET" action="{{ route('order.index') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="pid" class="form-label">Order ID (PID)</label>
                            <input type="text" class="form-control" id="pid" name="pid"
                                value="{{ request('pid') }}" placeholder="Enter Order ID">
                        </div>
                        <div class="col-md-2">
                            <label for="date_from" class="form-label">From Date</label>
                            <input type="date" class="form-control" id="date_from" name="date_from"
                                value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2">
                            <label for="date_to" class="form-label">To Date</label>
                            <input type="date" class="form-control" id="date_to" name="date_to"
                                value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-2">
                            <label for="per_page" class="form-label">Per Page</label>
                            <select class="form-control" id="per_page" name="per_page">
                                <option value="10" @if (request('per_page') == 10) selected @endif>10</option>
                                <option value="15" @if (request('per_page') == 20) selected @endif>20</option>
                                <option value="50" @if (request('per_page') == 50) selected @endif>50</option>
                                <option value="100" @if (request('per_page') == 100) selected @endif>100</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end" style="gap: 1rem;">
                            <div class="w-100">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i>
                                </button>
                                <a href="{{ route('order.index') }}" class="btn btn-outline-danger">
                                    <i class="fas fa-refresh me-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card h-90 shadow-sm">
            <div class="p-2">
                <div class="table-responsive mt-2">
                    {{-- <table id="example0" class="table display"> --}}

                    <table id="example0" class="table mb-2">
                        <thead>
                            <tr>
                                <th class="s-n">S.N</th>
                                <th class="order-id">Order</th>
                                <th class="order-id">User</th>
                                <th class="product-name">
                                    <span class="text-nowrap">Products</span>
                                </th>


                                <th class="product-price">
                                    <span class="text-nowrap">
                                        Amount
                                    </span>
                                </th>
                                <th class="Date">
                                    Date
                                </th>

                                <th class="delivery_method">
                                    Order Type
                                </th>

                                <th class="Status">
                                    Order Processing
                                </th>

                                <th class="Status">
                                    Pay Status
                                </th>

                                <th class="Status">
                                    Delivery Status
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $index => $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="@if($item->cancel_status == 1) cancelled-order @endif @if($item->order_status == 3) complete-order @endif">
                                        <a href="{{ route('order.show', $item->pid) }}"
                                            data-bs-toggle="tooltip" 
                                            @if($item->cancel_status == 1) data-bs-original-title="Cancelled Order" @endif 
                                            
                                            @if($item->order_status == 3) data-bs-original-title="Completed Order" @endif
                                            >{{ $item->pid }}
                                        </a>
                                    </td>

                                    <td>
                                        <button type="button" class="btn btn-sm rounded-pill btn-primary me-2"
                                            data-bs-toggle="modal" data-bs-target="#billingInfo{{ $index }}">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </td>


                                    <td class="product-name">
                                        <button type="button" class="btn btn-sm rounded-pill btn-primary me-2"
                                            data-bs-toggle="modal" data-bs-target="#exampleModalLong{{ $index }}">
                                            <i class="fa fa-eye"></i>
                                        </button>

                                    </td>


                                    <td class="product-price">
                                        $ {{ number_format($item->amount, 2) }}
                                    </td>

                                    <td class="issue-date">
                                        {{ $item->created_at->format('Y-m-d') }}
                                    </td>

                                    <td class="delivery_method">
                                        @if ($item->is_store_pickup)
                                            <span class="badge bg-info">Pickup</span>
                                        @else
                                            <span class="badge bg-secondary">Delivery</span>
                                        @endif
                                    </td>


                                    <td class="status">
                                        {{-- @if($item->order_status == 3)
                                            <span class="badge bg-success">-</span>
                                        @else --}}
                                            @if ($item->pending_status == 0)
                                                <a href="" class="btn btn-sm rounded-pill btn-danger me-2"
                                                    data-bs-toggle="tooltip" data-bs-original-title=""
                                                    onclick="event.preventDefault(); if(confirm('Are You Sure ?')) document.getElementById('pending-status-form-{{ $item->id }}').submit();">
                                                    Pending
                                                </a>
                                                <form id="pending-status-form-{{ $item->id }}"
                                                    action="{{ route('pending.status', $item->id) }}" method="post">
                                                    @csrf
                                                    @method('put')
                                                </form>
                                            @else
                                                <a href="" class="btn btn-sm rounded-pill btn-primary me-2"
                                                    data-bs-toggle="tooltip" data-bs-original-title="Order Status"
                                                    onclick="event.preventDefault(); if(confirm('Are You Sure ?')) document.getElementById('pending-status-form-{{ $item->id }}').submit();">
                                                    Processing
                                                </a>
                                                <form id="pending-status-form-{{ $item->id }}"
                                                    action="{{ route('pending.status', $item->id) }}" method="post">
                                                    @csrf
                                                    @method('put')
                                                </form>
                                            @endif
                                        {{-- @endif --}}
                                    </td>

                                    <td class="status">
                                        @if ($item->pay_status == 0)
                                            <a href="" class="btn btn-sm rounded-pill btn-danger me-2"
                                                data-bs-toggle="tooltip" data-bs-original-title=""
                                                onclick="event.preventDefault(); if(confirm('Are You Sure ?')) document.getElementById('pay-status-form-{{ $item->id }}').submit();">
                                                Due
                                            </a>
                                            <form id="pay-status-form-{{ $item->id }}"
                                                action="{{ route('order.pay.status', $item->id) }}" method="post">
                                                @csrf
                                                @method('put')
                                            </form>
                                        @else
                                            <span class="btn btn-sm rounded-pill btn-success me-2">Paid</span>
                                        @endif
                                    </td>

                                    <td class="status">
                                        @if($item->is_store_pickup)
                                            <span class="badge bg-info">N/A</span>
                                        @else
                                            @if ($item->delivery_status == 0)
                                                <a href="" class="btn btn-sm rounded-pill btn-danger me-2"
                                                    data-bs-toggle="tooltip" data-bs-original-title="Delivery Status"
                                                    onclick="event.preventDefault(); if(confirm('Are You Sure ?')) document.getElementById('delivery-status-form-{{ $item->id }}').submit();">
                                                    Pending
                                                </a>
                                                <form id="delivery-status-form-{{ $item->id }}"
                                                    action="{{ route('delivery.status', $item->id) }}" method="post">
                                                    @csrf
                                                    @method('put')
                                                </form>
                                            @else
                                                <a href="" class="btn btn-sm rounded-pill btn-primary me-2"
                                                    data-bs-toggle="tooltip" data-bs-original-title="Delivery Status"
                                                    onclick="event.preventDefault(); if(confirm('Are You Sure ?')) document.getElementById('delivery-status-form-{{ $item->id }}').submit();">
                                                    Delivered
                                                </a>
                                                <form id="delivery-status-form-{{ $item->id }}"
                                                    action="{{ route('delivery.status', $item->id) }}" method="post">
                                                    @csrf
                                                    @method('put')
                                                </form>
                                            @endif
                                        @endif
                                    </td>

                                    <div class="modal fade" id="exampleModalLong{{ $index }}" tabindex="-1"
                                        aria-labelledby="exampleModalLong{{ $index }}Title" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-md">
                                            <div class="modal-content border-0 shadow-lg">
                                                <div class="modal-header">
                                                    <h5 class="modal-title fw-bold"
                                                        id="exampleModalLong{{ $index }}Title">
                                                        <i class="fas fa-shopping-cart me-2"></i>Product List
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body px-4 px-sm-1">
                                                    @foreach ($item->orderProductLists as $prod)
                                                        <div class="card m-2">
                                                        <a href="{{ route('product.detail', $prod->product->slug) }}"
                                                            target="_blank" class="text-decoration-none text-dark">
                                                            <div class="cart-item">
                                                                <img src="{{ $prod->product->image }}" style="width: 120px;" alt="{{ $prod->product->name }}" class="img-fluid object-fit-cover">
                                                                <div class="flex-grow-1 d-flex flex-column justify-content-center">
                                                                    <h5 class="mb-1">{{ $prod->product->name }}</h5>
                                                                    <div class="d-flex gap-2 text-muted small">
                                                                        <span class="price">$ {{ number_format($prod->price, 2) }}</span>
                                                                        <span class="quantity">× {{ $prod->quantity }}</span>
                                                                    </div>
                                                                    <div>

                                                                    </div>
                                                                    <div class="mt-1 small">
                                                                        {{ Str::limit($prod->product->description, 70) }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- <div
                                                                class="card mb-3 border-0 bg-light hover-shadow transition-all">
                                                                <img src="/default-png-min.png" alt="{{$prod->product->name}}" class="item-image">
                                                                <div class="card-body p-3">
                                                                    <div
                                                                        class="d-flex justify-content-between align-items-center">
                                                                        <div>
                                                                            <h6
                                                                                class="card-title mb-1 text-dark fw-semibold">
                                                                                {{ $prod->product->name }}
                                                                            </h6>
                                                                            <small class="text-muted">
                                                                                <i class="fas fa-cube me-1"></i>
                                                                                Quantity: {{ $prod->quantity }}
                                                                            </small>
                                                                        </div>
                                                                        <div class="text-end">
                                                                            <span class="badge bg-success fs-6">
                                                                                $ {{ number_format($prod->product->getPriceForQuantity($prod->quantity) * $prod->quantity, 2) }}
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div> --}}
                                                        </a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="billingInfo{{ $index }}" tabindex="-1"
                                        aria-labelledby="billingInfo{{ $index }}Title" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="billingInfo{{ $index }}Title"></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <code>First Name : </code>
                                                            <p class="mb-0">
                                                                {{ $item->user->name }}
                                                            </p>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <code>Last Name : </code>
                                                            <p class="mb-0">
                                                                {{ $item->user->lname }}
                                                            </p>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <code>Phone : </code>
                                                            <p class="mb-0">
                                                                {{ $item->user->mobile }}
                                                            </p>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <code>Email : </code>
                                                            <p class="mb-0">
                                                                {{ $item->user->email }}
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <hr>
                                                    <h5 class="modal-title">Delivery Method</h5>

                                                    <div class="row">
                                                        @if ($item->is_store_pickup)
                                                            <div class="fw-bold"> <code>Store Pickup</code> <span class="fm-lighter"></span></div>
                                                        @else
                                                            @foreach ([
                                                                ['house_no', 'Apt / Suite / Floor'], 
                                                                ['address', 'Address'], 
                                                                // ['name', 'Name'], 
                                                                // ['email', 'Email'], 
                                                                ['city', 'City'], 
                                                                ['postal_code', 'Postal Code']] as $index => $title)
                                                                @if ($item->orderDeliveryAddress?->{$title[0]})
                                                                    <div class="col-md-6 mb-3">
                                                                        <code>{{ $title[1] }} : </code>
                                                                        <p class="mb-0">
                                                                            {{ $item->orderDeliveryAddress?->{$title[0]} ?? '-' }}
                                                                        </p>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </div>

                                                    <hr>
                                                    <div class="row">
                                                        <h5 class="mb-0">Notes:</h5>
                                                        <p class="mb-0">{{ $item->notes }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </tr>
                            @empty
                            @endforelse


                        </tbody>
                    </table>

                    {{ $orders->links('pagination::bootstrap-5') }}
                </div>


            </div>
        </div>
        {{-- </div>
</div> --}}
    </div>
@endsection
@push('css')
    <style>
        .product-price {
            text-align: center;
        }

        .cancelled-order {
            background-color: #ea5455 !important;
        }

        .cancelled-order a{
            color: white !important;
        }
        .complete-order {
            background-color: #28c76f !important;
        }

        .complete-order a {
            color: white !important;
        }
    </style>
@endpush
