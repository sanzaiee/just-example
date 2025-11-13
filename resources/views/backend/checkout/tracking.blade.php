@extends('backend.master')
@section('content')
@inject('app_setting','App\Helpers\AppHelper')
@inject('checkout','App\Helpers\CheckoutHelper')
@php
    $product =$checkout->invoice(request()->pid);
@endphp

<section class="m-3 p-4 bg-light border-bottom">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
      <h2 class="h4 mb-0">Order Tracking</h2>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item">
            <a href="index.html" class="text-decoration-none text-secondary">
              <i class="fa-solid fa-house"></i>
            </a>
          </li>
          <li class="breadcrumb-item active text-dark" aria-current="page">Order Tracking</li>
        </ol>
      </nav>
    </div>
  </div>
</section>

<!-- Order Tracking Info -->
<section class="m-3 p-3">
  <div class="container">
    <div class="row g-4 align-items-center">

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
                  <div class="rounded-circle {{ $product->pending_status == 1 ? 'bg-success' : 'bg-secondary' }} text-white p-2 mb-2">
                    <i class="fa-solid fa-cogs"></i>
                  </div>
                  <h6 class="mb-0">Processing</h6>
                </div>
              </li>

              <li class="list-inline-item flex-fill">
                <div class="d-flex flex-column align-items-center">
                  <div class="rounded-circle {{ $product->delivery_status == 1 ? 'bg-success' : 'bg-secondary' }} text-white p-2 mb-2">
                    <i class="fa-solid fa-truck"></i>
                  </div>
                  <h6 class="mb-0">Delivery Pending</h6>
                </div>
              </li>

              <li class="list-inline-item flex-fill">
                <div class="d-flex flex-column align-items-center">
                  <div class="rounded-circle {{ $product->pending_status == 1 && $product->delivery_status == 1 ? 'bg-success' : 'bg-secondary' }} text-white p-2 mb-2">
                    <i class="fa-solid fa-box-open"></i>
                  </div>
                  <h6 class="mb-0">Delivered</h6>
                </div>
              </li>

              <li class="list-inline-item flex-fill">
                <div class="d-flex flex-column align-items-center">
                  <div class="rounded-circle {{ $product->pay_status == 1 ? 'bg-success' : 'bg-secondary' }} text-white p-2 mb-2">
                    <i class="fa-solid fa-credit-card"></i>
                  </div>
                  <h6 class="mb-0">Payment Pending</h6>
                </div>
              </li>

              <li class="list-inline-item flex-fill">
                <div class="d-flex flex-column align-items-center">
                  <div class="rounded-circle {{ $product->pay_status == 1 ? 'bg-success' : 'bg-secondary' }} text-white p-2 mb-2">
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
  </div>
</section>

<!-- Order Table -->
<section class="p-5 bg-light">
  <div class="container">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Order Summary</h5>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table align-middle mb-0">
            <thead class="table-light">
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
                  <img src="{{ $prod->product->image }}" height="80" width="80" class="rounded me-3 border" alt="">
                  <div>
                    <strong>{{ $prod->product->name }}</strong>
                  </div>
                </td>
                <td>{{ $prod->quantity }}</td>
                <td>Rs {{ $prod->product->price }}</td>
                <td class="fw-bold text-end">Rs {{ $prod->product->price * $prod->quantity }}</td>
              </tr>
              @endforeach
            </tbody>
            <tfoot class="table-light">
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
</section>

@endsection
