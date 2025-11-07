<div>
    @if($detail)
            <form wire:submit.prevent="cartSubmit">
            <div class="d-flex align-items-center gap-2">

                <div class="input-group input-group-sm" style="width: 120px;">
                    <button type="button" class="btn btn-outline-primary" wire:click="decreaseQty">
                        <i class="fa fa-minus"></i>
                    </button>

                    <input type="number" class="form-control text-center"
                           min="1" max="5" wire:model="quantity">

                    <button type="button" class="btn btn-outline-primary" wire:click="increaseQty">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>

                <button type="submit" class="btn btn-sm btn-primary d-flex align-items-center">
                    <i class="bi bi-cart-plus me-1"></i> Add to Cart
                </button>
            </div>
        </form>
    @else
        <button wire:click="addToCart({{ $product->id }})"
                class="btn btn-sm btn-primary d-flex align-items-center">
            <i class="bi bi-cart-plus me-1"></i> Add to Cart
        </button>
    @endif
</div>
