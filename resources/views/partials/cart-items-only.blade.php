@forelse($globalCartItems as $cart)
<div class="cart-item" data-cart-id="{{ $cart->id }}">
    <img src="{{ asset($cart->product->mainImage->image_path) }}" class="cart-item-image">
    <div class="cart-item-content">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h5 class="cart-item-title">{{ $cart->product->name }}</h5>
                <div class="cart-item-category">{{ strtoupper($cart->product->category->name) }}</div>
                <div class="cart-item-price">Rp {{ number_format($cart->product->price, 0, ',', '.') }}</div>
                <div class="cart-qty-wrapper">
                    <form action="{{ route('cart.decrease', $cart->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="button" class="cart-qty-btn decrease-btn" data-id="{{ $cart->id }}">-</button>
                    </form>
                    <input type="text" value="{{ $cart->quantity }}" class="cart-qty-input" readonly id="cartQty{{ $cart->id }}">
                    <form action="{{ route('cart.increase', $cart->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="button" class="cart-qty-btn increase-btn" data-id="{{ $cart->id }}">+</button>
                    </form>
                </div>
            </div>
            <form action="{{ route('cart.delete', $cart->id) }}" method="POST">
                @csrf @method('DELETE')
                <button type="button" class="remove-item-btn delete-btn" data-id="{{ $cart->id }}">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </form>
        </div>
    </div>
</div>
@empty
<div class="text-center py-5 text-secondary">Your collection is empty</div>
@endforelse