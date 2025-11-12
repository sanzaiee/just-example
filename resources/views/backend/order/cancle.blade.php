@extends('backend.master')
@section('content')
<div class="container-fluid">
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h4 class="text-themecolor">Cancelled Order List </h4>
    </div>
    <div class="col-md-7 align-self-center text-right">
        <div class="d-flex justify-content-end align-items-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard',Auth::user()->name) }}">Home</a></li>
                {{-- <li class="breadcrumb-item active">All Content </li> --}}
            </ol>
            {{-- <a href="{{ route('product.create') }}"> <button type="button" class="btn btn-info d-none d-lg-block m-l-15"><i
                class="fa fa-plus-circle"></i> Product</button> </a> --}}
        </div>
    </div>
</div>

    <div class="row list-of-items">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Order List</h4>
                    <h6 class="card-subtitle">
                        @include('message')
                    </h6>
                    <div class="table-responsive m-t-40">
                        <table id="myTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="order-id">Order ID</th>

                                    <th class="Date">
                                        User
                                    </th>

                                    <th class="product-name">
                                        <span class="nobr">Products</span>
                                    </th>

                                    <th class="Date">
                                        Reason
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
                                        Pay Status
                                    </th>

                                    <th class="Status">
                                        Payment Type
                                    </th>

                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($order as $index => $item)
                                    <tr>
                                        <td class="order-id">
                                            {{ $item->pid }}
                                        </td>

                                        <td>
                                            {{ $item->user->name }}
                                        </td>

                                        <td class="product-name">
                                            <button type="button" class="btn btn-rounded btn-xs btn-info m-r-5" data-toggle="modal" data-target="#exampleModalLong{{ $index }}">
                                                <i class="fa fa-eye"></i> Product list
                                            </button>
                                        </td>


                                        <td class="product-name">
                                            <button type="button" class="btn btn-rounded btn-xs btn-info m-r-5" data-toggle="modal" data-target="#reason{{ $index }}">
                                                <i class="fa fa-eye"></i> Reason
                                            </button>
                                        </td>



                                        <td class="product-price">
                                            {{$item->amount}}
                                        </td>

                                        <td class="issue-date">
                                            {{ $item->created_at->format('Y-m-d') }}
                                        </td>



                                        <td class="status">
                                            @if($item->pay_status == 0)
                                                <span class="text-danger">Not Paid</span>
                                            @else
                                                <span class="text-success">Paid</span>
                                            @endif
                                        </td>

                                        @if($item->cod == 1)
                                            <td>
                                                <span class="text-success">Cash On Delivery</span>
                                            </td>
                                        @endif

                                        @if($item->esewa == 1)
                                            <td>
                                                <span class="text-success">Esewa/span>
                                            </td>
                                        @endif

                                        @if($item->khalti == 1)
                                            <td>
                                                <span class="text-success">Khalti</span>
                                            </td>
                                        @endif

                                        <div class="modal fade" id="reason{{ $index }}" tabindex="-1" role="dialog" aria-labelledby="reason{{ $index }}Title" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                              <div class="modal-content">
                                                <div class="modal-header">
                                                  <h5 class="modal-title" id="reason{{ $index }}Title"> Reason</h5>
                                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                  </button>
                                                </div>
                                                    <div class="modal-body">
                                                       <textarea name="" id="" cols="65" rows="10" readonly>{{ $item->ordercancel->reason }}</textarea>

                                                    </div>
                                                </div>
                                            </div>
                                          </div>



                                        <div class="modal fade" id="exampleModalLong{{ $index }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLong{{ $index }}Title" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                              <div class="modal-content">
                                                <div class="modal-header">
                                                  <h5 class="modal-title" id="exampleModalLong{{ $index }}Title"> Product List</h5>
                                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                  </button>
                                                </div>
                                                    <div class="modal-body">
                                                        @foreach ($item->order_product_lists as $prod)
                                                        <li>{{ $prod->product->name }}  x {{ $prod->quantity }}</li>
                                                    @endforeach

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
    </div>
</div>
</div>

@endsection





