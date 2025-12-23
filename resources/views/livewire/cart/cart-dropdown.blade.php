<div class="cart-dropdown-wrapper" wire:target="increase,decrease,clearCart,removeProductCart">
    <div class="mobile-backdrop" id="mobileBackdrop" onclick="toggleDropdown()"></div>
    <div class="cart-button" onclick="toggleDropdown()">
        <i class="cart-icon">🛒</i>
        <span class="cart-text">Cart</span>
        <span class="cart-count">({{ count($items) }})</span>
        @if (count($items) > 0)
            <span class="cart-badge">{{ count($items) }}</span>
        @endif
    </div>

    <div class="cart-dropdown" id="cartDropdown">
        <div class="cart-header">
            <h3>Shopping Cart</h3>
            @if (count($items) > 0)
                <button class="btn btn-sm btn-outline-danger" wire:click="clearCart" onclick="event.stopPropagation()">
                    Clear
                </button>
            @endif
        </div>

        <div class="cart-items">
            @forelse($items as $item)
                <div class="cart-item">
                    <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="item-image">
                    <div class="item-details">
                        <h4>{{ $item['name'] }}</h4>
                        <div class="item-price">
                            <span class="price">${{ number_format($item['price'], 2) }}</span>
                            <span class="quantity">× {{ $item['qty'] }}</span>
                        </div>
                        <div class="item-subtotal">
                            <small>Subtotal:</small>
                            <span class="subtotal">${{ number_format($item['subtotal'], 2) }}</span>
                        </div>
                    </div>
                    <button class="btn btn-sm btn-outline-danger" wire:click="removeProductCart('{{ $item['rowId'] }}')"
                        onclick="event.stopPropagation()">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            @empty
                <div class="empty-cart">
                    <div class="empty-icon">🛒</div>
                    <p>Your cart is empty</p>
                    <small>Add items to get started</small>
                </div>
            @endforelse
        </div>

        @if (count($items) > 0)
            <div class="cart-footer">
                <div class="cart-total">
                    <span>Total:</span>
                    <span class="total-amount">${{ number_format($total, 2) }}</span>
                </div>
                <div class="cart-actions">
                    <button class="checkout-btn" wire:click="goToCheckoutPage" onclick="event.stopPropagation()">
                        Checkout
                    </button>
                    <button class="continue-btn" onclick="event.stopPropagation(); toggleDropdown()">
                        Continue Shopping
                    </button>
                </div>
            </div>
        @endif
    </div>

    <style>
        .cart-dropdown-wrapper {
            position: relative;
            display: inline-block;
        }

        .cart-button {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: white;
            border: 2px solid #685DD8;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            color: #685DD8;
            transition: all 0.2s ease;
            position: relative;
        }

        .cart-button:hover {
            background: #685DD8;
            color: white;
        }

        .cart-icon {
            font-size: 16px;
        }

        .cart-text {
            font-weight: 500;
        }

        .cart-count {
            font-weight: 500;
        }

        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: bold;
        }

        .cart-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            width: 320px;
            max-width: 90vw;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            display: none;
            animation: slideDown 0.2s ease-out;
        }

        /* Mobile dropdown positioning */
        @media (max-width: 768px) {
            .mobile-backdrop {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
            }

            .mobile-backdrop.show {
                display: block;
            }

            .cart-dropdown {
                position: fixed;
                top: 20px;
                left: 50%;
                transform: translateX(-50%);
                width: 95vw;
                max-width: 95vw;
                max-height: calc(100vh - 40px);
                height: auto;
            }

            .cart-dropdown.show {
                display: block !important;
            }
        }

        .cart-dropdown.show {
            display: block;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .cart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px;
            border-bottom: 1px solid #dee2e6;
            background: #f8f9fa;
        }

        .cart-header h3 {
            margin: 0;
            font-size: 16px;
            color: #685DD8;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .cart-header h3::before {
            content: "🛒";
        }

        .clear-btn {
            background: transparent;
            border: 1px solid #dc3545;
            color: #dc3545;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.2s ease;
        }

        .clear-btn:hover {
            background: #dc3545;
            color: white;
        }

        .cart-items {
            max-height: 300px;
            overflow-y: auto;
        }

        .cart-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px;
            border-bottom: 1px solid #dee2e6;
            transition: background 0.2s ease;
        }

        .cart-item:hover {
            background: #f8f9fa;
        }

        .item-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #dee2e6;
        }

        .item-details {
            flex: 1;
            min-width: 0;
        }

        .item-details h4 {
            margin: 0 0 8px 0;
            font-size: 14px;
            font-weight: 600;
            color: #333;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .item-price {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
        }

        .price {
            font-weight: 600;
            color: #685DD8;
            font-size: 13px;
        }

        .quantity {
            background: #e9ecef;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 12px;
            color: #495057;
        }

        .item-subtotal {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 8px;
            border-top: 1px solid #e9ecef;
        }

        .item-subtotal small {
            color: #6c757d;
            font-size: 12px;
        }

        .subtotal {
            font-weight: 600;
            color: #28a745;
            font-size: 14px;
        }

        .remove-btn {
            background: transparent;
            border: none;
            color: #dc3545;
            cursor: pointer;
            font-size: 18px;
            padding: 4px;
            border-radius: 4px;
            transition: all 0.2s ease;
            opacity: 0.7;
        }

        .remove-btn:hover {
            opacity: 1;
            background: #f8d7da;
        }

        .empty-cart {
            text-align: center;
            padding: 40px 20px;
        }

        .empty-icon {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .empty-cart p {
            margin: 0 0 8px 0;
            font-weight: 500;
            color: #495057;
        }

        .empty-cart small {
            color: #6c757d;
        }

        .cart-footer {
            padding: 16px;
            border-top: 1px solid #dee2e6;
            background: #f8f9fa;
        }

        .cart-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .cart-total span:first-child {
            font-weight: 600;
            color: #333;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .cart-total span:first-child::before {
            content: "🧾";
        }

        .total-amount {
            font-weight: 700;
            color: #685DD8;
            font-size: 18px;
        }

        .cart-actions {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .checkout-btn {
            background: #685DD8;
            color: white;
            border: none;
            padding: 12px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s ease;
        }

        .checkout-btn::before {
            content: "🛍️";
        }

        .checkout-btn:hover {
            background: #0056b3;
        }

        .continue-btn {
            background: transparent;
            color: #6c757d;
            border: 1px solid #6c757d;
            padding: 10px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s ease;
        }

        .continue-btn::before {
            content: "←";
        }

        .continue-btn:hover {
            background: #6c757d;
            color: white;
        }

        /* Very Small Mobile (320px and below) */
        @media (max-width: 320px) {
            .cart-button {
                padding: 6px 8px;
                font-size: 12px;
                gap: 4px;
            }

            .cart-icon {
                font-size: 14px;
            }

            .cart-text {
                display: none;
                /* Hide text on very small screens */
            }

            .cart-count {
                font-size: 12px;
            }

            .cart-dropdown {
                width: 95vw;
                max-width: 95vw;
            }

            .cart-items {
                max-height: 200px;
            }

            .cart-item {
                padding: 8px;
                gap: 8px;
            }

            .item-image {
                width: 35px;
                height: 35px;
            }

            .item-details h4 {
                font-size: 12px;
                line-height: 1.3;
            }

            .price {
                font-size: 11px;
            }

            .quantity {
                font-size: 10px;
                padding: 1px 4px;
            }

            .subtotal {
                font-size: 12px;
            }

            .cart-header {
                padding: 12px;
            }

            .cart-header h3 {
                font-size: 13px;
            }

            .clear-btn {
                padding: 4px 8px;
                font-size: 10px;
            }

            .cart-footer {
                padding: 12px;
            }

            .cart-total span:first-child {
                font-size: 13px;
            }

            .total-amount {
                font-size: 14px;
            }

            .checkout-btn,
            .continue-btn {
                padding: 10px 12px;
                font-size: 12px;
            }

            .remove-btn {
                font-size: 16px;
                padding: 2px;
            }

            .empty-cart {
                padding: 30px 15px;
            }

            .empty-icon {
                font-size: 36px;
            }

            .empty-cart p {
                font-size: 13px;
            }

            .empty-cart small {
                font-size: 11px;
            }
        }

        /* Mobile Responsive */
        @media (max-width: 576px) {
            .cart-dropdown {
                width: 90vw;
            }

            .cart-items {
                max-height: 250px;
            }

            .cart-item {
                padding: 12px;
            }

            .item-image {
                width: 40px;
                height: 40px;
            }

            .cart-header h3,
            .cart-total span:first-child {
                font-size: 14px;
            }

            .total-amount {
                font-size: 16px;
            }
        }

        @media (max-width: 768px) {
            .cart-dropdown {
                width: 85vw;
            }
        }

        /* Touch-friendly */
        @media (hover: none) and (pointer: coarse) {
            .cart-button {
                min-height: 44px;
                min-width: 44px;
            }

            .cart-item {
                padding: 20px;
            }

            .remove-btn {
                min-width: 44px;
                min-height: 44px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }

        /* Custom scrollbar */
        .cart-items::-webkit-scrollbar {
            width: 6px;
        }

        .cart-items::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .cart-items::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .cart-items::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('cartDropdown');
            const backdrop = document.getElementById('mobileBackdrop');
            const isShowing = dropdown.classList.contains('show');

            if (isShowing) {
                dropdown.classList.remove('show');
                backdrop.classList.remove('show');
            } else {
                dropdown.classList.add('show');
                backdrop.classList.add('show');
            }

            // Close dropdown when clicking outside (only for desktop)
            if (window.innerWidth > 768) {
                document.addEventListener('click', function closeDropdown(e) {
                    if (!e.target.closest('.cart-dropdown-wrapper')) {
                        dropdown.classList.remove('show');
                        backdrop.classList.remove('show');
                        document.removeEventListener('click', closeDropdown);
                    }
                });
            }
        }
    </script>
</div>
