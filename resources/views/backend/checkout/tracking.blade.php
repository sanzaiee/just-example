@extends('backend.master')
@section('content')
    @inject('app_setting', 'App\Helpers\AppHelper')
    @inject('checkout', 'App\Helpers\CheckoutHelper')
    @php
        $product = $checkout->invoice(request()->pid);
    @endphp
    <!-- Order Tracking Info -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-4 align-items-center mb-3">
            <!-- Tracking Code -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="mb-3 text-muted">
                            <i class="fa-solid fa-box fa-2x"></i>
                        </div>
                        <h6 class="text-muted mb-1">Tracking Code</h6>
                        <h3 class="fw-bold text-primary mb-0">{{ $product->pid }}</h3>
                    </div>
                </div>
            </div>

            <!-- Progress Steps -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <ul class="list-inline d-flex justify-content-between text-center mb-0 flex-wrap">

                            <li class="list-inline-item flex-fill">
                                <div class="d-flex flex-column align-items-center">
                                    <div class="rounded-circle bg-success text-white p-2 mb-2">
                                        <i class="fa-solid fa-hourglass-start"></i>
                                    </div>
                                    <h6 class="mb-0">Pending</h6>
                                </div>
                            </li>

                            <li class="list-inline-item flex-fill">
                                <div class="d-flex flex-column align-items-center">
                                    <div
                                        class="rounded-circle {{ $product->pending_status == 1 ? 'bg-success' : 'bg-secondary' }} text-white p-2 mb-2">
                                        <i class="fa-solid fa-cogs"></i>
                                    </div>
                                    <h6 class="mb-0">Processing</h6>
                                </div>
                            </li>

                            <li class="list-inline-item flex-fill">
                                <div class="d-flex flex-column align-items-center">
                                    <div
                                        class="rounded-circle {{ $product->delivery_status == 1 ? 'bg-success' : 'bg-secondary' }} text-white p-2 mb-2">
                                        <i class="fa-solid fa-truck"></i>
                                    </div>
                                    <h6 class="mb-0">Delivery Pending</h6>
                                </div>
                            </li>

                            <li class="list-inline-item flex-fill">
                                <div class="d-flex flex-column align-items-center">
                                    <div
                                        class="rounded-circle {{ $product->pending_status == 1 && $product->delivery_status == 1 ? 'bg-success' : 'bg-secondary' }} text-white p-2 mb-2">
                                        <i class="fa-solid fa-box-open"></i>
                                    </div>
                                    <h6 class="mb-0">Delivered</h6>
                                </div>
                            </li>

                            <li class="list-inline-item flex-fill">
                                <div class="d-flex flex-column align-items-center">
                                    <div
                                        class="rounded-circle {{ $product->pay_status == 1 ? 'bg-success' : 'bg-secondary' }} text-white p-2 mb-2">
                                        <i class="fa-solid fa-credit-card"></i>
                                    </div>
                                    <h6 class="mb-0">Payment Pending</h6>
                                </div>
                            </li>

                            <li class="list-inline-item flex-fill">
                                <div class="d-flex flex-column align-items-center">
                                    <div
                                        class="rounded-circle {{ $product->pay_status == 1 ? 'bg-success' : 'bg-secondary' }} text-white p-2 mb-2">
                                        <i class="fa-solid fa-check-circle"></i>
                                    </div>
                                    <h6 class="mb-0">Paid</h6>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>

        </div>

        <!-- Order Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Order Summary</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="">
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Per Price</th>
                                <th>Sub Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($product->orderProductLists as $prod)
                                <tr>
                                    <td class="d-flex align-items-center">
                                        <img src="{{ $prod->product->image }}" height="80" width="80"
                                            class="rounded me-3 border" alt="">
                                        <div>
                                            <strong>{{ $prod->product->name }}</strong>
                                        </div>
                                    </td>
                                    <td>{{ $prod->quantity }}</td>
                                    <td>Rs {{ $prod->product->price }}</td>
                                    <td class="fw-bold text-end">Rs {{ $prod->product->price * $prod->quantity }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="">
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Grand Total:</td>
                                <td class="fw-bold text-primary text-end">Rs {{ $product->amount ?? 0 }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
