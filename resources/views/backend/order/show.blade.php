@extends('backend.master')
@section('content')
    <div class="container my-5">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white fw-bold">
                Order Detail
            </div>

            <div class="card-body mt-2">
                <!-- Header Section -->
                <div class="d-flex justify-content-between align-items-start flex-wrap mb-4">
                    <div>
                        <p class="mb-1">
                            <b>{{ $order->shippingAddress->name }}</b><br>
                            {{ $order->shippingAddress->address }}<br>
                            {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->house_no }}
                        </p>
                    </div>
                    <div class="text-end">
                        <img src="{{ get_logo('logo') }}" alt="logo" class="img-fluid mb-2" style="max-height: 80px;">
                    </div>
                </div>

                <!-- Order Meta -->
                <div class="row text-center border-top border-bottom py-3 mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">ISSUE DATE</h6>
                        <h5>{{ $order->created_at->diffForHumans() }}</h5>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">INVOICE ID</h6>
                        <h5>{{ $order->pid }}</h5>
                    </div>
                </div>

                <!-- Customer Info -->
                <div class="row gy-3 mb-4">
                    <div class="col-md-4">
                        <h6 class="text-muted mb-1">NAME</h6>
                        <p class="mb-0">{{ $order->shippingAddress->name ?? '' }} {{ $order->shippingAddress->lname ?? '' }}</p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted mb-1">EMAIL</h6>
                        <p class="mb-0">{{ $order->shippingAddress->email ?? '' }}</p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted mb-1">PHONE</h6>
                        <p class="mb-0">{{ $order->shippingAddress->phone ?? '' }}</p>
                    </div>

                    <div class="col-md-4">
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
                    </div>
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
                                        <img src="{{ $item->product->image }}" alt="Product" class="img-thumbnail" style="max-width: 100px;">
                                    </td>
                                    <td>{{ $item->product->name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>Rs. {{ number_format($item->price, 2) }}</td>
                                    <td class="text-end">Rs. {{ number_format($item->product->price * $item->quantity, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No products found in this order.</td>
                                </tr>
                            @endforelse
                            <tr class="table-active">
                                <td colspan="4" class="text-end fw-bold">GRAND TOTAL</td>
                                <td class="text-end fw-bold">Rs. {{ number_format($order->amount, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Order Cancel Reason -->
                @if($order->ordercancel)
                    <div class="alert alert-danger mt-4">
                        <strong>Order Cancelled:</strong> {{ $order->ordercancel->reason }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
