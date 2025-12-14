<div>
    <div class="row">
        <div class="col-md-8">
            <div class="table-responsive">
                <table class="table">
                    <tbody>
                        @forelse ($cartItems as $item)
                            <tr class="">
                                <td
                                    class="d-flex flex-column align-items-center justify-content-center text-center text-nowrap">
                                    <a href="{{ route('product.detail', $item['slug']) }}">
                                        <img src="{{ $item['image'] }}"
                                            class="rounded float-start img-thumbnail blur-up lazyload" alt=""
                                            width="100px" height="100px">
                                    </a>
                                    <a href="{{ route('product.detail', $item['slug']) }}"
                                        class="text-decoration-none text-dark fw-bold mt-2">{{ $item['name'] }}</a>
                                </td>

                                <td class="price">
                                    <h6 class="fw-bold">Price</h6>
                                    <p>Rs. {{ number_format($item['unitPrice'], 2) }}</p>
                                </td>

                                <td class="quantity">
                                    <h6 class="table-title text-content" style="text-align: center">Qty</h6>
                                    <div class="input-group input-group-sm">
                                        <button type="button" class="btn btn-outline-primary"
                                            wire:click="decreaseQty('{{ $item['rowId'] }}', {{ $item['qty'] }})">
                                            <i class="fa fa-minus"></i>
                                        </button>

                                        <input type="number" class="form-control text-center" min="1"
                                            max="5" wire:model="quantity" value="{{ $item['qty'] }}" readonly>

                                        <button type="button" class="btn btn-outline-primary"
                                            wire:click="increaseQty('{{ $item['rowId'] }}', {{ $item['qty'] }})">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </td>

                                <td class="subtotal">
                                    <h6 class="fw-bold">Sub Total</h6>
                                    <p>Rs. {{ number_format($item['subtotal'], 2) }}</p>
                                </td>

                                <td class="save-remove">
                                    <button class="btn btn-outline-danger btn-xs text-danger"
                                        wire:click="removeFromCart('{{ $item['rowId'] }}')">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <p class="text-muted">Your cart is empty</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3">
                <div class="card-title">
                    <h5>Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0">Total Items:</h6>
                        <span class="badge bg-primary">{{ $cartCount }}</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0">Subtotal:</h6>
                        <p class="mb-0 fw-bold">Rs. {{ number_format($subTotal, 2) }}</p>
                    </div>

                    @if ($discount > 0)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0">Discount:</h6>
                            <p class="mb-0 text-danger">-Rs. {{ number_format($discount, 2) }}</p>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0">Total:</h6>
                            <p class="mb-0 fw-bold text-success">Rs. {{ number_format($subTotal - $discount, 2) }}</p>
                        </div>
                    @endif

                    <hr>

                    @if ($cartCount > 0)
                        <button class="btn btn-primary w-100" wire:click="checkout">
                            <i class="fa fa-lock me-2"></i>Proceed to Checkout
                        </button>
                    @else
                        <button class="btn btn-secondary w-100" disabled>
                            <i class="fa fa-shopping-cart me-2"></i>Cart is Empty
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
