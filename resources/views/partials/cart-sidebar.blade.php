<style>
    #cartSidebar {
        width: 430px !important;
        background: #f9f9f7;
        border-left: 1px solid #ebe5de;
        padding: 22px 24px;
        display: flex;
        flex-direction: column;
        height: 100vh;
    }
    .cart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-shrink: 0;
    }
    .cart-title {
        font-size: 28px;
        font-weight: 700;
        color: #111827;
        margin: 0;
        letter-spacing: -1px;
    }
    .cart-close-btn {
        border: none;
        background: transparent;
        font-size: 24px;
        color: #111827;
        transition: 0.2s ease;
    }
    .cart-close-btn:hover { opacity: 0.7; }
    .offcanvas-body {
        padding: 0 !important;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        height: 100%;
    }
    .offcanvas-backdrop.show {
        opacity: 0.08;
        backdrop-filter: blur(2px);
    }
    .cart-items-wrapper {
        flex: 1;
        overflow-y: auto;
        padding-right: 4px;
        margin-bottom: 16px;
    }
    .cart-item {
        display: flex;
        gap: 16px;
        padding: 14px 0;
        border-bottom: 1px solid #eee7df;
        transition: opacity 0.3s ease;
    }
    .cart-item:first-child { padding-top: 0; }
    .cart-item:last-child { border-bottom: none; }
    .cart-item-image-wrapper {
        width: 92px;
        height: 92px;
        border-radius: 18px;
        background: #e8eeee;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        flex-shrink: 0;
    }
    .cart-item-image {
        width: 92px;
        height: 92px;
        border-radius: 18px;
        object-fit: cover;
    }
    .cart-item-content {
        flex: 1;
        padding-top: 2px;
    }
    .cart-item-title {
        font-size: 16px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 6px;
        line-height: 1.3;
    }
    .cart-item-category {
        font-size: 11px;
        letter-spacing: 2px;
        font-weight: 700;
        color: #666;
        margin-bottom: 10px;
    }
    .cart-item-price {
        font-size: 15px;
        font-weight: 700;
        color: #111827;
        padding-bottom: 4px;
    }
    .cart-footer {
        border-top: 1px solid #ebe5de;
        padding-top: 18px;
        padding-bottom: 6px;
        flex-shrink: 0;
        background: #f9f9f7;
    }
    .cart-total-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
    }
    .cart-total-label {
        font-size: 16px;
        letter-spacing: 3px;
        font-weight: 700;
        color: #666;
    }
    .cart-total-price {
        font-size: 20px;
        font-weight: 700;
        color: #111827;
        transition: all 0.3s ease;
    }
    .checkout-btn {
        width: 100%;
        height: 58px;
        border-radius: 18px;
        border: none;
        background: #111827;
        color: white;
        font-size: 17px;
        font-weight: 600;
        transition: 0.2s ease;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }
    .checkout-btn:hover { opacity: 0.92; color: white; }
    .checkout-btn.empty-shake {
        background: #c45555 !important;
        animation: shakeBtn 0.4s ease;
    }
    @keyframes shakeBtn {
        0%, 100% { transform: translateX(0); }
        20% { transform: translateX(-6px); }
        40% { transform: translateX(6px); }
        60% { transform: translateX(-4px); }
        80% { transform: translateX(4px); }
    }
    .cart-items-wrapper::-webkit-scrollbar { width: 5px; }
    .cart-items-wrapper::-webkit-scrollbar-thumb { background: #999; border-radius: 20px; }
    .cart-qty-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .cart-qty-btn {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        border: none;
        background: #111827;
        color: white;
        font-size: 14px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: opacity 0.2s ease;
    }
    .cart-qty-btn:hover { opacity: 0.75; }
    .cart-qty-btn:disabled { opacity: 0.4; cursor: not-allowed; }
    .cart-qty-input {
        width: 42px;
        height: 28px;
        border-radius: 8px;
        border: 1px solid #ddd;
        background: white;
        text-align: center;
        font-size: 13px;
        font-weight: 600;
    }
    .remove-item-btn {
        border: none;
        background: transparent;
        font-size: 16px;
        color: #8b8b8b;
        transition: 0.2s ease;
        cursor: pointer;
    }
    .remove-item-btn:hover { color: #d35b5b; }

    .cart-toast{
        position: fixed;
        bottom: 28px;
        left: 50%;
        transform: translateX(-50%);
        background: #1f3117;
        color: #f4efe7;

        padding: 14px 24px;
        border-radius: 999px;

        display: flex;
        align-items: center;
        gap: 10px;

        z-index: 99999;

        opacity: 0;
        pointer-events: none;

        transition: 0.3s ease;
    }

    .cart-toast.show{
        opacity: 1;
    }
    /* RESPONSIVE */
    @media(max-width: 768px){
        #cartSidebar{
            width: 100% !important;
            padding: 18px;
        }
        .cart-title{
            font-size: 24px;
        }
        .cart-item-image{
            width: 82px;
            height: 82px;
        }
        .cart-item-title{
            font-size: 15px;
        }
        .checkout-btn{
            height: 54px;
            font-size: 15px;
        }
    }
</style>

<!-- CONFIRM POPUP -->
<div class="cart-confirm-backdrop" id="cartConfirmBackdrop">
    <div class="cart-confirm-modal">
        <div class="cart-confirm-icon"><i class="fas fa-trash-alt"></i></div>
        <h4 class="cart-confirm-title">Remove this item?</h4>
        <p class="cart-confirm-msg" id="cartConfirmMsg">This item will be removed from your collection.</p>
        <div class="cart-confirm-actions">
            <button class="cart-confirm-cancel" id="cartConfirmCancel">Keep it</button>
            <button class="cart-confirm-delete" id="cartConfirmDelete">Remove</button>
        </div>
    </div>
</div>

<!-- EMPTY CART TOAST -->
<div class="cart-empty-toast" id="cartEmptyToast">
    <i class="fas fa-shopping-bag"></i>
    <span>Add items to your collection first</span>
</div>

<!-- CART SIDEBAR -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="cartSidebar">

    <!-- HEADER -->
    <div class="cart-header">
        <h2 class="cart-title">Your Collection</h2>
        <button type="button" class="cart-close-btn" data-bs-dismiss="offcanvas">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <!-- BODY -->
    <div class="offcanvas-body pt-0">
        <div class="cart-items-wrapper" id="cartItemsWrapper">
            @forelse($globalCartItems as $cart)
            <div class="cart-item" data-cart-id="{{ $cart->id }}">
                <img
                    src="{{ asset($cart->product->mainImage->image_path) }}"
                    class="cart-item-image"
                >
                <div class="cart-item-content">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="cart-item-title">
                                {{ $cart->product->name }}
                            </h5>
                            <div class="cart-item-category">
                                {{ strtoupper($cart->product->category->name) }}
                            </div>

                            <div class="cart-item-price">
                                Rp {{ number_format($cart->product->price, 0, ',', '.') }}
                            </div>

                            <!-- QUANTITY -->
                            <div class="cart-qty-wrapper">

                                <!-- MINUS -->
                                <form action="{{ route('cart.decrease', $cart->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <button type="button" class="cart-qty-btn decrease-btn" data-id="{{ $cart->id }}">
                                        -
                                    </button>
                                </form>

                                <input
                                    type="text"
                                    value="{{ $cart->quantity }}"
                                    class="cart-qty-input"
                                    readonly
                                    id="cartQty{{ $cart->id }}"
                                >

                                <!-- PLUS -->
                                <form action="{{ route('cart.increase', $cart->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <button type="button" class="cart-qty-btn increase-btn" data-id="{{ $cart->id }}">
                                        +
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- DELETE -->
                        <form action="{{ route('cart.delete', $cart->id) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <button type="button" class="remove-item-btn delete-btn" data-id="{{ $cart->id }}">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 text-secondary" id="cartEmptyState">
                    Your collection is empty
                </div>
            @endforelse
        </div>

        <!-- FOOTER -->
        <div class="cart-footer">
            <div class="cart-total-wrapper">
                <div class="cart-total-label">
                    TOTAL VALUE
                </div>
                <div class="cart-total-price" id="cartTotalPrice">
                    Rp {{ number_format($globalCartItems->sum(fn($item) => $item->product->price * $item->quantity), 0, ',', '.') }}
                </div>
            </div>
        </div>

        <!-- CHECKOUT BUTTON -->
        <button class="checkout-btn" id="checkoutBtn"
                data-url="{{ route('checkout.index') }}"
                data-empty="{{ $globalCartItems->isEmpty() ? '1' : '0' }}">
            Proceed to Checkout
        </button>
    </div>
</div>

<div class="cart-toast" id="cartToast">
    <i class="fas fa-check-circle"></i>
    <span id="cartToastText"></span>
</div>
<script>
    const cartToast = document.getElementById('cartToast');
    const cartToastText = document.getElementById('cartToastText');
    let cartToastTimer = null;
    function showCartToast(message, type = 'success') {
        const icon = cartToast.querySelector('i');
        if(type === 'delete'){ icon.className = 'fas fa-trash'; }
        else if(type === 'update'){ icon.className = 'fas fa-pen';}
        else if(type === 'warning') icon.className = 'fas fa-circle-exclamation';
        else if(type === 'wishlist') icon.className = 'fas fa-heart';
        else{ icon.className = 'fas fa-check-circle';}
    
        cartToastText.textContent = message;
        cartToast.classList.add('show');
        clearTimeout(cartToastTimer);
        cartToastTimer = setTimeout(() => {
            cartToast.classList.remove('show');
        }, 2500);
    }

    function updateCartUI(data){
        // update quantity input
        const item = document.querySelector(`[data-cart-id="${data.cart_id}"]`);
        if(item){
            const qtyInput = item.querySelector('.cart-qty-input');
            if(qtyInput){
                qtyInput.value = data.quantity;
            }
        }
        // update total
        const totalEl = document.querySelector('.cart-total-price');
        if(totalEl){
            totalEl.innerText = 'Rp ' + Number(data.total).toLocaleString('id-ID');
        }

        // remove item if deleted
        if(data.deleted){
            const card = document.querySelector(`[data-cart-id="${data.cart_id}"]`);
            if(card){ card.remove(); }
        }
    }

    window.updateCartBadge = function(count) {
        const badge = document.getElementById('cartBadge');
        if (!badge) return;
        badge.textContent = count;
        badge.style.display = count > 0 ? 'inline-flex' : 'none';
    }
    window.attachCartListeners = function() {
        // INCREASE
        document.querySelectorAll('.increase-btn').forEach(button => {
            button.addEventListener('click', function(e){
                e.preventDefault();
                const id = this.dataset.id;

                fetch(`/cart/${id}/increase`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if(!data.success){ showCartToast(data.message, 'warning'); return; }
                    document.getElementById(`cartQty${id}`).value = data.quantity;
                    document.getElementById('cartTotalPrice').innerText =
                        'Rp ' + Number(data.total).toLocaleString('id-ID');
                    updateCartBadge(data.count);
                    showCartToast('Product quantity increased', 'update');
                    // updateCartUI(data);
                });
            });
        });

        // DECREASE
        document.querySelectorAll('.decrease-btn').forEach(button => {
            button.addEventListener('click', function(e){
                e.preventDefault();
                const id = this.dataset.id;

                fetch(`/cart/${id}/decrease`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    document.getElementById(`cartQty${id}`).value = data.quantity;
                    document.getElementById('cartTotalPrice').innerText =
                        'Rp ' + Number(data.total).toLocaleString('id-ID');
                    updateCartBadge(data.count);
                    showCartToast('Product quantity decreased', 'update');
                    // updateCartUI(data);
                });
            });
        });

        // DELETE
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(e){
                e.preventDefault();
                const id = this.dataset.id;
                const cartItem = this.closest('.cart-item');
                fetch(`/cart/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    cartItem.remove();
                    // localStorage.setItem('cartOpen', 'true');
                    // document.querySelector(`.delete-btn[data-id="${id}"]`).closest('.cart-item').remove();
                    document.getElementById('cartTotalPrice').innerText =
                        'Rp ' + Number(data.total).toLocaleString('id-ID');
                    updateCartBadge(data.count);
                    showCartToast('Product deleted from cart', 'delete');
                    // updateCartUI(data);
                });
            });
        });
    };
    window.refreshCartSidebar = function() {
        fetch('/cart/sidebar', {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(res => res.json())
        .then(data => {
            document.querySelector('.cart-items-wrapper').innerHTML = data.html;
            document.getElementById('cartTotalPrice').innerText = 'Rp ' + Number(data.total).toLocaleString('id-ID');
            updateCartBadge(data.count);
            attachCartListeners();
        });
    }
</script>