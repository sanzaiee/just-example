@extends('backend.master')
@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <span>
                        <h4 class="text-success">Order Success</h4>
                        <p class="">Order placed Successfully And Your Order Is On The Way</p>
                        <h6><code>{{ $order->pid }}</code></h6>
                    </span>
                    <span>
                        <code>Cash On Delivery</code><Br>
                        <small>{{ $order->created_at->diffForHumans() }}</small>
                    </span>
                </div>
            </div>
        </div>


        <div class="row g-sm-4 g-3 mt-3">
            <div class="col-xxl-9 col-lg-8">

                <div class="card">
                    <div class="card-title p-2">
                        <code>Products </code>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                                @forelse ($productList as $item)
                                    <tr>
                                        <td class="d-flex flex-column">
                                            <a href="{{ route('product.show',$item->product->id) }}" target="blank">
                                                <img src="{{ $item->product->image }}"
                                                    class="rounded float-start img-thumbnail blur-up lazyload" alt="" width="100px" height="100px">
                                            </a>
                                            <a href="{{ route('product.show',$item->product->id) }}" class="mt-2" target="blank">{{ $item->product->name }}</a>
                                        </td>

                                        <td class="price">
                                            <h6 class="fw-bold">Price</h6>
                                            <p>Rs. {{ $item->product->price }} <del class="text-danger"> Rs. {{ $item->product->strike_price }}</del></p>
                                        </td>

                                        <td class="quantity">
                                            <h6 class="fw-bold">Qty</h6>
                                            <p class="text-title">{{ $item->quantity }}</p>
                                        </td>

                                        <td class="subtotal">
                                            <h6 class="fw-bold">Sub Total</h6>
                                            <p>{{ $item->product->price * $item->quantity }}</p>
                                        </td>
                                    </tr>
                                @empty

                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-xxl-3 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-lg-12 col-sm-12">
                                <h5>Price Details ({{ $productList->count() }} Items)</h5>

                                <td class="subtotal">
                                    <h6 class="fw-bold"><code>Total (Rs) : {{ $order->amount }}</code></h6>
                                </td>

                                    {{-- @if($order->coupon_id)
                                        <ul class="summery-contain">
                                            <li>
                                                <h4>Coupon Discount</h4>
                                                <h4 class="price text-danger">{{ $order->coupon->discount ?? '0' }}</h4>
                                            </li>
                                        </ul>
                                    @endif --}}

                            </div>

                            <div class="col-lg-12 col-sm-6">
                                    <h5>Shipping Address</h5>

                                    @foreach ([['name','Name'],['email','Email'],['address','Address'],['city','City'],['house_no','House Number'],['street','Street']] as $index => $title)
                                        @if($deliveryAddress->{$title[0]})
                                            <div class="fw-bold"> <code>{{ $title[1] }} : </code> <span class="fm-lighter">{{ $deliveryAddress->{$title[0]} }}</span></div>
                                        @endif
                                    @endforeach

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>


</div>

@endsection
