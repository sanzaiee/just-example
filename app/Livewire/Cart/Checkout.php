<?php

namespace App\Livewire\Cart;

use App\Mail\SendOrderConfirmation;
use App\Models\Order;
use App\Models\OrderDeliveryAddress;
use App\Models\OrderProductList;
use App\Models\Product;
use App\Models\ShippingAddress;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;

class Checkout extends Component
{
    public $subTotal = 0;

    public $cartCount = 0;

    public $discount = 0;

    public $total = 0;

    public $pid;

    // Cache product data to avoid repeated queries
    public $products = [];

    public $cartItems = [];

    public $shippings;

    public function render()
    {
        return view('livewire.cart.checkout', [
            'cartItems' => $this->cartItems,
            'subTotal' => $this->subTotal,
            'cartCount' => $this->cartCount,
            'shippings' => $this->shippings,
        ]);
    }

    public function mount()
    {
        $this->loadCartData();
        $this->pid = 'P-id'.'-'.Str::random(5);

        $this->shippings = ShippingAddress::where('user_id', auth()->id())->get(); // auth()->id();
    }

    #[On('refreshCart')]
    public function refreshCart()
    {
        $this->loadCartData();
        $this->dispatch('cartUpdated');
    }

    /**
     * Load all cart data efficiently with eager loading
     */
    private function loadCartData(): void
    {
        $cartContent = Cart::content();

        if ($cartContent->isEmpty()) {
            $this->cartItems = [];
            $this->subTotal = 0;
            $this->cartCount = 0;

            return;
        }

        // Get all product IDs from cart
        $productIds = $cartContent->pluck('id')->unique()->toArray();

        // Eager load all products with their relationships and tiered prices
        $this->products = Product::with(['user', 'category', 'brand', 'tieredPrices'])
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        // Prepare cart items with product data and calculated prices
        $this->cartItems = [];
        $subtotal = 0;

        foreach ($cartContent as $index => $item) {
            $product = $this->products[$item->id] ?? null;

            if (! $product) {
                continue;
            }

            // Calculate price once and store it
            $unitPrice = $this->getCachedPriceForQuantity($product->id, $item->qty);
            $itemSubtotal = $unitPrice * $item->qty;

            // Use array instead of DTO for Livewire compatibility
            $this->cartItems[$index] = [
                'rowId' => $item->rowId,
                'id' => $item->id,
                'name' => $item->name,
                'slug' => $product->slug,
                'qty' => $item->qty,
                'unitPrice' => $unitPrice,
                'subtotal' => $itemSubtotal,
                'image' => $product->image,
            ];

            $subtotal += $itemSubtotal;
        }

        // Calculate totals
        $this->subTotal = $subtotal;
        $this->cartCount = count($this->cartItems);
    }

    /**
     * Cached price calculation to avoid repeated database queries
     */
    private function getCachedPriceForQuantity(int $productId, int $quantity): float
    {
        static $priceCache = [];

        $cacheKey = "{$productId}_{$quantity}";

        if (! isset($priceCache[$cacheKey])) {
            $product = $this->products[$productId] ?? null;
            if ($product) {
                $priceCache[$cacheKey] = $product->getPriceForQuantity($quantity);
            } else {
                $priceCache[$cacheKey] = 0;
            }
        }

        return $priceCache[$cacheKey];
    }

    /**
     * Update cart item with new quantity and corresponding price
     */
    private function updateCartItemPrice($rowId, $newQuantity)
    {
        $cartItem = Cart::get($rowId);
        if (! $cartItem) {
            return;
        }

        // Get the product from the database if not in cached products
        $product = $this->products[$cartItem->id] ?? null;
        if (! $product) {
            $product = Product::with(['tieredPrices'])->find($cartItem->id);
            if (! $product) {
                return;
            }
        }

        $newPrice = $product->getPriceForQuantity($newQuantity);

        // Update both quantity and price
        Cart::update($rowId, [
            'qty' => $newQuantity,
            'price' => $newPrice,
        ]);
    }

    public function decreaseQty($rowId, $qty = 1)
    {
        // Debug: Log the incoming parameters
        logger()->info('decreaseQty called', ['rowId' => $rowId, 'qty' => $qty]);

        $cartItem = Cart::get($rowId);
        if (! $cartItem) {
            logger()->warning('Cart item not found', ['rowId' => $rowId]);

            return;
        }

        $step = max(1, (int) $qty);
        $currentQty = (int) ($cartItem->qty ?? 0);

        if ($currentQty == 1) {
            $this->dispatch('alert', ['type' => 'error', 'message' => 'Minimum quantity reached!']);

            return;
        }

        if ($currentQty > 1) {
            $newQty = $currentQty - $step;
            $this->updateCartItemPrice($rowId, $newQty);
        }

        $this->refreshCart();
        $this->dispatch('alert', ['type' => 'success', 'message' => 'Item Decreased!']);
    }

    public function increaseQty($rowId, $qty = 1)
    {
        // Debug: Log the incoming parameters
        // logger()->info('increaseQty called', ['rowId' => $rowId, 'qty' => $qty]);
        logger()->info('increaseQty called', ['rowId' => $rowId]);

        $cartItem = Cart::get($rowId);
        if (! $cartItem) {
            logger()->warning('Cart item not found', ['rowId' => $rowId]);

            return;
        }

        // Enforce integer and positivity
        $step = max(1, (int) $qty);

        $currentQty = (int) ($cartItem->qty ?? 0);
        $newQty = $currentQty + $step;
        // $newQty = ++$qty;
        $this->updateCartItemPrice($rowId, $newQty);

        $this->refreshCart();
        $this->dispatch('alert', ['type' => 'success', 'message' => 'Item Increased!']);
    }

    public function removeFromCart($rowId)
    {
        // Debug: Log the incoming parameter
        logger()->info('removeFromCart called', ['rowId' => $rowId]);

        Cart::remove($rowId);
        $this->refreshCart();
        $this->dispatch('alert', ['type' => 'success', 'message' => 'Item Removed!']);
    }

    public function orderStore()
    {
        $effectiveShippingId = $this->delivery_method === 'pickup'
            ? getStorePickupShippingId()
            : $this->shipping_id;

        // Remove previous pending order with same pid
        Order::where('pid', $this->pid)
            ->where('order_status', 0)
            ->delete();

        // Create new order
        $order = Order::create([
            'pid' => $this->pid,
            'quantity' => $this->cartCount,
            'user_id' => auth()->id(),
            'amount' => $this->subTotal,
            'discount' => $this->discount,
            'is_store_pickup' => $this->delivery_method === 'pickup',
            'order_status' => 1,
            'notes' => $this->deliveryNotes ? trim($this->deliveryNotes) : null,
        ]);

        // Insert order products in bulk
        if (!empty($this->cartItems)) {
            OrderProductList::insert(
                collect($this->cartItems)->map(fn ($item) => [
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['qty'],
                    'price' => $item['unitPrice'],
                ])->toArray()
            );
        }
        
        // Use $this->shippings instead of a DB query
        $shippingAddress = $this->shippings->firstWhere('id', $effectiveShippingId);
        if($shippingAddress) { //Pickup does not shipping address
            OrderDeliveryAddress::create([
                'order_id' => $order->id,
                'name' => $shippingAddress->name,
                'email' => $shippingAddress->email,
                'address' => $shippingAddress->address,
                'street' => $shippingAddress->street,
                'city' => $shippingAddress->city,
                'tole' => $shippingAddress->tole,
                'house_no' => $shippingAddress->house_no,
                'phone' => $shippingAddress->phone,
                'description' => $shippingAddress->description,
                'postal_code' => $shippingAddress->postal_code,
            ]);
        }

        return $order;
    }

    public $delivery_method = null; // pickup or delivery

    public $shipping_id = null; // shipping_id

    public $deliveryNotes = ''; // shipping_id

    protected $rules = [
        'deliveryNotes' => 'nullable|string|max:255',
    ];

    public function updatedDeliveryMethod($value)
    {
        if ($value === 'pickup') {
            $this->shipping_id = null;
        }
    }

    public function getCanEnableCheckoutButton()
    {
        if ($this->subTotal == 0) {
            return true;
        }

        if ($this->cartCount == 0) {
            return true;
        }

        if ($this->delivery_method === 'pickup') {
            return true;
        }

        if ($this->delivery_method === 'delivery') {
            return ! empty($this->shipping_id);
        }

        return false;
    }

    public function getShippingDescriptionProperty(): ?string
    {
        if (! $this->shipping_id) {
            return null;
        }

        $shipping = $this->shippings->firstWhere('id', $this->shipping_id);

        if (! $shipping) {
            return null;
        }

        return collect([
            $shipping->house_no,
            $shipping->address,
            $shipping->city,
            // $shipping->province,
            $shipping->postal_code,
        ])
            ->filter()          // remove null / empty values
            ->implode(', ');
    }

    public bool $isProcessing = false;

    public function checkout()
    {
        if ($this->isProcessing) {
            return;
        }
        $this->isProcessing = true;

        try {
            if (! $this->getCanEnableCheckoutButton()) {
                $this->dispatch('alert', ['type' => 'error', 'message' => 'Please select delivery method and shipping address!']);

                return;
            }

            // Step 1: Place the order (database transaction)
            $order = DB::transaction(function () {
                return $this->orderStore();
            });

            // Step 2: Clear cart and reset counters
            Cart::destroy();
            $this->cartCount = 0;
            $this->subTotal = 0;

            // Step 3: Mark order as completed
            //$order->update(['order_status' => 1]); Done in orderStore

            try {
                // Step 4: Load related models for email
                $order->load('orderProductLists.product', 'orderDeliveryAddress');

                // Step 5: Send confirmation email (outside transaction)
                $this->sendOrderConfirmationEmail($order);
            } catch (Exception $e) {
                if (config('app.debug')) {
                    $this->dispatch(
                        'console-log',
                        message: 'Checkout error',
                        data: $e->getMessage()
                    );
                }
            }

            $this->dispatch('alert', ['type' => 'success', 'message' => 'Order successfully placed. Please wait for response!!!!']);

            return redirect(route('success.page', $order->pid));
        } catch (\Exception $e) {
            $this->dispatch('alert', ['type' => 'error', 'message' => 'Internal Server Error']);
            if (config('app.debug')) {
                $this->dispatch(
                    'console-log',
                    message: 'Checkout error',
                    data: $e->getMessage()
                );
            }
            return;
        } finally {
            $this->isProcessing = false;
        }
    }

    protected function sendOrderConfirmationEmail(Order $order)
    {
        try {
            $email = auth()->user()->email ?? '';
            Mail::to($email)->send(new SendOrderConfirmation($order));
        } catch (\Exception $e) {
            // Log email failure but don't block the order
            logger()->error("Order confirmation email failed for Order ID {$order->id}: {$e->getMessage()}");
        }
    }

}
