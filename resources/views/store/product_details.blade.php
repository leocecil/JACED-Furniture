@extends('base.base')

@section('content')

<style>
    .product-section{
        padding: 28px 0 40px;
    }
    .back-btn{
        text-decoration: none;
        color: var(--jaced-brown-dark);
        font-size: 18px;
        font-weight: 600;
        transition: 0.2s ease;
    }

    /* LEFT SIDE STICKY */
    .sticky-gallery{
        position: sticky;
        top: 100px;
    }
    /* MAIN IMAGE */
    .image-preview-wrapper{
        position: relative;
        background: var(--jaced-card);
        border-radius: 28px;
        overflow: hidden;
        height: 480px;

        display: flex;
        align-items: center;
        justify-content: center;
        
        border: 1px solid var(--jaced-input);
        box-shadow: 0 10px 30px rgba(0,0,0,0.04);
    }

    .main-product-image{
        width: 82%;
        height: 82%;
        object-fit: contain;
    }

    /* ARROWS */
    .slider-arrow{
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 34px;
        height: 34px;
        border-radius: 50%;
        border: none;
        background: rgba(39,46,29,0.9);
        color: white;
        font-size: 18px;
        z-index: 2;
        transition: 0.2s ease;
    }

    .slider-arrow:hover{ background: var(--jaced-dark); }

    .arrow-left{ left: 12px; }
    .arrow-right{ right: 12px;}

    /* THUMBNAILS */
    .thumbnail-wrapper{ margin-top: 12px; }

    .thumbnail-image{
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 14px;
        cursor: pointer;
        border: 3px solid transparent;
        transition: 0.2s ease;
        background: var(--jaced-card);
        padding: 4px;
    }

    .thumbnail-image:hover{ opacity: 0.9; }

    .thumbnail-image.active-thumbnail{
        border: 2px solid var(--jaced-brown-dark);
        transform: scale(0.97);
    }

    /* PRODUCT INFO */
    .premium-badge{
        display: inline-block;
        padding: 6px 14px;
        border-radius: 30px;
        background: #f2d3a1;
        color: var(--jaced-caramel);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1px;
    }

    .product-title{
        font-size: 40px;
        font-weight: 700;
        line-height: 1.05;
        margin-top: 14px;
        margin-bottom: 10px;
        color: var(--jaced-brown-dark);
    }

    .product-price{
        font-size: 26px;
        font-weight: 700;
        margin: 0 0 30px;
        color: var(--jaced-brown-dark);
    }

    .product-description{
        font-size: 15px;
        line-height: 1.9;
        color: #5d5d5d;
        margin-bottom: 28px;
    }

    /* INFO CARD */
    .info-card{
        background: var(--jaced-card);
        border-radius: 18px;
        padding: 20px;
        height: 100%;
    }

    .info-title{
        font-size: 16px;
        letter-spacing: 2px;
        font-weight: 700;
        color: var(--jaced-muted);
        margin-bottom: 10px;
    }

    .info-content{
        font-size: 14px;
        font-weight: 600;
        color: var(--jaced-brown-dark);
    }
    /* DIMENSION CARD */
        .dimension-card{ padding: 24px; }

        .dimension-grid{
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
        }

        .dimension-item{
            background: var(--jaced-white);
            border: 1px solid var(--jaced-input);
            border-radius: 18px;
            padding: 18px 16px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            transition: 0.2s ease;
        }

        .dimension-item:hover{
            transform: translateY(-2px);
            border-color: var(--jaced-caramel);
        }

        .dimension-label{
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;

            color: var(--jaced-muted);
        }

        .dimension-value{
            font-size: 22px;
            font-weight: 700;
            color: var(--jaced-brown-dark);
        }

        @media(max-width: 768px){
            .dimension-grid{
                grid-template-columns: 1fr;
            }
        }

    /* ACCORDION */
    .accordion-item{
        border-radius: 18px !important;
        overflow: hidden;
        border: 1px solid var(--jaced-input);
        background: var(--jaced-card);
    }

    .accordion-button{
        padding: 18px 22px;
        font-size: 16px;
        font-weight: 700;
        background: var(--jaced-card);
        color: var(--jaced-brown-dark);
        box-shadow: none !important;
    }

    .accordion-button:not(.collapsed){
        background: var(--jaced-card);
        color: var(--jaced-brown-dark);
    }

    .accordion-body{
        padding: 18px 22px;
        font-size: 16px;
        line-height: 1.8;
        color: var(--jaced-brown);
        background: var(--jaced-white);
        border-top: 1px solid var(--jaced-input);
    }

    /* QUANTITY */
    .qty-wrapper{
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 24px;
    }

    .qty-btn{
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: none;
        background: var(--jaced-brown-dark);
        color: white;
        font-size: 18px;
        font-weight: 500;
    }

    .qty-btn:hover{
        background: var(--jaced-dark);
    }

    .qty-input{
        width: 62px;
        height: 38px;
        border-radius: 10px;
        border: 1px solid var(--jaced-input);
        background: var(--jaced-card);
        text-align: center;
        font-size: 18px;
        font-weight: 700;
        color: var(--jaced-brown-dark)
    }

    /* BUTTONS */
    .action-btn{
        height: 56px;
        border-radius: 18px;
        font-size: 16px;
        font-weight: 600;
    }
    .btn-dark-custom{
        background: var(--jaced-brown-dark);
        color: white;
        border: none;
        font-size:16px; 
    }
    .btn-dark-custom:hover {
        background: var(--jaced-dark);
        color: white !important;
        opacity: 0.9;
    }
    .btn-outline-custom{
        border: 1.5px solid var(--jaced-input);
        background: transparent;
        color: var(--jaced-brown-dark);
        font-size:16px;
    }
    .btn-outline-custom:hover {
        background: var(--jaced-card); 
        border-color: var(--jaced-brown);
        color: var(--jaced-brown-dark);
    }

    /* WISHLIST */
    .wishlist-btn {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        border: 1px solid var(--jaced-input);
        background: var(--jaced-card);
        color: var(--jaced-brown-dark);
        font-size: 18px;
        transition: all 0.25s ease;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .wishlist-btn i { transition: all 0.25s ease; }
    .wishlist-btn:hover {
        background: var(--jaced-brown-dark);
        color: white;
    }
    .wishlist-btn.active {
        background: var(--jaced-caramel);
        color: white;
        border-color: var(--jaced-caramel);
    }
    .wishlist-btn.active:hover {
        background: rgba(156, 53, 53, 0.12);
        color: #9c3535;
        border-color: rgba(156, 53, 53, 0.3);
    }
    .wishlist-btn.active .fa-heart::before { content: "\f004"; }
    .wishlist-btn.active:hover .fa-heart::before { content: "\f7a9"; }

    .wishlist-btn.active{
        background: var(--jaced-brown-dark);
        color: white;
        border-color: var(--jaced-brown-dark);
    }

    body{
        background: #f9f9f7;
    }

    .cart-toast{
        position: fixed;
        bottom: 28px;
        left: 50%;
        transform: translateX(-50%);
        background: var(--jaced-brown-dark);
        color: var(--jaced-cream);
        padding: 14px 26px;
        border-radius: 999px;
        font-size: 14px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        width: fit-content;
        max-width: 320px;
        white-space: nowrap;
        z-index: 3000;
        box-shadow: 0 12px 32px rgba(0,0,0,0.2);
        animation: toastIn 0.4s ease;
        opacity: 1;
        transition: opacity 0.3s ease;
    }

    .related-card{
        background: var(--jaced-card);
        border-radius: 28px;
        padding: 18px;
        height: 100%;
        border: 1px solid var(--jaced-input);
        transition: 0.25s ease;
        overflow: hidden;
    }

    .related-card:hover{
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.06);
    }

    .related-image-wrapper{
        position: relative;
        background: #f5f2ec;
        border-radius: 22px;
        height: 260px;

        display: flex;
        align-items: center;
        justify-content: center;

        overflow: hidden;
    }

    .related-image{
        width: 85%;
        height: 85%;
        object-fit: contain;
        transition: 0.3s ease;
    }

    .related-card:hover .related-image{
        transform: scale(1.03);
    }

    .stock-badge{
        position: absolute;
        top: 16px;
        left: 16px;

        background: #d38a33;
        color: white;

        padding: 8px 14px;
        border-radius: 999px;

        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1px;

        z-index: 2;
    }

    .related-category{
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 2px;
        color: #c89a61;
        margin-bottom: 8px;
    }

    .related-title{
        font-size: 26px;
        font-weight: 600;
        line-height: 1.3;
        color: var(--jaced-brown-dark);
        transition: 0.2s ease;
    }

    .related-title:hover{
        color: var(--jaced-caramel);
    }

    .related-price{
        font-size: 18px;
        font-weight: 700;
        color: var(--jaced-brown-dark);
    }

    .related-dimension{
        font-size: 14px;
        font-weight: 600;
        color: var(--jaced-muted);
    }
    @keyframes toastIn{
        from{
            opacity:0;
            transform:translateX(-50%) translateY(20px);
        }
        to{
            opacity:1;
            transform:translateX(-50%) translateY(0);
        }
    }

    @media(max-width: 992px){
        .sticky-gallery{
            position: relative;
            top: 0;
        }
        .product-section{ padding: 20px 0 30px; }
        .product-title{ font-size: 30px; }
        .product-price{ font-size: 22px; }
        .image-preview-wrapper{ height: 300px; }
        .main-product-image{
            width: 100%;
            height: 100%;
        }
        .thumbnail-image{ height: 70px; }
    }

    .pd-confirm-backdrop {
        position: fixed; inset: 0;
        background: rgba(28,28,26,0.5);
        backdrop-filter: blur(4px);
        z-index: 1300;
        display: flex; align-items: center; justify-content: center;
        opacity: 0; visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s;
    }
    .pd-confirm-backdrop.show { opacity: 1; visibility: visible; }
    .pd-confirm-backdrop.show #pdConfirmModal { transform: scale(1) translateY(0) !important; }
</style>

<div class="container-fluid px-5 product-section">

    <!-- BACK -->
    <div class="mb-4">
        <a href="{{ route('shop') }}" class="back-btn">
            <i class="fa-solid fa-arrow-left me-2"></i> Back to Catalog
        </a>
    </div>

    <div class="row g-5">
        <!-- LEFT SIDE -->
        <div class="col-lg-6">
            <!-- IMAGE PREVIEW -->
            <div class="image-preview-wrapper">
                <!-- LEFT ARROW -->
                    <button class="slider-arrow arrow-left" onclick="previousImage()">
                        ‹
                    </button>

                    <!-- MAIN IMAGE -->
                    <img
                        id="mainImage"
                        src="{{ asset($product->mainImage->image_path) }}"
                        {{-- src="{{ $product->image_path ? asset('product_image/' . $product->image_path) : 'https://placehold.co/800x800' }}" --}}
                        class="main-product-image"
                        {{-- alt="{{ $product->name }}" --}}
                        alt="{{ $product->mainImage->image_path }}"
                    >

                    <!-- RIGHT ARROW -->
                    <button class="slider-arrow arrow-right" onclick="nextImage()"> ›
                    </button>
            </div>

            <!-- THUMBNAILS -->
            <div class="row g-3 thumbnail-wrapper">
                @foreach($product->images as $index => $image)
                    <div class="col-3">
                        <img
                            src="{{ asset($image->image_path) }}"
                            class="thumbnail-image {{ $index == 0 ? 'active-thumbnail' : '' }}"
                            onclick="changeImage(this)"
                            alt="{{ $product->mainImage->image_path }}"
                        >
                    </div>
                @endforeach
            </div>

        </div>

        <!-- RIGHT SIDE -->
        <div class="col-lg-6">

            <div class="d-flex align-items-center gap-3 mb-3">

                <span class="premium-badge">
                    {{ strtoupper($product->label) }}
                </span>

                <div class="fw-semibold text-secondary">
                    <i class="fa-solid fa-bag-shopping me-2"></i>
                    {{ $totalSold }} people bought this
                </div>
                {{-- ★ --}}
                <button
                    class="wishlist-btn {{ $isWishlisted ? 'active' : '' }}"
                    id="wishlistBtn" data-product="{{ $product->id }}">
                    <i class="{{ $isWishlisted ? 'fa-solid' : 'fa-regular' }} fa-heart"></i>
                </button>
            </div>

            <h2 class="product-title">
                {{ $product->name }}
            </h2>

            <div class="product-price">
                Rp {{ number_format($product->price, 0, ',', '.') }}
            </div>
            
            <!-- INFO CARDS -->
            <div class="row g-4">
                <div class="col-12">
                    <div class="info-card dimension-card">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="info-title mb-0">
                                PRODUCT DIMENSIONS
                            </div>

                            <i class="fa-solid fa-ruler-combined fs-5"
                            style="color: var(--jaced-caramel);">
                            </i>
                        </div>

                        <div class="dimension-grid">
                            <!-- LENGTH -->
                            <div class="dimension-item">
                                <span class="dimension-label"> Length </span>
                                <span class="dimension-value">{{ $product->length }} {{ $product->unit }}</span>
                            </div>
                            <!-- WIDTH -->
                            <div class="dimension-item">
                                <span class="dimension-label"> Width </span>
                                <span class="dimension-value">{{ $product->width }} {{ $product->unit }}</span>
                            </div>
                            <!-- HEIGHT -->
                            <div class="dimension-item">
                                <span class="dimension-label"> Height </span>
                                <span class="dimension-value">{{ $product->height }} {{ $product->unit }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
            <!-- QUANTITY -->
            <div class="qty-wrapper ">
                <label class="fw-bold fs-5 mb-0">
                    Quantity:
                </label>
                
                <button
                    class="qty-btn" type="button" onclick="decreaseQty()" > -
                </button>

                <input
                    type="number" id="quantity" value="1" min="1" max="{{ $product->stock }}"
                    class="qty-input" oninput="handleQtyInput()">

                <button
                    class="qty-btn" type="button" onclick="increaseQty()" > +
                </button>
            </div>

            <div class="mt-2 text-muted fw-semibold">
                Stock Available: {{ $product->stock }}
            </div>

            <!-- ACTION BUTTONS -->
            <div class="row g-3 mt-5">
                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" id="cartQuantity" value="1">

                    <button type="submit" class="btn btn-dark-custom action-btn w-100">
                        <i class="fa-solid fa-bag-shopping me-2"></i>
                        Add to Collection
                    </button>
                </form>
            </div>

            <!-- BOOTSTRAP ACCORDION -->
            <div class="accordion mt-4" id="productAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button
                            class="accordion-button" type="button"
                            data-bs-toggle="collapse" data-bs-target="#descriptionCollapse">
                            Product Description
                        </button>
                    </h2>

                    <div
                        id="descriptionCollapse"
                        class="accordion-collapse collapse show"
                        data-bs-parent="#productAccordion">
                        <div class="accordion-body">
                            {{ $product->description }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="cart-toast" id="cartToast" style="display:none;">
            <i class="fas fa-check-circle"></i>
            <span id="cartToastText"></span>
        </div>

        @if(session('success'))
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                showCartToast("{{ session('success') }}");
            });
        </script>
        @endif
    </div>
    @if($related->count())
    <div class="mt-5">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold"
            style="color: var(--jaced-brown-dark);">
                You May Also Like
            </h3>
        </div>

        <div class="row g-4">
            @foreach($related as $relatedProduct)
            <div class="col-lg-4 col-md-6">
                <div class="related-card">
                    <a href="{{ route('product.show', $relatedProduct->slug) }}"
                    class="text-decoration-none">
                        <div class="related-image-wrapper">

                            @if($relatedProduct->stock <= 3)
                                <div class="stock-badge">
                                    ONLY {{ $relatedProduct->stock }} LEFT
                                </div>
                            @endif

                            <img
                                src="{{ asset($relatedProduct->mainImage->image_path) }}"
                                class="related-image"
                                alt="{{ $relatedProduct->name }}"
                            >

                        </div>

                        <div class="mt-3">

                            <div class="related-category">
                                {{ strtoupper($relatedProduct->category->name) }}
                            </div>

                            <div class="related-title">
                                {{ $relatedProduct->name }}
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="related-price">
                                    Rp {{ number_format($relatedProduct->price, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<div class="pd-confirm-backdrop" id="pdConfirmBackdrop">
    <div style="background:#f9f9f7; border-radius:24px; padding:36px 32px; max-width:360px; width:90%; text-align:center; transform:scale(0.92) translateY(12px); transition:transform 0.35s cubic-bezier(0.22,1,0.36,1); box-shadow:0 24px 60px rgba(0,0,0,0.15);" id="pdConfirmModal">
        <div style="font-size:28px; color:#9c3535; margin-bottom:14px;"><i class="fas fa-heart-crack"></i></div>
        <h4 style="font-size:17px; font-weight:700; color:#1a1a18; margin-bottom:6px;">Remove from Wishlist?</h4>
        <p id="pdConfirmMsg" style="font-size:13px; color:#666; margin-bottom:24px; line-height:1.5;"></p>
        <div style="display:flex; gap:10px;">
            <button id="pdConfirmCancel" style="flex:1; background:transparent; border:1px solid #ddd; color:#1a1a18; padding:11px; border-radius:999px; font-size:13px; font-weight:600; cursor:pointer;">Keep it</button>
            <button id="pdConfirmOk" style="flex:1; background:#9c3535; border:none; color:white; padding:11px; border-radius:999px; font-size:13px; font-weight:600; cursor:pointer;">Remove</button>
        </div>
    </div>
</div>

<script>
    const thumbnails = document.querySelectorAll('.thumbnail-image');
    const mainImage = document.getElementById('mainImage');
    let currentIndex = 0;
    function changeImage(element){
        mainImage.src = element.src;
        thumbnails.forEach(img => {
            img.classList.remove('active-thumbnail');
        });
        element.classList.add('active-thumbnail');
        currentIndex = Array.from(thumbnails).indexOf(element);
    }

    function nextImage(){
        currentIndex++;
        if(currentIndex >= thumbnails.length){
            currentIndex = 0;
        }
        updateSliderImage();
    }

    function previousImage(){
        currentIndex--;
        if(currentIndex < 0){
            currentIndex = thumbnails.length - 1;
        }
        updateSliderImage();
    }

    function updateSliderImage(){
        mainImage.src = thumbnails[currentIndex].src;
        thumbnails.forEach(img => {
            img.classList.remove('active-thumbnail');
        });
        thumbnails[currentIndex].classList.add('active-thumbnail');
    }

    function selectColor(element){
        const colorName = element.getAttribute('data-color');
        document.getElementById('selectedColorName').innerText = colorName;
        document.querySelectorAll('.color-option').forEach(color => {
            color.classList.remove('active-color');
        });
        element.classList.add('active-color');
    }

    const qtyInput = document.getElementById('quantity');
    const cartQtyInput = document.getElementById('cartQuantity');

    function updateCartQty(){
        cartQtyInput.value = qtyInput.value;
    }

    function increaseQty(){
        let current = parseInt(qtyInput.value);

        if(current < {{ $product->stock }}){
            qtyInput.value = current + 1;
            updateCartQty();
        }
    }

    function decreaseQty(){
        let current = parseInt(qtyInput.value);

        if(current > 1){
            qtyInput.value = current - 1;
            updateCartQty();
        }
    }

    function handleQtyInput(){
        let value = parseInt(qtyInput.value);

        // if empty or invalid
        if(isNaN(value) || value < 1){
            value = 1;
        }

        // prevent exceeding stock
        if(value > {{ $product->stock }}){
            value = {{ $product->stock }};
        }

        qtyInput.value = value;

        updateCartQty();
    }


    const cartToast = document.getElementById('cartToast');
    const cartToastText = document.getElementById('cartToastText');

    let cartToastTimer = null;

    // function showCartToast(message) {
    //     cartToastText.textContent = message;
    //     cartToast.classList.add('show');
    //     clearTimeout(cartToastTimer);
    //     cartToastTimer = setTimeout(() => {
    //         cartToast.classList.remove('show');
    //     }, 2500);
    // }

    function showCartToast(message, type = 'success') {
        const toast = document.getElementById('cartToast');
        const text = document.getElementById('cartToastText');
        const icon = toast.querySelector('i');

        if(type === 'delete'){
            icon.className = 'fas fa-trash';
        }
        else if(type === 'update'){
            icon.className = 'fas fa-pen';
        }
        else if(type === 'wishlist'){
            icon.className = 'fas fa-heart';
        }
        else{
            icon.className = 'fas fa-check-circle';
        }

        text.textContent = message;
        toast.style.display = 'inline-flex';
        toast.classList.add('show');
        clearTimeout(cartToastTimer);

        cartToastTimer = setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => {
                toast.style.display = 'none';
                toast.style.opacity = '1';
                toast.classList.remove('show');
            }, 300);
        }, 2500);
    }

    const wishlistBtn = document.getElementById('wishlistBtn');

    wishlistBtn.addEventListener('click', async () => {
        const productId = wishlistBtn.dataset.product;

        try{
            const response = await fetch('/wishlist/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },

                body: JSON.stringify({
                    product_id: productId
                })
            });

            const data = await response.json();
            const icon = wishlistBtn.querySelector('i');

            if(data.status === 'added'){
                wishlistBtn.classList.add('active');
                icon.classList.remove('fa-regular');
                icon.classList.add('fa-solid');
                showCartToast('Added to wishlist', 'wishlist');

            }else{
                wishlistBtn.classList.remove('active');
                icon.classList.remove('fa-solid');
                icon.classList.add('fa-regular');
                showCartToast('Removed from wishlist', 'delete');
            }

        }catch(e){
            console.error(e);
            showCartToast('Failed to update wishlist', 'delete');
        }
    });
</script>

@endsection