@extends('base.base')

@section('content')

<style>
    .product-section{
        padding: 28px 0 40px;
    }
    .back-btn{
        text-decoration: none;
        color: #111827;
        font-size: 18px;
        font-weight: 600;
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
        background: rgba(20,20,20,0.72);
        color: white;
        font-size: 18px;
        z-index: 2;
        transition: 0.2s ease;
    }

    .slider-arrow:hover{ background: rgba(0,0,0,0.9); }

    .arrow-left{ left: 10px; }
    .arrow-right{ right: 10px;}

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
        background: #eef3f3;
        padding: 4px;
    }

    .thumbnail-image:hover{ opacity: 0.9; }

    .thumbnail-image.active-thumbnail{
        border: 2px solid #111827;
        transform: scale(0.97);
    }

    /* PRODUCT INFO */
    .premium-badge{
        display: inline-block;
        padding: 6px 14px;
        border-radius: 30px;
        background: #f2d3a1;
        color: #7a5a28;
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
        color: #111827;
    }

    .product-price{
        font-size: 26px;
        font-weight: 700;
        margin: 0 0 30px;
        color: #111827;
    }

    .product-description{
        font-size: 15px;
        line-height: 1.9;
        color: #5d5d5d;
        margin-bottom: 28px;
    }

    .info-card{
        background: #eeeeec;
        border-radius: 18px;
        padding: 20px;
        height: 100%;
    }

    .info-title{
        font-size: 16px;
        letter-spacing: 2px;
        font-weight: 700;
        color: #666;
        margin-bottom: 10px;
    }

    .info-content{
        font-size: 14px;
        font-weight: 600;
        color: #111827;
    }

    /* ACCORDION */
    .accordion-item{
        border-radius: 18px !important;
        overflow: hidden;
        border: none;
    }

    .accordion-button{
        padding: 18px 22px;
        font-size: 16px;
        font-weight: 700;
        background: #ececeb;
        color: #111827;
        box-shadow: none !important;
    }

    .accordion-button:not(.collapsed){
        background: #e7e7e7;
        color: #111827;
    }

    .accordion-body{
        padding: 18px 22px;
        font-size: 16px;
        line-height: 1.8;
        color: #555;
    }

    /* COLOR SELECT */
    .custom-select{
        border-radius: 18px;
        height: 60px;
        font-size: 18px;
        padding-left: 20px;
        font-weight: 500;
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
        background: #111827;
        color: white;
        font-size: 18px;
        font-weight: 500;
    }

    .qty-input{
        width: 62px;
        height: 38px;
        border-radius: 10px;
        border: 1px solid #ddd;
        text-align: center;
        font-size: 18px;
        font-weight: 700;
    }

    /* BUTTONS */
    .action-btn{
        height: 56px;
        border-radius: 18px;
        font-size: 16px;
        font-weight: 600;
    }
    .btn-dark-custom{
        background: #111827;
        color: white;
        border: none;
        font-size:16px; 
    }
    .btn-dark-custom:hover {
        background: #1f2937;
        color: white !important;
        opacity: 0.9;
    }
    .btn-outline-custom{
        border: 1.5px solid #dddddd;
        background: transparent;
        color: #111827;
        font-size:16px;
    }
    .btn-outline-custom:hover {
        background: #f3f4f6; 
        border-color: #111827;
    }

    /* WISHLIST */
    .wishlist-btn{
        width: 42px;
        height: 42px;
        border-radius: 50%;
        border: 1px solid #dddddd;
        background: white;
        color: #111827;
        font-size: 22px;
        transition: 0.2s ease;
    }

    .wishlist-btn:hover{
        background: #111827;
        color: white;
    }

    /* COLOR PICKER */
    .color-option{
        width: 32px;
        height: 32px;
        border-radius: 50%;
        cursor: pointer;
        border: 2px solid #8b8b8b;
        transition: 0.2s ease;
        position: relative;
    }

    .color-option:hover{
        transform: scale(0.95);
    }

    /* ACTIVE COLOR */
    .color-option.active-color::after{
        content: '';
        position: absolute;
        top: -8px;
        left: -8px;
        right: -8px;
        bottom: -8px;
        border: 2px solid #111827;
        border-radius: 50%;
    }

    body{
        background: #f9f9f7;
    }
    @media(max-width: 992px){
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
</style>

<div class="container-fluid px-5 product-section">

    <!-- BACK -->
    <div class="mb-4">
        <a href="#" class="back-btn">
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
                    src="https://placehold.co/800x800"
                    {{-- src="{{ $product->image_path ? asset('product_image/' . $product->image_path) : 'https://placehold.co/800x800' }}" --}}
                    class="main-product-image"
                    {{-- alt="{{ $product->name }}" --}}
                >

                <!-- RIGHT ARROW -->
                <button class="slider-arrow arrow-right" onclick="nextImage()"> ›
                </button>

            </div>

            <!-- THUMBNAILS -->
            <div class="row g-3 thumbnail-wrapper">
                <div class="col-3">
                    <img
                        src="https://placehold.co/800x800"
                        {{-- src="{{ $product->image_path ? asset('product_image/' . $product->image_path) : 'https://placehold.co/300x300' }}" --}}
                        class="thumbnail-image active-thumbnail"
                        onclick="changeImage(this)"
                    >
                </div>

                <div class="col-3">
                    <img
                        src="https://placehold.co/800x800"
                        {{-- src="{{ $product->image_path ? asset('product_image/' . $product->image_path) : 'https://placehold.co/300x300/cccccc' }}" --}}
                        class="thumbnail-image"
                        onclick="changeImage(this)"
                    >
                </div>

                <div class="col-3">
                    <img
                        src="https://placehold.co/800x800"
                        {{-- src="{{ $product->image_path ? asset('product_image/' . $product->image_path) : 'https://placehold.co/300x300/bbbbbb' }}" --}}
                        class="thumbnail-image"
                        onclick="changeImage(this)"
                    >
                </div>

                <div class="col-3">
                    <img
                        src="https://placehold.co/800x800"
                        {{-- src="{{ $product->image_path ? asset('product_image/' . $product->image_path) : 'https://placehold.co/300x300/aaaaaa' }}" --}}
                        class="thumbnail-image"
                        onclick="changeImage(this)"
                    >
                </div>

            </div>

        
        </div>

        <!-- RIGHT SIDE -->
        <div class="col-lg-6">

            <div class="d-flex align-items-center gap-3 mb-3">

                <span class="premium-badge">
                    PREMIUM SELECTION
                </span>

                <a href="#" class="fw-semibold text-secondary">
                    <i class="fa-solid fa-star me-2"></i>4.9 (124 reviews)
                </a>
                {{-- ★ --}}
                <button class="wishlist-btn">
                    <i class="fa-regular fa-heart"></i>
                </button>
            </div>

            <h2 class="product-title">
                {{-- {{ $product->name }} --}}
                Lounge Chair
            </h2>

            <div class="product-price">
                {{-- Rp {{ number_format($product->price, 0, ',', '.') }} --}}
                Rp 250.000
            </div>

            <!-- INFO CARDS -->
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="info-card">
                        <div class="info-title">
                            DIMENSIONS
                        </div>
                        <div class="info-content">
                            L 85cm x W 90cm x H 75cm
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="info-card">
                        <div class="info-title">
                            MATERIALS
                        </div>
                        <div class="info-content">
                            Solid Oak, Leather
                        </div>
                    </div>
                </div>
            </div>

            <!-- COLOR OPTIONS -->
            <div class="mt-5">
                <label class="fw-bold fs-5 mb-3">
                    Select Product Color
                </label>
                <!-- SELECTED COLOR TEXT -->
                <div class="mb-4">

                    <h4 class="fw-light mb-0" style="font-size: 20px;">
                        <span id="selectedColorName">YELLOW</span>
                    </h4>

                </div>

                <!-- COLOR OPTIONS -->
                <div class="d-flex align-items-center gap-4">
                    <!-- WHITE -->
                    <div 
                        class="color-option"
                        data-color="WHITE"
                        style="background: #f4f4f4;"
                        onclick="selectColor(this)"
                    >
                    </div>

                    <!-- BLACK -->
                    <div 
                        class="color-option"
                        data-color="BLACK"
                        style="background: #111111;"
                        onclick="selectColor(this)"
                    >
                    </div>

                    <!-- LIGHT GRAY -->
                    <div 
                        class="color-option"
                        data-color="GRAY"
                        style="background: #ddd9d9;"
                        onclick="selectColor(this)">
                    </div>

                    <!-- YELLOW -->
                    <div 
                        class="color-option active-color"
                        data-color="YELLOW"
                        style="background: #dec892;"
                        onclick="selectColor(this)">
                    </div>
                </div>
            </div>
            
            <br>
            <!-- QUANTITY -->
            <div class="qty-wrapper ">
                <label class="fw-bold fs-5 mb-0">
                    Quantity:
                </label>
                
                <button
                    class="qty-btn" type="button"
                    onclick="
                        const qty = document.getElementById('quantity');
                        if(parseInt(qty.value) > 1){
                            qty.value--;
                        }" > -
                </button>

                <input
                    type="text"
                    id="quantity"
                    value="1"
                    class="qty-input"
                    readonly
                >

                <button
                    class="qty-btn"
                    type="button"
                    onclick="
                        const qty = document.getElementById('quantity');
                        qty.value++;
                    "
                >
                    +
                </button>

            </div>

            <!-- ACTION BUTTONS -->
            <div class="row g-3 mt-5">

                <div class="col-md-6">
                    <button class="btn btn-dark-custom action-btn w-100">
                        <i class="fa-solid fa-bag-shopping me-2"></i> Add to Collection
                    </button>
                </div>

                <div class="col-md-6">
                    <button class="btn btn-outline-custom action-btn w-100">
                        <i class="fa-solid fa-cube me-2"></i> 3D Simulation
                    </button>
                </div>
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
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Deleniti nihil temporibus ipsa magnam quidem perferendis voluptatem expedita omnis magni, accusamus aliquid iure? Doloremque officia nobis totam rem, hic reprehenderit dolorum.
                            {{-- {{ $product->details }} --}}
                        </div>
                    </div>
                </div>
            </div>

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
</script>

@endsection