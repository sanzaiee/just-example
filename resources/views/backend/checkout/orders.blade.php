@extends('backend.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        {{-- <div class="row g-4">
            <div class="col-md-3"> --}}
        <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Orders </span> </h4>

        <div class="card h-90 shadow-sm">
            <div class="card-body">
                 <div class="table-responsive m-t-40">
                        {{-- <table id="example0" class="table display"> --}}

                        <table id="example0" class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="order-id">Order ID</th>
                                    <th class="order-id">User Name</th>
                                    <th class="product-name">
                                        <span class="nobr">Products</span>
                                    </th>


                                    <th class="product-price">
                                        <span class="nobr">
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
                                        Payment Type
                                    </th>

                                    <th class="Status">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders as $index => $item)
                                    <tr>
                                        <td class="order-id">
                                            {{ $item->pid }}
                                        </td>

                                        <td>
                                            <button type="button" class="btn btn-rounded btn-xs btn-info m-r-5" data-bs-toggle="modal" data-bs-target="#billingInfo{{ $index }}">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </td>


                                        <td class="product-name">

                                            <button type="button" class="btn btn-rounded btn-xs btn-info m-r-5" data-bs-toggle="modal" data-bs-target="#exampleModalLong{{ $index }}">
                                                <i class="fa fa-eye"></i> Product list
                                            </button>

                                        </td>


                                        <td class="product-price">
                                            {{$item->amount}}
                                        </td>

                                        <td class="issue-date">
                                            {{ $item->created_at->format('Y-m-d') }}
                                        </td>

                                        <td class="status">
                                            @if($item->pending_status == 0)

                                                <a href="" class="btn btn-rounded btn-xs btn-danger m-r-5" data-toggle="tooltip"
                                                data-original-title="Order Status"
                                                onclick="event.preventDefault(); if(confirm('Are You Sure ?')) document.getElementById('pending-status-form-{{ $item->id }}').submit();">
                                                    Pending
                                                </a>
                                                <form id="pending-status-form-{{ $item->id }}" action="{{route('pending.status',$item->id)}}" method="post">
                                                @csrf
                                                @method('put')
                                                </form>



                                            @else
                                                <a href="" class="btn btn-rounded btn-xs btn-info m-r-5" data-toggle="tooltip"
                                                data-original-title="Order Status"
                                                onclick="event.preventDefault(); if(confirm('Are You Sure ?')) document.getElementById('pending-status-form-{{ $item->id }}').submit();">
                                                    Compeleted
                                                </a>
                                                <form id="pending-status-form-{{ $item->id }}" action="{{route('pending.status',$item->id)}}" method="post">
                                                @csrf
                                                @method('put')
                                                </form>
                                            @endif
                                        </td>

                                        <td class="status">
                                            @if($item->delivery_status == 0)

                                                <a href="" class="btn btn-rounded btn-xs btn-danger m-r-5" data-toggle="tooltip"
                                                data-original-title="Delivery Status"
                                                onclick="event.preventDefault(); if(confirm('Are You Sure ?')) document.getElementById('delivery-status-form-{{ $item->id }}').submit();">
                                                    Not Delivered
                                                </a>
                                                <form id="delivery-status-form-{{ $item->id }}" action="{{route('delivery.status',$item->id)}}" method="post">
                                                @csrf
                                                @method('put')
                                                </form>


                                            @else
                                                <a href="" class="btn btn-rounded btn-xs btn-info m-r-5" data-toggle="tooltip"
                                                data-original-title="Delivery Status"
                                                onclick="event.preventDefault(); if(confirm('Are You Sure ?')) document.getElementById('delivery-status-form-{{ $item->id }}').submit();">
                                                    Delivered
                                                </a>
                                                <form id="delivery-status-form-{{ $item->id }}" action="{{route('delivery.status',$item->id)}}" method="post">
                                                @csrf
                                                @method('put')
                                                </form>
                                            @endif
                                        </td>

                                        <td class="status">
                                            @if($item->pay_status == 0)
                                                <a href="" class="btn btn-rounded btn-xs btn-danger m-r-5" data-toggle="tooltip"
                                                data-original-title="Order Status"
                                                onclick="event.preventDefault(); if(confirm('Are You Sure ?')) document.getElementById('pay-status-form-{{ $item->id }}').submit();">
                                                    UnPaid
                                                </a>
                                                <form id="pay-status-form-{{ $item->id }}" action="{{route('order.pay.status',$item->id)}}" method="post">
                                                @csrf
                                                @method('put')
                                                </form>
                                            @else
                                                <span class="btn btn-rounded btn-xs btn-info m-r-5">Paid</span>
                                            @endif
                                        </td>

                                        <td>
                                            <span class="btn btn-rounded btn-xs btn-info m-r-5">Cash On Delivery</span>
                                        </td>

                                        <td>
                                            <a href="{{ route('order.show',$item->pid) }}">
                                                <span class="btn btn-rounded btn-xs btn-info m-r-5">Show <i class="fa fa-eye"></i></span>
                                            </a>
                                        </td>

                                        <div class="modal fade" id="exampleModalLong{{ $index }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLong{{ $index }}Title" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLong{{ $index }}Title">Product List</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @foreach ($item->orderProductLists as $prod)
                                                            <a href="{{ route('product.show',$prod->product->id) }}" target="blank">
                                                                <div>
                                                                    {{ $prod->product->name }}  x {{ $prod->quantity }} =
                                                                    Rs. {{ $prod->product->price * $prod->quantity}}
                                                                </div>
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="billingInfo{{ $index }}" tabindex="-1" role="dialog" aria-labelledby="billingInfo{{ $index }}Title" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="billingInfo{{ $index }}Title">Shipping Address</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                              @foreach ([['name','Name'],['email','Email'],['address','Address'],['city','City'],['house_no','House Number'],['street','Street']] as $index => $title)
                                                                @if($item->shippingAddress->{$title[0]})
                                                                    <div class=col-md-6>
                                                                        <code>{{ $title[1] }} : </code>
                                                                        <p>{{ $item->shippingAddress->{$title[0]} ?? '' }} </p>
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
                    </div>


            </div>
        </div>
    {{-- </div>
</div> --}}



    </div>
@endsection
