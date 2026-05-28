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

    /* ===== CONFIRM POPUP ===== */
    .cart-confirm-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(17, 24, 39, 0.45);
        backdrop-filter: blur(4px);
        z-index: 1400;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s;
    }
    .cart-confirm-backdrop.show {
        opacity: 1;
        visibility: visible;
    }
    .cart-confirm-modal {
        background: #f9f9f7;
        border-radius: 24px;
        padding: 36px 32px;
        max-width: 360px;
        width: 90%;
        text-align: center;
        transform: scale(0.92) translateY(12px);
        transition: transform 0.35s cubic-bezier(0.22, 1, 0.36, 1);
        box-shadow: 0 24px 60px rgba(0,0,0,0.18);
    }
    .cart-confirm-backdrop.show .cart-confirm-modal {
        transform: scale(1) translateY(0);
    }
    .cart-confirm-icon {
        font-size: 28px;
        color: #c45555;
        margin-bottom: 14px;
    }
    .cart-confirm-title {
        font-size: 17px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 6px;
        letter-spacing: -0.02em;
    }
    .cart-confirm-msg {
        font-size: 13px;
        color: #666;
        margin-bottom: 24px;
        line-height: 1.5;
    }
    .cart-confirm-actions {
        display: flex;
        gap: 10px;
    }
    .cart-confirm-cancel {
        flex: 1;
        background: transparent;
        border: 1px solid #ddd;
        color: #111827;
        padding: 11px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .cart-confirm-cancel:hover { background: #f0eeeb; }
    .cart-confirm-delete {
        flex: 1;
        background: #c45555;
        border: none;
        color: white;
        padding: 11px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .cart-confirm-delete:hover { background: #a83c3c; }

    /* ===== EMPTY CART TOAST ===== */
    .cart-empty-toast {
        position: fixed;
        bottom: 28px;
        left: 50%;
        transform: translateX(-50%) translateY(80px);
        background: #111827;
        color: white;
        padding: 14px 24px;
        border-radius: 999px;
        font-size: 14px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 10px;
        z-index: 1500;
        opacity: 0;
        transition: transform 0.4s cubic-bezier(0.22, 1, 0.36, 1), opacity 0.4s ease;
        box-shadow: 0 12px 32px rgba(0,0,0,0.2);
        white-space: nowrap;
    }
    .cart-empty-toast.show {
        transform: translateX(-50%) translateY(0);
        opacity: 1;
    }
    .cart-empty-toast i { color: #f59e0b; }

    @media(max-width: 768px) {
        #cartSidebar { width: 100% !important; padding: 18px; }
        .cart-title { font-size: 24px; }
        .cart-item-image { width: 82px; height: 82px; }
        .cart-item-title { font-size: 15px; }
        .checkout-btn { height: 54px; font-size: 15px; }
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
                <div class="cart-item" id="cart-item-{{ $cart->id }}">
                    <div class="cart-item-image-wrapper">
                        <img src="{{ asset($cart->product->mainImage->image_path) }}"
                             class="cart-item-image" alt="{{ $cart->product->name }}">
                    </div>
                    <div class="cart-item-content">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="cart-item-title">{{ $cart->product->name }}</h5>
                                <div class="cart-item-category">{{ strtoupper($cart->product->category->name) }}</div>
                                <div class="cart-item-price" id="price-{{ $cart->id }}">
                                    Rp {{ number_format($cart->product->price, 0, ',', '.') }}
                                </div>
                                <div class="cart-qty-wrapper">
                                    <button class="cart-qty-btn decrease-btn"
                                            data-id="{{ $cart->id }}"
                                            data-price="{{ $cart->product->price }}">−</button>
                                    <input type="text"
                                           value="{{ $cart->quantity }}"
                                           class="cart-qty-input"
                                           id="qty-{{ $cart->id }}"
                                           readonly>
                                    <button class="cart-qty-btn increase-btn"
                                            data-id="{{ $cart->id }}"
                                            data-price="{{ $cart->product->price }}">+</button>
                                </div>
                            </div>
                            <button class="remove-item-btn delete-btn"
                                    data-id="{{ $cart->id }}"
                                    data-name="{{ $cart->product->name }}">
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
                <div class="cart-total-label">TOTAL VALUE</div>
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

<script>
(function () {
    const CSRF = '{{ csrf_token() }}';
    let cartTotal = {{ $globalCartItems->sum(fn($item) => $item->product->price * $item->quantity) }};
    let itemCount = {{ $globalCartItems->count() }};

    // ===== CONFIRM POPUP =====
    let confirmCb = null;
    const backdrop = document.getElementById('cartConfirmBackdrop');

    function showConfirm(name, onConfirm) {
        document.getElementById('cartConfirmMsg').textContent = `"${name}" will be removed from your collection.`;
        backdrop.classList.add('show');
        confirmCb = onConfirm;
    }

    document.getElementById('cartConfirmDelete').addEventListener('click', () => {
        backdrop.classList.remove('show');
        if (confirmCb) { confirmCb(); confirmCb = null; }
    });

    document.getElementById('cartConfirmCancel').addEventListener('click', () => {
        backdrop.classList.remove('show');
        confirmCb = null;
    });

    backdrop.addEventListener('click', (e) => {
        if (e.target === backdrop) { backdrop.classList.remove('show'); confirmCb = null; }
    });

    // ===== EMPTY CART TOAST =====
    let emptyToastTimer = null;
    function showEmptyToast() {
        const t = document.getElementById('cartEmptyToast');
        t.classList.add('show');
        clearTimeout(emptyToastTimer);
        emptyToastTimer = setTimeout(() => t.classList.remove('show'), 3000);
    }

    // ===== UPDATE TOTAL UI =====
    function updateTotal() {
        const fmt = new Intl.NumberFormat('id-ID').format(cartTotal);
        document.getElementById('cartTotalPrice').textContent = 'Rp ' + fmt;

        const checkoutBtn = document.getElementById('checkoutBtn');
        checkoutBtn.setAttribute('data-empty', itemCount === 0 ? '1' : '0');

        if (itemCount === 0) {
            const wrapper = document.getElementById('cartItemsWrapper');
            wrapper.innerHTML = '<div class="text-center py-5 text-secondary">Your collection is empty</div>';
        }

        // Update cart badge di navbar
        const badge = document.querySelector('.position-absolute.badge');
        if (badge) badge.textContent = itemCount;
    }

    // ===== INCREASE =====
    document.querySelectorAll('.increase-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const id = this.dataset.id;
            const price = parseFloat(this.dataset.price);
            const qtyEl = document.getElementById('qty-' + id);

            this.disabled = true;

            fetch(`/cart/${id}/increase`, {
                method: 'PATCH',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(() => {
                const newQty = parseInt(qtyEl.value) + 1;
                qtyEl.value = newQty;
                cartTotal += price;
                updateTotal();
            })
            .finally(() => { this.disabled = false; });
        });
    });

    // ===== DECREASE =====
    document.querySelectorAll('.decrease-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const id = this.dataset.id;
            const price = parseFloat(this.dataset.price);
            const qtyEl = document.getElementById('qty-' + id);
            const currentQty = parseInt(qtyEl.value);

            if (currentQty <= 1) return;

            this.disabled = true;

            fetch(`/cart/${id}/decrease`, {
                method: 'PATCH',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(() => {
                const newQty = currentQty - 1;
                qtyEl.value = newQty;
                cartTotal -= price;
                updateTotal();
            })
            .finally(() => { this.disabled = false; });
        });
    });

    // ===== DELETE =====
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const id = this.dataset.id;
            const name = this.dataset.name;
            const itemEl = document.getElementById('cart-item-' + id);
            const qty = parseInt(document.getElementById('qty-' + id)?.value || 1);
            const price = parseFloat(
                document.querySelector(`.decrease-btn[data-id="${id}"]`)?.dataset.price || 0
            );

            showConfirm(name, () => {
                fetch(`/cart/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
                })
                .then(r => r.json())
                .then(() => {
                    if (itemEl) {
                        itemEl.style.opacity = '0';
                        itemEl.style.transform = 'translateX(20px)';
                        itemEl.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                        setTimeout(() => itemEl.remove(), 300);
                    }
                    cartTotal -= price * qty;
                    itemCount--;
                    updateTotal();
                });
            });
        });
    });

    // ===== CHECKOUT BUTTON =====
    document.getElementById('checkoutBtn').addEventListener('click', function () {
        const isEmpty = this.getAttribute('data-empty') === '1';
        if (isEmpty) {
            showEmptyToast();
            this.classList.add('empty-shake');
            setTimeout(() => this.classList.remove('empty-shake'), 600);
            return;
        }
        window.location.href = this.dataset.url;
    });

    // ===== REOPEN SIDEBAR AFTER PAGE LOAD =====
    if (localStorage.getItem('cartOpen') === 'true') {
        localStorage.removeItem('cartOpen');
        const sidebar = document.getElementById('cartSidebar');
        if (sidebar && typeof bootstrap !== 'undefined') {
            const offcanvas = new bootstrap.Offcanvas(sidebar);
            offcanvas.show();
        }
    }
})();
</script>