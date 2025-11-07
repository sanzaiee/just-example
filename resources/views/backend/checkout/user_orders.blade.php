@extends('backend.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="title">
            <h4>My Orders History</h4>
        </div>

        <div class="order-contain">
            @forelse ($orders as $index => $item)
                <div class="order-box dashboard-bg-box">
                    <div class="order-container">
                        <div class="order-icon">
                            <i data-feather="box"></i>
                        </div>

                        <div class="order-detail">
                            <ul>
                                <li>
                                    @if($item->delivery_status == 0)
                                    <h4>Delivery <span class="success-bg">Pending</span></h4>
                                    @else
                                    <h4>Delivered <span class="success-bg">Success</span></h4>
                                    @endif
                                </li>
                                <li>
                                    <a href="{{ route('invoice',$item->pid) }}">
                                        <h4><span class="success-bg">Invoice</span></h4>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('order.tracking',$item->pid) }}" target="blank">
                                        <h4><span class="success-bg">Order Track</span></h4>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="product-order-detail">

                        @foreach ($item->orderProductLists as $prod)
                            <a href="{{ route('product.show',$prod->product->id) }}" class="order-image">
                                <div class="detail">
                                    <img src="{{ $prod->product->image }}" width="100px" height="100px" class="blur-up lazyload" alt="">
                                    <span>{{ $prod->product->name }}  x {{ $prod->quantity }}</span>
                                    <span>{{ $prod->notes ?? '' }}</span>
                                </div>
                            </a>
                        @endforeach



                        <div class="order-wrap">
                            <a href="product-left-thumbnail.html">
                                <h3>Order Id: {{ $item->pid }}</h3>
                            </a>

                            <ul class="product-size">
                                <li>
                                    <div class="size-box">
                                        <h6 class="text-content">Price : </h6>
                                        <h5>Rs. {{$item->amount}}</h5>
                                    </div>
                                </li>

                                <li>
                                    <div class="size-box">
                                        <h6 class="text-content">Orderd Placed On : </h6>
                                        <h5>{{ $item->created_at->format('Y-m-d') }}</h5>
                                    </div>
                                </li>



                                <li>
                                    <div class="size-box">
                                        <h6 class="text-content">Pay Status : </h6>
                                        <h5 class="text-content">@if($item->pay_status == 0) <span class="text-danger">Not Paid</span> @else <span class="text-success">Paid</span> @endif </h5>
                                    </div>
                                </li>



                                <li>
                                    <div class="size-box">
                                        <h6 class="text-content">Order Status : </h6>
                                        <h5 class="text-content"> @if($item->pending_status == 0) <span class="text-danger">Pending</span>@else<span class="text-success">Compeleted</span> @endif</h5>
                                    </div>
                                </li>


                                @if($item->cancel_status == 0)
                                    <li>
                                        <div class="size-box">
                                            <button class="btn deal-button" data-bs-toggle="modal" data-bs-target="#order-box-{{ $item->id }}">
                                                <i data-feather="zap"></i>
                                                <span>Cancel Order</span>
                                            </button>
                                        </div>
                                    </li>
                                @else
                                    <li>
                                        <div class="size-box">
                                            <h6 class="text-content"> <span class="text-success">Order Cancelled </span></h6>
                                        </div>
                                    </li>
                                @endif

                            </ul>
                        </div>

                        <div class="modal fade theme-modal deal-modal" id="order-box-{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <div>
                                            <h5 class="modal-title w-100" id="deal_today">Cacnel Order</h5>
                                            <p class="mt-1 text-content">Please give strong valid reason to cancel order.</p>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="deal-offer-box">

                                            <form action="{{ route('client.order.cancel') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="order_id" value="{{ $item->id }}">

                                                <div class="product-detail p-4">
                                                    <h4>Reason :</h4>
                                                    <textarea name="reason" class="form-control" id="" cols="50" rows="10" required></textarea>
                                                </div>
                                                <div class="modal-button">
                                                    <button type="submit"class="btn theme-bg-color view-button icon text-white fw-bold btn-md">
                                                        Submit
                                                    </button>
                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            @empty

            @endforelse

        </div>
    </div>
</div>

@endsection
