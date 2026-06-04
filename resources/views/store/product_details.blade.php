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
        background: transparent;
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
        width: 100%;
        height: 100%;
        object-fit: cover;
        cursor: zoom-in;
        transition: transform 0.4s ease;
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
        border: 2px solid transparent;
        background: var(--jaced-card);
        padding: 4px;

        transition:
            transform 0.4s cubic-bezier(0.22,1,0.36,1),
            box-shadow 0.4s ease,
            opacity 0.3s ease;
    }

    .thumbnail-image:hover{
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 10px 24px rgba(39,46,29,0.12);
    }

    .thumbnail-image.active-thumbnail{
        border: 2px solid var(--jaced-brown-dark);
        /* transform: scale(0.97); */
        box-shadow: 0 8px 20px rgba(39,46,29,0.12);
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
        color: var(--jaced-sage);
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
        transition: 0.2s ease;
    }

    .accordion-button:hover{
        background: rgba(0,0,0,0.02);
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

    .sold-tag { font-size: 16px; color: #5d5d5d; display: flex; align-items: center; gap: 5px; margin-left: 8px; }

    .stock-row { display: flex; align-items: center; gap: 8px; font-size: 16px; color: var(--jaced-muted); font-weight: 500; }
    .stock-dot { width: 7px; height: 7px; border-radius: 50%; background: #4a7c59; flex-shrink: 0; }

    /* Quantity */
    .qty-row { display: flex; align-items: center; gap: 12px; }
    .qty-label { font-size: 18px; color: #4a7c59; font-weight: 500; }
    .qty-ctrl {
        display: flex; align-items: center;
        border: 1.5px solid var(--jaced-input);
        border-radius: 999px; overflow: hidden;
        background: var(--jaced-card);
    }
    .qty-btn-new {
        width: 40px; height: 40px; border: none;
        background: transparent; cursor: pointer;
        font-size: 18px; font-weight: 500; transition: 0.2s ease;
        color: var(--jaced-brown-dark);
    }
    .qty-btn-new:hover{
        background: rgba(0,0,0,0.04);
    }
    .qty-num-input{
        width: 54px;
        height: 40px;
        border: none;
        border-left: 1px solid var(--jaced-input);
        border-right: 1px solid var(--jaced-input);
        background: transparent;
        text-align: center;
        font-size: 15px;
        font-weight: 600;
        color: var(--jaced-brown-dark);
        outline: none;
        appearance: textfield;
        -moz-appearance: textfield;
    }

    .qty-num-input::-webkit-outer-spin-button,
    .qty-num-input::-webkit-inner-spin-button{ -webkit-appearance: none; margin: 0; }

    .qty-input-inline{
        width: 52px;
        height: 40px;
        border: none;
        outline: none;
        text-align: center;
        font-size: 15px;
        font-weight: 600;
        background: transparent;
        color: var(--jaced-brown-dark);

        border-left: 1px solid var(--jaced-input);
        border-right: 1px solid var(--jaced-input);

        -moz-appearance: textfield;
    }

    .qty-input-inline::-webkit-outer-spin-button,
    .qty-input-inline::-webkit-inner-spin-button{
        -webkit-appearance: none;
        margin: 0;
    }
    /* BUTTONS */
    .action-btn{
        height: 56px;
        border-radius: 999px !important;
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
        background: #1f3117;
        color: #f4efe7;
        padding: 18px 34px;
        border-radius: 999px;
        font-size: 15px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 12px;
        width: fit-content;
        max-width: 90vw;
        z-index: 3000;
        box-shadow: 0 10px 30px rgba(0,0,0,0.18);
        animation: toastIn 0.35s ease;
        letter-spacing: -0.01em;
    }
    /* .cart-toast{
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
    } */


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

    .wl-card {
        background: var(--jaced-card); border-radius: 16px; overflow: hidden;
        transition: transform 0.4s cubic-bezier(0.22,1,0.36,1), box-shadow 0.4s ease;
        position: relative;
    }
    .wl-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(39,46,29,0.1); }
    .wl-card-img-wrap { position: relative; aspect-ratio: 1; overflow: hidden; background: var(--jaced-input); }
    .wl-card-img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s cubic-bezier(0.22,1,0.36,1); }
    .wl-card:hover .wl-card-img { transform: scale(1.06); }

    .wl-remove-btn i { transition: all 0.2s ease; }
    .wl-remove-btn:hover i {
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
    }
    .wl-remove-btn:hover { background: rgba(156, 53, 53, 0.1); color: #9c3535; }

    .wl-card-body { padding: 16px; }
    .wl-card-cat { font-size: 10px; text-transform: uppercase; letter-spacing: 0.2em; color: var(--jaced-caramel); font-weight: 600; margin-bottom: 4px; display: block; }
    .wl-card-name { font-size: 16px; font-weight: 600; color: var(--jaced-brown-dark); margin-bottom: 4px; letter-spacing: -0.01em; line-height: 1.3; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; 
        display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; }
    .wl-card-price { font-size: 20px; font-weight: 700; color: var(--jaced-sage); margin-bottom: 6px; display: block; }
    .wl-card-actions { display: flex; gap: 8px; }

    .wl-atc-btn {
        flex: 1; display: flex; align-items: center; justify-content: center;
        background: var(--jaced-brown-dark); color: var(--jaced-cream);
        padding: 10px 16px; border-radius: 999px; font-size: 12px; font-weight: 600;
        border: none; cursor: pointer; transition: background 0.3s ease; gap: 6px; width: 100%;
    }
    .wl-atc-btn:hover { background: var(--jaced-caramel); color: var(--jaced-cream); }
    .wl-atc-btn.added { background: #4a7c59; }
    .wl-atc-btn:disabled { opacity: 0.7; cursor: not-allowed; }

    .modal-backdrop.show{
        opacity: 0.88;
    }

    #modalImage{
        animation: fadeZoom 0.25s ease;
    }

    @keyframes fadeZoom{
        from{
            opacity: 0;
            transform: scale(0.96);
        }
        to{
            opacity: 1;
            transform: scale(1);
        }
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
        .product-price{ font-size: 22px; color: var(--jaced-sage); }
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
                        id="mainImage" data-bs-toggle="modal" data-bs-target="#imageModal"
                        src="{{ asset($product->mainImage->image_path) }}"
                        class="main-product-image"
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

            <div class="d-flex align-items-center gap-2 mb-3">
                <span class="premium-badge">{{ strtoupper($product->label) }}</span>
                <div class="sold-tag">
                    <i class="fa-solid fa-bag-shopping"></i>
                    {{ $totalSold }} people bought this
                </div>
                <button class="wishlist-btn ms-auto {{ $isWishlisted ? 'active' : '' }}"
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
            <div class="qty-row mt-4 mb-3">
                <span class="qty-label">Quantity</span>
                <div class="qty-ctrl">
                    <button class="qty-btn-new" type="button" onclick="decreaseQty()">−</button>
                    <input type="number" id="quantity" class="qty-num-input" value="1"
                        min="1" max="{{ $product->stock }}" oninput="handleQtyInput()" >
                    <button class="qty-btn-new" type="button" onclick="increaseQty()">+</button>
                </div>
                {{-- <span class="stock-dot"></span>
                In stock — {{ $product->stock }} units available --}}
            </div>

            <div class="stock-row">
                <span class="stock-dot"></span>
                In stock — {{ $product->stock }} units available
            </div>


            <!-- ACTION BUTTONS -->
            {{-- <div class="row g-3 mt-2">
                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" id="cartQuantity" value="1">

                    <button type="submit" class="btn btn-dark-custom action-btn w-100">
                        <i class="fa-solid fa-bag-shopping me-2"></i>
                        Add to Collection
                    </button>
                </form>
            </div> --}}
            {{-- change 1 --}}

            <div class="row g-3 mt-2">
                <input type="hidden" id="cartQuantity" value="1">
                <button type="button" id="addToCartBtn" class="btn btn-dark-custom action-btn w-100">
                    <i class="fa-solid fa-bag-shopping me-2"></i>
                    Add to Collection
                </button>
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
                        class="accordion-collapse collapse"
                        data-bs-parent="#productAccordion">
                        <div class="accordion-body">
                            {{ $product->description }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
{{-- 
        @if(session('success'))
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                showCartToast("{{ session('success') }}");
            });
        </script>
        @endif --}}
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
            <div class="col-6 col-md-4 col-lg-3">
                <div class="wl-card">
                    <a href="{{ route('product.show', $relatedProduct->slug) }}"
                    style="text-decoration:none; color:inherit;">
                        <div class="wl-card-img-wrap">
                            @if($relatedProduct->stock <= 3)
                                <div class="stock-badge">
                                    ONLY {{ $relatedProduct->stock }} LEFT
                                </div>
                            @endif

                            <img
                                src="{{ asset($relatedProduct->mainImage->image_path) }}"
                                alt="{{ $relatedProduct->name }}"
                                class="wl-card-img"
                            >
                            {{-- OPTIONAL HEART BUTTON --}}
                            <button class="wl-remove-btn"
                                    data-id="{{ $relatedProduct->id }}"
                                    title="Wishlist">
                                <i class="fas fa-heart"></i>
                            </button>
                        </div>
                        <div class="wl-card-body">
                            <small class="wl-card-cat">
                                {{ strtoupper($relatedProduct->category->name) }}
                            </small>
                            <h5 class="wl-card-name">
                                {{ $relatedProduct->name }}
                            </h5>
                            <span class="wl-card-price">
                                Rp {{ number_format($relatedProduct->price, 0, ',', '.') }}
                            </span>
                        </div>
                    </a>
                    <div class="wl-card-actions px-3 pb-3">
                        <button class="wl-atc-btn"
                                data-id="{{ $relatedProduct->id }}"
                                data-name="{{ $relatedProduct->name }}">
                            <i class="fas fa-shopping-bag"></i>
                            Add to Collection
                        </button>
                    </div>
                </div>
            </div>
            @endforeach    
        </div>
    </div>
    @endif
</div>

<!-- IMAGE MODAL -->
<!-- IMAGE MODAL -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-transparent border-0 position-relative">

            <!-- CLOSE -->
            <button type="button"
                    class="btn-close btn-close-white position-absolute top-0 end-0 m-4 z-3"
                    data-bs-dismiss="modal">
            </button>

            <!-- PREV -->
            <button
                class="slider-arrow arrow-left z-3"
                onclick="previousModalImage()">
                ‹
            </button>

            <!-- IMAGE -->
            <img
                id="modalImage" src="" class="w-100"
                style="object-fit: contain; max-height: 90vh; border-radius: 20px;" >

            <!-- NEXT -->
            <button
                class="slider-arrow arrow-right z-3"
                onclick="nextModalImage()">
                ›
            </button>

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

    mainImage.addEventListener('click', () => {
        updateModalImage();
    });

    function updateModalImage(){
        document.getElementById('modalImage').src =
            thumbnails[currentIndex].src;
    }

    function nextModalImage(){
        currentIndex++;

        if(currentIndex >= thumbnails.length){
            currentIndex = 0;
        }

        updateSliderImage();
        updateModalImage();
    }

    function previousModalImage(){
        currentIndex--;

        if(currentIndex < 0){
            currentIndex = thumbnails.length - 1;
        }

        updateSliderImage();
        updateModalImage();
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
        let current = parseInt(qtyInput.value) || 1;

        if(current < {{ $product->stock }}){
            qtyInput.value = current + 1;
            updateCartQty();
        }
    }

    function decreaseQty(){
        let current = parseInt(qtyInput.value) || 1;
        if(current > 1){
            qtyInput.value = current - 1;
            updateCartQty();
        }
    }

    function handleQtyInput(){
        let value = qtyInput.value;
        // allow empty while typing
        if(value === ''){
            cartQtyInput.value = '';
            return;
        }
        value = parseInt(value);
        if(isNaN(value)){
            qtyInput.value = 1;
            value = 1;
        }
        if(value < 1){
            value = 1;
        }
        if(value > {{ $product->stock }}){
            value = {{ $product->stock }};
        }
        qtyInput.value = value;
        updateCartQty();
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

    // document.getElementById('addToCartBtn').addEventListener('click', function() {

    const addToCartBtn = document.getElementById('addToCartBtn');
    addToCartBtn.addEventListener('click', function() {
        @guest
        window.location.href = '{{ route("login") }}';
        return;
        @endguest

        const productId = '{{ $product->id }}';
        const quantity = parseInt(document.getElementById('cartQuantity').value) || 1;

        fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: quantity
            })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success){
                addToCartBtn.disabled = true;
                addToCartBtn.innerHTML =
                    '<i class="fas fa-check"></i> Added';

                updateCartBadge(data.count);
                refreshCartSidebar();
                showCartToast('{{ $product->name }} added to cart', 'success');

                setTimeout(() => {
                    addToCartBtn.disabled = false;
                    addToCartBtn.innerHTML =
                        '<i class="fa-solid fa-bag-shopping me-2"></i> Add to Collection';
                }, 2000);
            }
        });
    });

    // RELATED PRODUCTS - Add to Cart
    document.querySelectorAll('.wl-atc-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            @guest
            window.location.href = '{{ route("login") }}';
            return;
            @endguest

            const id = this.dataset.id;
            const name = this.dataset.name;

            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';

            fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ product_id: id, quantity: 1 })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    this.innerHTML = '<i class="fas fa-check"></i> Added';
                    this.classList.add('added');
                    updateCartBadge(data.count);
                    refreshCartSidebar();
                    showCartToast(name + ' added to cart', 'success');
                    setTimeout(() => {
                        this.disabled = false;
                        this.innerHTML = '<i class="fas fa-shopping-bag"></i> Add to Collection';
                        this.classList.remove('added');
                    }, 2000);
                }
            })
            .catch(() => {
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-shopping-bag"></i> Add to Collection';
                showCartToast('Failed to add product', 'delete');
            });
        });
    });
</script>
@endsection