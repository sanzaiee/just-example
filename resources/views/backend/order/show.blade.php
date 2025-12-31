@extends('backend.master')
@section('content')
    <div class="container my-5">
        @if ($order->order_status == 3)
            <div class="alert alert-success mt-4">
                <strong>Order Complete</strong>
            </div>
        @endif
        
        <div class="card">
            <div class="card-body">
                <div class="row gy-3">
                    @if ($order->cancel_status == 0 && $order->order_status != 3)
                        <div class="col-md-3">
                            <button type="button" class="btn btn-danger me-2"
                                data-bs-toggle="modal"
                                data-bs-target="#cancelOrderModal">
                                Cancel Order
                            </button>
                        </div>
                    @endif

                    @if ($order->order_status == 1 && $order->cancel_status == 0)
                        <div class="col-md-3">
                            <a href="" onclick="event.preventDefault(); if(confirm('Are You Sure - Mark order as Complete ?')) document.getElementById('delivery-status-form-{{ $order->id }}').submit();">
                                <button type="button" class="btn btn-success me-2">Mark Complete</button>
                            </a>
                            <form id="delivery-status-form-{{ $order->id }}"
                                action="{{ route('order.status', $order->id) }}" method="post">
                                @csrf
                                @method('put')
                            </form>
                        </div>
                    @endif

                    @if ($order->shipping_address_id != getStorePickupShippingId() && $order->delivery_status == 0)
                        <div class="col-md-3">
                            <button type="button" class="btn btn-secondary me-2"
                                data-bs-toggle="modal"
                                onclick="event.preventDefault(); alert('Info: Delivery will start after integration is done');">
                                Start Delivery
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalTitle" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cancelOrderModalTitle">
                            Cancel Order #{{ $order->pid }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form action="{{ route('client.order.cancel') }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="order_id" value="{{ $order->id }}">
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

        <div class="card shadow-sm border-0 mt-2">
            <div class="card-header bg-primary text-white fw-bold">
                @if ($order->shipping_address_id == getStorePickupShippingId())
                    Pickup
                @else
                    Delivery
                @endif
            </div>

            <div class="card-body mt-2">
                <!-- Header Section -->
                @if ($order->shipping_address_id != getStorePickupShippingId())
                <div class="row gy-3 mb-4">
                    <div class="col-md-3">
                        <h6 class="text-muted mb-1">Apt/House No:</h6>
                        <p class="mb-0">{{ $order->shippingAddress->house_no ?? '' }}</p>
                    </div>
                    <div class="col-md-3">
                        <h6 class="text-muted mb-1">Street</h6>
                        <p class="mb-0">{{ $order->shippingAddress->address ?? '' }}</p>
                    </div>
                    <div class="col-md-3">
                        <h6 class="text-muted mb-1">City</h6>
                        <p class="mb-0">{{ $order->shippingAddress->city ?? '' }}</p>
                    </div>
                    <div class="col-md-3">
                        <h6 class="text-muted mb-1">Postal Code</h6>
                        <p class="mb-0">{{ $order->shippingAddress->postal_code ?? '' }}</p>
                    </div>
                </div>
                @endif

                <!-- Order Meta -->
                <div class="row text-center border-top border-bottom py-2">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">DATE</h6>
                        <h5>{{ $order->created_at->diffForHumans() }}</h5>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Order ID</h6>
                        <h5>{{ $order->pid }}</h5>
                    </div>
                </div>

                <!-- Customer Info -->
                <div class="row gy-3 mb-4">
                    <div class="col-md-4">
                        <h6 class="text-muted mb-1">NAME</h6>
                        <p class="mb-0">{{ $order->user->name ?? '' }}
                            {{ $order->user->lname ?? '' }}</p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted mb-1">EMAIL</h6>
                        <p class="mb-0">{{ $order->user->email ?? '' }}</p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted mb-1">PHONE</h6>
                        <p class="mb-0">{{ $order->user->mobile ?? '' }}</p>
                    </div>

                    {{-- <div class="col-md-4">
                        <h6 class="text-muted mb-1">ADDRESS</h6>
                        <p class="mb-0">{{ $order->shippingAddress->address ?? '' }}</p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted mb-1">STREET</h6>
                        <p class="mb-0">{{ $order->shippingAddress->street ?? '' }}</p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted mb-1">CITY</h6>
                        <p class="mb-0">{{ $order->shippingAddress->city ?? '' }}</p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted mb-1">TOLE</h6>
                        <p class="mb-0">{{ $order->shippingAddress->tole ?? '' }}</p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted mb-1">HOUSE NUMBER</h6>
                        <p class="mb-0">{{ $order->shippingAddress->house_no ?? '' }}</p>
                    </div> --}}
                </div>

                <!-- Product Table -->
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>IMAGE</th>
                                <th>PRODUCT NAME</th>
                                <th>QUANTITY</th>
                                <th>RATE PER ITEM</th>
                                <th class="text-end">TOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($order->orderProductLists as $item)
                                <tr>
                                    <td>
                                        <img src="{{ $item->product->image }}" alt="Product" class="img-thumbnail"
                                            style="max-width: 100px;">
                                    </td>
                                    <td>{{ $item->product->name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>$ {{ number_format($item->product->getPriceForQuantity($item->quantity), 2) }}
                                    </td>
                                    <td class="text-end">$
                                        {{ number_format($item->product->getPriceForQuantity($item->quantity) * $item->quantity, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No products found in this order.
                                    </td>
                                </tr>
                            @endforelse
                            <tr class="table-active">
                                <td colspan="4" class="text-end fw-bold">GRAND TOTAL</td>
                                <td class="text-end fw-bold">$ {{ number_format($order->amount, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Order Cancel Reason -->
                @if ($order->ordercancel)
                    <div class="alert alert-danger mt-4">
                        <strong>Order Cancelled:</strong> {{ $order->ordercancel->reason }}
                    </div>
                @endif
            </div>
        </div>
    </div>
<style>
    table td:not(:first-child),
    table th:not(:first-child) {
        text-align: right;
    }
</style>
@endsection
