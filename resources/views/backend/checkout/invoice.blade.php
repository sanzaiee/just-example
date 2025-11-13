@extends('backend.master')
@section('content')
@inject('app_setting','App\Helpers\AppHelper')
@inject('checkout','App\Helpers\CheckoutHelper')

   <section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xxl-6 col-xl-8 col-lg-10">
                <div class="card shadow-sm border-0">

                    {{-- Header --}}
                    <div class="card-header bg-white border-bottom-0 d-flex justify-content-between align-items-center py-3">
                        <div>
                            <img src="{{ $app_setting->siteSettingImages('logo') ?? '' }}" alt="Logo" class="img-fluid" style="max-height: 60px;">
                        </div>
                        <div class="text-end">
                            <h4 class="fw-bold text-primary mb-0">INVOICE</h4>
                            <small class="text-muted">#{{ $checkout->invoice(request()->pid)->pid }}</small>
                        </div>
                    </div>

                    <div class="card-body">

                        {{-- Address Section --}}
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-secondary mb-2">Billing Address</h6>
                                <ul class="list-unstyled small mb-0">
                                    <li>{{ $checkout->invoice(request()->pid)->shippingAddress->name }}</li>
                                    <li>{{ $checkout->invoice(request()->pid)->shippingAddress->address }}</li>
                                    <li>{{ $checkout->invoice(request()->pid)->shippingAddress->street ?? '' }}</li>
                                    <li>{{ $checkout->invoice(request()->pid)->shippingAddress->city ?? '' }}</li>
                                    <li>{{ 'House No. ' . ($checkout->invoice(request()->pid)->shippingAddress->house_no ?? '') }}</li>
                                    <li>Email: {{ $checkout->invoice(request()->pid)->shippingAddress->email ?? '' }}</li>
                                    <li>Phone: {{ $checkout->invoice(request()->pid)->shippingAddress->phone ?? '' }}</li>
                                </ul>
                            </div>
                            <div class="col-md-6 text-md-end mt-4 mt-md-0">
                                <h6 class="fw-bold text-secondary mb-2">Invoice Details</h6>
                                <ul class="list-unstyled small mb-0">
                                    <li><span class="fw-semibold">Issue Date:</span> {{ date('M d, Y | g:i a', strtotime($checkout->invoice(request()->pid)->created_at)) }}</li>
                                    <li><span class="fw-semibold">Invoice No:</span> {{ $checkout->invoice(request()->pid)->pid }}</li>
                                    <li><span class="fw-semibold">Email:</span> {{ $checkout->invoice(request()->pid)->shippingAddress->email ?? '' }}</li>
                                </ul>
                            </div>
                        </div>

                        {{-- Products Table --}}
                        <div class="table-responsive">
                            <table class="table table-sm align-middle border">
                                <thead class="table-light">
                                    <tr class="text-center">
                                        <th style="width: 5%;">#</th>
                                        <th class="text-start">Product</th>
                                        <th style="width: 10%;">Qty</th>
                                        <th style="width: 15%;">Price</th>
                                        <th style="width: 20%;">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($checkout->invoice(request()->pid)->orderProductLists as $index => $prod)
                                        <tr>
                                            <td class="text-center">{{ ++$index }}</td>
                                            <td class="text-start">
                                                <div class="fw-semibold">{{ $prod->product->name }}</div>
                                            </td>
                                            <td class="text-center">{{ $prod->quantity }}</td>
                                            <td class="text-center">Rs {{ number_format($prod->product->price, 2) }}</td>
                                            <td class="text-end">Rs {{ number_format($prod->product->price * $prod->quantity, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="4" class="text-end fw-bold">GRAND TOTAL</td>
                                        <td class="text-end fw-bold text-primary">
                                            Rs {{ number_format($checkout->invoice(request()->pid)->amount, 2) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div>

                    {{-- Footer --}}
                    <div class="card-footer bg-white border-0 text-center py-3">
                        <button onclick="window.print();" class="btn btn-primary btn-sm me-2">
                            <i class="bi bi-file-earmark-pdf me-1"></i> Export as PDF
                        </button>
                        <button onclick="window.print();" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-printer me-1"></i> Print
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>


    @endsection
