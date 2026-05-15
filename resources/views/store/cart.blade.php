@extends('base.base')

@section('content')
<style>
    /* CART SIDEBAR */
    .cart-sidebar{
        width: 430px !important;
        background: #f9f9f7;
        border-left: 1px solid #ebe5de;
        padding: 22px 24px;

        display: flex;
        flex-direction: column;
        height: 100vh;
    }

    /* HEADER */
    .cart-header{
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-shrink: 0;
    }

    .cart-title{
        font-size: 28px;
        font-weight: 700;
        color: #111827;
        margin: 0;
        letter-spacing: -1px;
    }

    .cart-close-btn{
        border: none;
        background: transparent;
        font-size: 24px;
        color: #111827;
        transition: 0.2s ease;
    }

    .cart-close-btn:hover{
        opacity: 0.7;
    }

    /* BODY */
    .offcanvas-body{
        padding: 0 !important;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        height: 100%;
    }

    /* ITEMS */
    .cart-items-wrapper{
        flex: 1;
        overflow-y: auto;
        padding-right: 4px;
        margin-bottom: 16px;
    }

    .cart-item{
        display: flex;
        gap: 16px;
        padding: 14px 0;
        margin-bottom: 1px solid #eee7df;
    }

    .cart-item:first-child{
        padding-top: 0;
    }
    .cart-item-image{
        width: 92px;
        height: 92px;
        border-radius: 18px;
        object-fit: cover;
        background: #e8eeee;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .cart-item-image-wrapper{
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
    .cart-item-content{
        flex: 1;
        padding-top: 2px;
    }

    .cart-item-title{
        font-size: 16px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 6px;
        line-height: 1.3;
    }

    .cart-item-category{
        font-size: 11px;
        letter-spacing: 2px;
        font-weight: 700;
        color: #666;
        margin-bottom: 10px;
    }

    .cart-item-price{
        font-size: 15px;
        font-weight: 700;
        color: #111827;
        padding-bottom: 4px;
    }

    .remove-item-btn{
        border: none;
        background: transparent;
        font-size: 18px;
        color: #444;
        padding: 0;
        transition: 0.2s ease;
    }

    .remove-item-btn:hover{
        color: #c45555;
    }

    /* FOOTER */
    .cart-footer{
        border-top: 1px solid #ebe5de;
        padding-top: 18px;
        padding-bottom: 6px;
        flex-shrink: 0;
        background: #f9f9f7;
    }

    /* TOTAL */
    .cart-total-wrapper{
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
    }

    .cart-total-label{
        font-size: 16px;
        letter-spacing: 3px;
        font-weight: 700;
        color: #666;
    }

    .cart-total-price{
        font-size: 20px;
        font-weight: 700;
        color: #111827;
    }

    /* CHECKOUT */
    .checkout-btn{
        width: 100%;
        height: 58px;
        border-radius: 18px;
        border: none;
        background: #111827;
        color: white;
        font-size: 17px;
        font-weight: 600;
        transition: 0.2s ease;
    }

    .checkout-btn:hover{
        opacity: 0.92;
    }

    /* SCROLLBAR */
    .cart-items-wrapper::-webkit-scrollbar{
        width: 5px;
    }

    .cart-items-wrapper::-webkit-scrollbar-thumb{
        background: #999;
        border-radius: 20px;
    }

    /* QUANTITY */
    .cart-qty-wrapper{
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .cart-qty-btn{
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
    }

    .cart-qty-input{
        width: 42px;
        height: 28px;

        border-radius: 8px;
        border: 1px solid #ddd;

        background: white;

        text-align: center;

        font-size: 13px;
        font-weight: 600;
    }

    /* DELETE BUTTON */

    .remove-item-btn{
        border: none;
        background: transparent;

        font-size: 16px;
        color: #8b8b8b;

        transition: 0.2s ease;
    }

    .remove-item-btn:hover{
        color: #d35b5b;
    }

    /* RESPONSIVE */
    @media(max-width: 768px){
        .cart-sidebar{
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

<!-- ADD TO COLLECTION BUTTON -->
<button 
    class="btn btn-dark-custom action-btn w-100"
    data-bs-toggle="offcanvas"
    data-bs-target="#cartSidebar"
>
    <i class="fa-solid fa-bag-shopping me-2"></i>
    Add to Collection
</button>

<!-- CART SIDEBAR -->
<div 
    class="offcanvas offcanvas-end cart-sidebar" 
    tabindex="-1" id="cartSidebar">

    <!-- HEADER -->
    <div class="cart-header">
        <h2 class="cart-title">
            Your Collection
        </h2>

        <button 
            type="button" 
            class="cart-close-btn"
            data-bs-dismiss="offcanvas"
        >
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <!-- BODY -->
    <div class="offcanvas-body pt-0">
        <!-- CART ITEMS -->
        <div class="cart-items-wrapper">
            <!-- ITEM -->
            <div class="cart-item">
                <img src="https://placehold.co/300x300" class="cart-item-image">

                <div class="cart-item-content">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="cart-item-title">
                                Ethereal Glass Table
                            </h5>
                            <div class="cart-item-category">
                                TABLES
                            </div>
                            <div class="cart-item-price">
                                Rp 2.100.000
                            </div>

                            <!-- QUANTITY -->
                            <div class="cart-qty-wrapper">
                                <button class="cart-qty-btn" type="button"
                                    onclick="
                                        const qty = this.nextElementSibling;
                                        if(parseInt(qty.value) > 1){
                                            qty.value--;
                                        }
                                    "> -
                                </button>

                                <input
                                    type="text" value="1" class="cart-qty-input" readonly
                                >

                                <button class="cart-qty-btn" type="button"
                                    onclick="
                                        const qty = this.previousElementSibling;
                                        qty.value++;
                                    "> +
                                </button>
                            </div>
                        </div>

                        <!-- DELETE -->
                        <button class="remove-item-btn">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ITEM -->
            <div class="cart-item">
                <img src="https://placehold.co/300x300" class="cart-item-image">

                <div class="cart-item-content">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="cart-item-title">
                                Aurelius Lounge Chair
                            </h5>
                            <div class="cart-item-category">
                                SEATING
                            </div>
                            <div class="cart-item-price">
                                Rp 1.250.000
                            </div>

                            <!-- QUANTITY -->
                            <div class="cart-qty-wrapper">
                                <button class="cart-qty-btn" type="button"
                                    onclick="
                                        const qty = this.nextElementSibling;
                                        if(parseInt(qty.value) > 1){
                                            qty.value--;
                                        }
                                    "> -
                                </button>

                                <input
                                    type="text" value="1" class="cart-qty-input" readonly
                                >

                                <button class="cart-qty-btn" type="button"
                                    onclick="
                                        const qty = this.previousElementSibling;
                                        qty.value++;
                                    "> +
                                </button>
                            </div>
                        </div>

                        <!-- DELETE -->
                        <button class="remove-item-btn">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- STICKY FOOTER -->
        <div class="cart-footer">
            <!-- TOTAL -->
            <div class="cart-total-wrapper">
                <div class="cart-total-label">
                    TOTAL VALUE
                </div>
                <div class="cart-total-price">
                    Rp 3.350.000
                </div>
            </div>
        </div>

        <!-- CHECKOUT BUTTON -->
        <button class="checkout-btn">
            Proceed to Checkout
        </button>
    </div>
</div>
@endsection