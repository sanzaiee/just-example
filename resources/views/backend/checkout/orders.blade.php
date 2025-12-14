@extends('backend.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4">
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
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="btn-group w-100">
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
            <div class="card-body">
                <div class="table-responsive mt-4">
                    {{-- <table id="example0" class="table display"> --}}

                    <table id="example0" class="table table-striped">
                        <thead>
                            <tr>
                                <th class="s-n">S.N</th>
                                <th class="order-id">Order ID</th>
                                <th class="order-id">User Name</th>
                                <th class="product-name">
                                    <span class="text-nowrap">Products</span>
                                </th>


                                <th class="product-price">
                                    <span class="text-nowrap">
                                        Price
                                    </span>
                                </th>
                                <th class="Date">
                                    Date
                                </th>

                                <th class="Status">
                                    Order Status
                                </th>

                                <th class="Status">
                                    Delivery Status
                                </th>

                                <th class="Status">
                                    Pay Status
                                </th>

                                <th class="Status">
                                    Action
                                </th>
                                <th class="Status">
                                    Cancel Order
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $index => $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="order-id">
                                        {{ $item->pid }}
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
                                        {{ $item->amount }}
                                    </td>

                                    <td class="issue-date">
                                        {{ $item->created_at->format('Y-m-d') }}
                                    </td>

                                    <td class="status">
                                        @if ($item->pending_status == 0)
                                            <a href="" class="btn btn-sm rounded-pill btn-danger me-2"
                                                data-bs-toggle="tooltip" data-bs-original-title="Order Status"
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
                                                Compeleted
                                            </a>
                                            <form id="pending-status-form-{{ $item->id }}"
                                                action="{{ route('pending.status', $item->id) }}" method="post">
                                                @csrf
                                                @method('put')
                                            </form>
                                        @endif
                                    </td>

                                    <td class="status">
                                        @if ($item->delivery_status == 0)
                                            <a href="" class="btn btn-sm rounded-pill btn-danger me-2"
                                                data-bs-toggle="tooltip" data-bs-original-title="Delivery Status"
                                                onclick="event.preventDefault(); if(confirm('Are You Sure ?')) document.getElementById('delivery-status-form-{{ $item->id }}').submit();">
                                                Not Delivered
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
                                    </td>

                                    <td class="status">
                                        @if ($item->pay_status == 0)
                                            <a href="" class="btn btn-sm rounded-pill btn-danger me-2"
                                                data-bs-toggle="tooltip" data-bs-original-title="Order Status"
                                                onclick="event.preventDefault(); if(confirm('Are You Sure ?')) document.getElementById('pay-status-form-{{ $item->id }}').submit();">
                                                UnPaid
                                            </a>
                                            <form id="pay-status-form-{{ $item->id }}"
                                                action="{{ route('order.pay.status', $item->id) }}" method="post">
                                                @csrf
                                                @method('put')
                                            </form>
                                        @else
                                            <span class="btn btn-sm rounded-pill btn-primary me-2">Paid</span>
                                        @endif
                                    </td>

                                    <td>
                                        <a href="{{ route('order.show', $item->pid) }}">
                                            <span class="btn btn-sm rounded-pill btn-primary me-2"><i
                                                    class="fa fa-eye"></i></span>
                                        </a>
                                    </td>

                                    <td>
                                        @if ($item->cancel_status == 0)
                                            <button type="button" class="btn btn-sm rounded-pill btn-danger me-2"
                                                data-bs-toggle="modal"
                                                data-bs-target="#cancelOrderModal{{ $index }}">
                                                <i class="fa fa-times"></i> Cancel
                                            </button>
                                        @else
                                            <span class="btn btn-sm rounded-pill btn-secondary me-2">Cancelled</span>
                                        @endif
                                    </td>

                                    <div class="modal fade" id="exampleModalLong{{ $index }}" tabindex="-1"
                                        aria-labelledby="exampleModalLong{{ $index }}Title" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content border-0 shadow-lg">
                                                <div class="modal-header">
                                                    <h5 class="modal-title fw-bold"
                                                        id="exampleModalLong{{ $index }}Title">
                                                        <i class="fas fa-shopping-cart me-2"></i>Product List
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    @foreach ($item->orderProductLists as $prod)
                                                        <a href="{{ route('product.detail', $prod->product->slug) }}"
                                                            target="_blank" class="text-decoration-none">
                                                            <div
                                                                class="card mb-3 border-0 bg-light hover-shadow transition-all">
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
                                                                                Rs.
                                                                                {{ number_format($prod->product->getPriceForQuantity($prod->quantity) * $prod->quantity, 2) }}
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
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
                                                    <h5 class="modal-title" id="billingInfo{{ $index }}Title">
                                                        Shipping Address</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        @foreach ([['name', 'Name'], ['email', 'Email'], ['address', 'Address'], ['city', 'City'], ['house_no', 'House Number'], ['street', 'Street']] as $index => $title)
                                                            @if ($item->shippingAddress->{$title[0]})
                                                                <div class="col-md-6 mb-3">
                                                                    <code>{{ $title[1] }} : </code>
                                                                    <p class="mb-0">
                                                                        {{ $item->shippingAddress->{$title[0]} ?? '' }}
                                                                    </p>
                                                                </div>
                                                            @endif
                                                        @endforeach
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


        <!-- Cancel Order Modal -->
        @forelse ($orders as $index => $item)
            @if ($item->cancel_status == 0)
                <div class="modal fade" id="cancelOrderModal{{ $index }}" tabindex="-1"
                    aria-labelledby="cancelOrderModal{{ $index }}Title" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="cancelOrderModal{{ $index }}Title">
                                    Cancel Order #{{ $item->pid }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="{{ route('client.order.cancel') }}" method="post">
                                @csrf
                                <div class="modal-body">
                                    <input type="hidden" name="order_id" value="{{ $item->id }}">
                                    <div class="mb-3">
                                        <label for="reason" class="form-label">Cancellation Reason</label>
                                        <textarea class="form-control" id="reason" name="reason" rows="3" required
                                            placeholder="Please provide a reason for cancelling this order..."></textarea>
                                    </div>
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <strong>Warning:</strong> This action cannot be undone. The order will be marked as
                                        cancelled.
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-danger">Confirm Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        @empty
        @endforelse



    </div>
@endsection
