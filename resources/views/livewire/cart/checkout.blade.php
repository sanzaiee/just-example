<div>
    <div class="row">
        <div class="col-md-8">
            <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            @forelse ($list as $item)
                            @php
                                $prod = App\Models\Product::with(['user','category','brand'])->find($item->id);
                            @endphp
                                <tr class="">
                                    <td class="d-flex flex-column">
                                        <a href="{{ route('product.show',$item->id) }}" >
                                            <img src="{{ $prod->image }}"
                                                class="rounded float-start img-thumbnail blur-up lazyload" alt="" width="100px" height="100px">
                                        </a>
                                        <a href="{{ route('product.show',$item->id) }}" class="text-center">{{ $item->name }}</a>
                                    </td>

                                    <td class="price">
                                        <h6 class="fw-bold">Price</h6>
                                        <p>Rs. {{ $prod->price }} <del class="text-danger"> Rs. {{ $prod->strike_price }}</del></p>
                                    </td>

                                    <td class="quantity">
                                        <h6 class="table-title text-content">Qty</h6>
                                        <div class="input-group input-group-sm" style="width: 120px;">
                                            <button type="button" class="btn btn-outline-primary" wire:click="decreaseQty({{ $loop->index }},{{ $item->qty }})">
                                                <i class="fa fa-minus"></i>
                                            </button>

                                            <input type="number" class="form-control text-center"
                                                min="1" max="5" wire:model="quantity" value="{{ $item->qty }}">

                                            <button type="button" class="btn btn-outline-primary" wire:click="increaseQty({{ $loop->index }},{{ $item->qty }})">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>

                                    </td>
                                    {{-- <td>
                                        <h4 class="table-title text-content">Notes</h4>

                                    {{ $prod->short}}
                                    </td> --}}

                                    <td class="subtotal">
                                        <h6 class="fw-bold">Sub Total</h6>
                                        <p>{{ $prod->price * $item->qty }}</p>
                                    </td>

                                    <td class="save-remove">
                                        <a class="btn btn-outline-danger btn-xs text-danger" wire:click="removeFromCart({{ $loop->index }})">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty

                            @endforelse
                        </tbody>
                    </table>
                </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3">
                <div class="card-title"><h5>Summary</h5></div>
                <div class="card-body">
                    <td class="subtotal">
                        <h6 class="fw-bold">Total</h6>
                        <p>Rs. {{ $subTotal }}</p>
                    </td>
                    <hr>
                    {{-- <td class="subtotal">
                        <h6 class="fw-bold">Total</h6>
                        <p>{{ $total }}</p>
                    </td> --}}

                    <button class="btn btn-outline-primary float-end" wire:click="checkout">Checkout</button>
                </div>
            </div>
        </div>
    </div>

</div>
