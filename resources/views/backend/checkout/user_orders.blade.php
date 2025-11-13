@extends('backend.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
            <h4 class="card-header">My Orders History</h4>

        <div class="order-contain p-3">
            @forelse ($orders as $index => $item)
                <div class="card border-0 shadow-sm mb-4 rounded-3">
                    <div class="row g-0 align-items-stretch">

                        <div class="col-md-4 bg-primary text-white p-3 rounded-start d-flex flex-column justify-content-between">
                            <div>
                                 <div class="mb-2">
                                    <a href="{{ route('invoice', $item->pid) }}" class="d-block text-white text-decoration-none mb-2">
                                        <i class="bi bi-receipt me-1"></i> Invoice
                                    </a>
                                    <a href="{{ route('order.tracking', $item->pid) }}" target="_blank" class="d-block text-white text-decoration-none">
                                        <i class="bi bi-truck me-1"></i> Track Order
                                    </a>
                                </div>
                                <ul class="list-unstyled mb-0 small">
                                    <li class="mb-2">
                                        @if($item->delivery_status == 0)
                                            <span class="badge bg-danger">Delivery Pending</span>
                                        @else
                                            <span class="badge bg-success">Delivered</span>
                                        @endif
                                    </li>

                                    <li class="mb-2">
                                        @if($item->pay_status == 0)
                                            <span class="badge bg-danger">Not Paid</span>
                                        @else
                                            <span class="badge bg-success">Paid</span>
                                        @endif
                                    </li>

                                    <li class="mb-2">
                                        @if($item->pending_status == 0)
                                            <span class="badge bg-warning text-dark">Order Pending</span>
                                        @else
                                            <span class="badge bg-success">Order Completed</span>
                                        @endif
                                    </li>
                                </ul>


                            </div>

                            <div class="mt-3">
                                @if($item->cancel_status == 0)
                                    <button class="btn btn-light btn-sm w-100 fw-semibold text-danger" data-bs-toggle="modal" data-bs-target="#order-box-{{ $item->id }}">
                                        <i class="bi bi-x-circle me-1"></i> Cancel
                                    </button>
                                @else
                                    <div class="text-center mt-2 small">
                                        <span class="badge bg-success px-3 py-2">Order Cancelled</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- RIGHT PANEL --}}
                        <div class="col-md-8 p-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold mb-0">Order #{{ $item->pid }}</h6>
                                <span class="text-muted small">{{ $item->created_at->format('Y-m-d') }}</span>
                            </div>

                            {{-- PRODUCTS --}}
                            <div class="mb-3">
                                @foreach ($item->orderProductLists as $prod)
                                    <div class="d-flex align-items-center mb-2">
                                        <img src="{{ $prod->product->image }}" alt="" class="rounded border me-2" width="55" height="55">
                                        <div>
                                            <div class="fw-semibold small">{{ $prod->product->name }} <span class="text-muted">× {{ $prod->quantity }}</span></div>
                                            @if($prod->notes)
                                                <div class="text-muted small fst-italic">{{ $prod->notes }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="border-top pt-2 mt-2">
                                <div class="d-flex justify-content-between small">
                                    <span class="text-muted">Total Amount:</span>
                                    <span class="fw-semibold text-dark">Rs. {{ $item->amount }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- CANCEL MODAL --}}
                    <div class="modal fade" id="order-box-{{ $item->id }}" tabindex="-1" aria-labelledby="cancelOrderLabel{{ $item->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow">
                                <div class="modal-header bg-danger text-white py-2">
                                    <h6 class="modal-title" id="cancelOrderLabel{{ $item->id }}">Cancel Order</h6>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('client.order.cancel') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="order_id" value="{{ $item->id }}">
                                    <div class="modal-body">
                                        <p class="text-muted small mb-2">Please provide your reason for cancelling this order:</p>
                                        <textarea name="reason" class="form-control form-control-sm" rows="3" required placeholder="Type reason..."></textarea>
                                    </div>
                                    <div class="modal-footer py-2">
                                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-danger btn-sm fw-semibold">Submit</button>
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
</div>

@endsection
