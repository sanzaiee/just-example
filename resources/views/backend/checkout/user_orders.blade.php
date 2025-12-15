@extends('backend.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <!-- Filter Form -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
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
                                    <i class="fas fa-filter me-2"></i>
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


        <div class="card">
            <h4 class="card-header">My Orders History</h4>

            <div class="order-contain p-3">
                @forelse ($orders as $index => $item)
                    <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                        <div class="row g-0 h-100">
                            <!-- Left Status Panel -->
                            <div class="col-lg-3 bg-primary text-white p-4 d-flex flex-column">
                                <!-- Order Actions -->
                                <div class="mb-4">
                                    <h6 class="text-white small text-uppercase mb-3">Quick Actions</h6>
                                    <div class="d-grid gap-2">
                                        {{-- <a href="{{ route('invoice', $item->pid) }}"
                                            class="btn btn-outline-secondary btn-sm text-start">
                                            <i class="bi bi-receipt me-2"></i> View Invoice
                                        </a> --}}
                                        <a href="{{ route('order.tracking', $item->pid) }}" target="_blank"
                                            class="btn bg-secondary btn-sm text-start">
                                            <i class="bi bi-truck me-2"></i> Track Order
                                        </a>
                                    </div>
                                </div>

                                <!-- Status Badges -->
                                <div class="mb-4">
                                    <h6 class="text-white small text-uppercase mb-3">Order Status</h6>
                                    <div class="d-flex flex-column gap-2">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="small">Delivery</span>
                                            @if ($item->delivery_status == 0)
                                                <span class="badge bg-danger bg-opacity-75">Pending</span>
                                            @else
                                                <span class="badge bg-success bg-opacity-75">Delivered</span>
                                            @endif
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="small">Payment</span>
                                            @if ($item->pay_status == 0)
                                                <span class="badge bg-danger bg-opacity-75">Unpaid</span>
                                            @else
                                                <span class="badge bg-success bg-opacity-75">Paid</span>
                                            @endif
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="small">Status</span>
                                            @if ($item->pending_status == 0)
                                                <span class="badge bg-warning text-dark bg-opacity-75">Pending</span>
                                            @else
                                                <span class="badge bg-success bg-opacity-75">Completed</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Cancel Action -->
                                <div class="mt-auto">
                                    @if ($item->cancel_status == 0)
                                        <button class="btn btn-danger btn-sm w-100" data-bs-toggle="modal"
                                            data-bs-target="#order-box-{{ $item->id }}">
                                            <i class="bi bi-x-circle me-2"></i> Cancel Order
                                        </button>
                                    @else
                                        <div class="text-center">
                                            <span class="badge bg-success bg-opacity-75 px-3 py-2">
                                                <i class="bi bi-check-circle me-1"></i> Cancelled
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Right Order Details Panel -->
                            <div class="col-lg-9 p-4">
                                <!-- Order Header -->
                                <div class="d-flex justify-content-between align-items-start mb-4 pb-3 border-bottom">
                                    <div>
                                        <h5 class="fw-bold mb-1">Order #{{ $item->pid }}</h5>
                                        <p class="text-muted small mb-0">
                                            <i class="bi bi-calendar3 me-1"></i>
                                            {{ $item->created_at->format('M d, Y - h:i A') }}
                                        </p>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-primary bg-opacity-10 px-3 py-2">
                                            {{ number_format($item->amount, 2) }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Products List -->
                                <div class="mb-4">
                                    <h6 class="text-muted small text-uppercase mb-3">Order Items</h6>
                                    <div class="row g-3">
                                        @foreach ($item->orderProductLists as $prod)
                                            <div class="col-12">
                                                <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                                    <img src="{{ $prod->product->image }}"
                                                        alt="{{ $prod->product->name }}" class="rounded-2 border me-3"
                                                        width="60" height="60" style="object-fit: cover;">
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1 fw-semibold">{{ $prod->product->name }}</h6>
                                                        <div class="d-flex align-items-center gap-3">
                                                            <span class="badge bg-secondary bg-opacity-25 text-dark">
                                                                <i class="bi bi-box-seam me-1"></i>
                                                                Qty: {{ $prod->quantity }}
                                                            </span>
                                                            @if ($prod->notes)
                                                                <span class="text-muted small fst-italic">
                                                                    <i class="bi bi-chat-left-text me-1"></i>
                                                                    {{ $prod->notes }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Order Summary -->
                                <div class="border-top pt-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="bi bi-info-circle text-muted"></i>
                                                <span class="text-muted small">Order ID: {{ $item->pid }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <div class="d-flex align-items-center justify-content-end gap-2">
                                                <span class="text-muted">Total Amount:</span>
                                                <span class="fw-bold fs-5 text-primary">
                                                    {{ number_format($item->amount, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Cancel Order Modal -->
                        <div class="modal fade" id="order-box-{{ $item->id }}" tabindex="-1"
                            aria-labelledby="cancelOrderLabel{{ $item->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg">
                                    <div class="modal-header bg-danger text-white border-0">
                                        <h5 class="modal-title d-flex align-items-center"
                                            id="cancelOrderLabel{{ $item->id }}">
                                            <i class="bi bi-exclamation-triangle me-2"></i>
                                            Cancel Order #{{ $item->pid }}
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white"
                                            data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('client.order.cancel') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="order_id" value="{{ $item->id }}">
                                        <div class="modal-body p-4">
                                            <div class="alert alert-warning d-flex align-items-center" role="alert">
                                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                                <div>
                                                    <strong>Warning:</strong> This action cannot be undone.
                                                    Please provide a reason for cancelling this order.
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="reason-{{ $item->id }}" class="form-label fw-semibold">
                                                    Cancellation Reason <span class="text-danger">*</span>
                                                </label>
                                                <textarea name="reason" id="reason-{{ $item->id }}" class="form-control" rows="4" required
                                                    placeholder="Please explain why you need to cancel this order..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                <i class="bi bi-x-lg me-1"></i> Keep Order
                                            </button>
                                            <button type="submit" class="btn btn-danger">
                                                <i class="bi bi-check-lg me-1"></i> Confirm Cancellation
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center my-5">
                        <p class="text-muted fs-6">No orders found.</p>
                    </div>
                @endforelse
            </div>

        </div>
        <div class="mt-3">
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    </div>

@endsection
