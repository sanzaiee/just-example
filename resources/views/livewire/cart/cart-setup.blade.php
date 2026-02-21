<div>
    {{-- @if ($detail)
        <form wire:submit.prevent="cartSubmit">
            <div class="d-flex align-items-center gap-2">

                <div class="input-group input-group-sm" style="width: 120px;">
                    <button type="button" class="btn btn-outline-primary" wire:click="decreaseQty">
                        <i class="fa fa-minus"></i>
                    </button>

                    <input type="number" class="form-control text-center" min="1" max="5"
                        wire:model="quantity">

                    <button type="button" class="btn btn-outline-primary" wire:click="increaseQty">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>

                <button type="submit" class="btn btn-sm btn-primary d-flex align-items-center">
                    <i class="bi bi-cart-plus me-1"></i> Add to Cart
                </button>
            </div>
        </form>
    @else --}}
    <form wire:submit.prevent="cartSelectSubmit" class="d-flex flex-column gap-.5">
        @if ($selected_quantity)
            {{-- <div class="alert alert-info py-1 px-1 small mb-2" style="text-align: center">
                <p class="mb-0"><i class="bi bi-info-circle me-1"></i>
                Adding {{ $selected_quantity }} unit(s) @
                ${{ number_format($product->getPriceForQuantity($selected_quantity), 2) }}</p>
            </div> --}}
        @endif
        <div class="d-flex align-items-center gap-2 justify-content-between">
            <div class="form-group flex-grow-1">
                <select wire:model.live="selected_quantity" id="quantity"
                    class="form-select form-select-sm shadow-sm border-0 bg-light">
                    <option value="" disabled selected class="text-center">-- select quantity --</option>
                    @foreach ($prices as $price)
                        <option value="{{ $price->quantity }}" class="text-center">{{ $price->quantity }} units @ ${{ number_format($price->price, 2) }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" @disabled($prices->isEmpty() || $selected_quantity == "") class="btn btn-primary btn-sm d-flex align-items-center justify-content-center gap-2 shadow-sm px-3.5">
                <i class="fa fa-cart-plus"></i>
            </button>
        </div>

    </form>

    {{-- <button wire:click="addToCart({{ $product->id }})"
                class="btn btn-sm btn-primary d-flex align-items-center">
            <i class="bi bi-cart-plus me-1"></i> Add to Cart
            </button> --}}
    {{-- @endif --}}
</div>
