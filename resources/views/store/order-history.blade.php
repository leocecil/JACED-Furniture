@extends('base.base')

@push('styles')
<style>

    .nav-pills .nav-link::after,
    .nav-pills .nav-link::before {
        display: none !important;
        content: none !important;
    }

    .nav-pills a.nav-link {
        text-decoration: none !important;
    }

    .nav-pills a.nav-link:hover {
        text-decoration: none !important;
    }

    .nav-pills {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }

    .nav-pills::-webkit-scrollbar {
        display: none; /* Chrome, Safari and Opera */
    }

    .page-title {
        font-size: 2.8rem;
        font-weight: 400;
        line-height: 1.1;
        color: var(--jaced-brown-dark);
    }

    .nav-pills .nav-link {
        border: 1px solid var(--jaced-input);
        border-radius: 999px;
        color: var(--jaced-brown-dark);
        font-size: 13px;
        font-weight: 500;
        padding: 7px 20px;
        background: transparent;
        transition: all .2s;
        text-decoration: none !important;
    }
    .nav-pills .nav-link:hover {
        border: 1px solid var(--jaced-sage) !important;
        border-color: var(--jaced-sage);
        color: var(--jaced-sage);
    }
    .nav-pills .nav-link.active {
        background-color: var(--jaced-caramel) !important;
        border-color: var(--jaced-caramel) !important;
        color: white !important;
    }

    .order-product-img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 10px;
        flex-shrink: 0;
    }
    .order-id-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: var(--jaced-muted);
    }
    .status-badge {
        display: inline-block;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: .08em;
        text-transform: uppercase;
        padding: 4px 12px;
        border-radius: 999px;
    }
    .status-badge.shipped    { color: #5b66ad;              background: #E8EDE8; }
    .status-badge.packed     { color: #8a6a2a;              background: #f5ecd5; }
    .status-badge.delivered  { color: #4a7c59;              background: #e4f0e8; }
    .status-badge.shipped    { color: #5b66ad;              background: #eceef8; }
    .status-badge.arrived    { color: #3da347;              background: #f5e4e4; }
    .status-badge.unpaid     { color: #b52f2f;              background: #f5f0e0; }
    /* .status-badge.returns    { color: #5a5a8a;              background: #eeeef5; } */
    .status-badge.disputed   { color: #603a7a;              background: #f5f0e0; }
    .status-badge.cancelled  { color: #930000;              background: #eeeef5; }
    .status-badge.on_process { color: #eb8303;              background: #f5f0e0; }

    .btn-order-details {
        background: var(--jaced-dark);
        color: white;
        padding: 9px 22px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all .25s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        display: inline-block;
    }
    .btn-order-details:hover {
        background: var(--jaced-caramel); 
        color: white;
        box-shadow: 0 4px 12px rgba(184, 115, 51, 0.2);
    }

    .order-divider {
        border: none;
        border-top: 1px solid var(--jaced-input);
        margin: 0;
    }

    /* Wrapper item per order dibuat jadi card mandiri (Kunci: Tanpa transform) */
    .order-item-card {
        background: white;
        transition: background-color 0.25s ease, box-shadow 0.25s ease;
        position: relative;
        animation: fadeInSlide 0.4s cubic-bezier(0.4, 0, 0.2, 1) both;
    }

    .order-item-card:hover {
        background-color: #F5F5F3;
        box-shadow: inset 0 -1px 0 var(--jaced-input);
        z-index: 2;
    }

    .order-product-img-wrapper {
        border-radius: 10px;
        overflow: hidden; 
        flex-shrink: 0;
    }
    
    .order-product-img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        transition: transform 0.3s ease; 
    }

    .order-item-card:hover .order-product-img {
        transform: scale(1.04); /* Gambar membesar tipis di dalam frame-nya sendiri */
    }

    /* Menjaga border radius list paling atas & bawah agar presisi dengan kontainer */
    .order-item-card:first-child {
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }
    .order-item-card:last-child {
        border-bottom-left-radius: 12px;
        border-bottom-right-radius: 12px;
    }

    /* Efek saat card di-hover, status badge di dalamnya ikut berubah sedikit */
    .order-item-card:hover .status-badge {
        transform: translateY(-1px); /* Naik 1 piksel saja secara halus */
        filter: brightness(0.95); /* Warnanya sedikit lebih solid/tegas */
        transition: all 0.25s ease;
    }
    .order-item-card:nth-child(1) { animation-delay: 0.05s; }
    .order-item-card:nth-child(2) { animation-delay: 0.1s; }
    .order-item-card:nth-child(3) { animation-delay: 0.15s; }
    .order-item-card:nth-child(4) { animation-delay: 0.2s; }

    @keyframes fadeInSlide {
        from {
            opacity: 0;
            transform: translateY(8px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Membuat efek gradasi memudar di ujung kanan kontainer */
    .menu-scroll-container::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 50px; /* Lebar area memudar */
        height: 100%;
        background: linear-gradient(to right, rgba(245, 235, 224, 0), #f5ebe0); /* Sesuaikan #f5ebe0 dengan warna background web kamu */
        pointer-events: none; /* Biar tombol di bawahnya tetap bisa diklik */
    }

    /* Sembunyikan scrollbar bawaan tapi fungsi slide bawaan HP tetap aktif */
    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .menu-scroll-container {
        width: 100%;
    }

    /* Styling Tombol Panah Bulat */
    .btn-nav-scroll {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: #ffffff; /* Warna background tombol putih bulat */
        border: 1px solid #e0e0e0;
        box-shadow: 0px 2px 5px rgba(0,0,0,0.1);
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: bold;
        color: #a69076; /* Warna panah */
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-nav-scroll:hover {
        background-color: #f5ebe0; /* Efek hover tipis */
    }

    /* Posisi tombol agar tidak menutupi teks menu pertama/terakhir */
    .left-arrow {
        left: -12px;
    }

    .right-arrow {
        right: -12px;
    }

    /* Sembunyikan scrollbar bawaan */
    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }

    /* Container Utama */
    .scroll-container {
        position: relative;
        width: 100%;
    }

    /* Efek Masking Fade Kiri */
    .scroll-container::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        width: 40px;
        height: 100%;
        background: linear-gradient(to right, #f5ebe0, rgba(245, 235, 224, 0)); /* Ganti #f5ebe0 dengan warna bg webmu */
        z-index: 2;
        pointer-events: none;
        transition: opacity 0.3s ease;
        opacity: 1; /* Default muncul */
    }

    /* Efek Masking Fade Kanan */
    .scroll-container::after {
        content: '';
        position: absolute;
        right: 0;
        top: 0;
        width: 40px;
        height: 100%;
        background: linear-gradient(to left, #f5ebe0, rgba(245, 235, 224, 0)); /* Ganti #f5ebe0 dengan warna bg webmu */
        z-index: 2;
        pointer-events: none;
        transition: opacity 0.3s ease;
        opacity: 1; /* Default muncul */
    }

    /* LOGIKANYA: Sembunyikan fade sesuai posisi */
    .scroll-container.is-at-start::before {
        opacity: 0; /* Pas di paling kiri, fade kiri ilang */
    }

    .scroll-container.is-at-end::after {
        opacity: 0; /* Pas mentok di kanan, fade kanan ilang */
    }
</style>
@endpush

@section('content')
@php
    $statusLabel = [
        'unpaid'     => 'Unpaid',
        'on_process' => 'On Process',
        'packed'     => 'Packed',
        'delivered'  => 'Delivered',
        'shipped'    => 'Shipped',
        'arrived'    => 'Arrived',
        'cancelled'  => 'Cancelled',
        'disputed'   => 'Disputed',
    ];
    $statusClass = [
        'unpaid'     => 'unpaid',
        'on_process' => 'on_process',
        'packed'     => 'packed',
        'delivered'  => 'delivered',
        'shipped'    => 'shipped',
        'arrived'    => 'arrived',
        'cancelled'  => 'cancelled',
        'disputed'   => 'disputed',
    ];
@endphp

<div class="jaced-page">
    <div style="max-width: 860px; margin: 0 auto;">

        <div class="mb-4">
            <h1 class="fw-bold mb-1">My Orders</h1>
            <p class="text-jaced-muted mb-0" style="font-size: 14px; line-height: 1.6;">
                Track your orders and manage their journey from our artisan workshops to your sanctuary.
            </p>
        </div>

        {{-- FILTER TABS --}}
        <div class="scroll-container is-at-start" id="scrollContainer">
            <ul class="nav nav-pills gap-2 mb-4 flex-nowrap overflow-x-auto text-nowrap py-2 hide-scrollbar" id="scrollMenu">
                @foreach ($filters as $filter)
                    <li class="nav-item">
                        <a href="{{ route('store.orderhistory', ['filter' => $filter]) }}"
                        class="nav-link text-decoration-none {{ $filter === $activeFilter ? 'active' : '' }}">
                            {{ $filter }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- ORDER LIST --}}
        <div class="jaced-card" style="background: transparent; box-shadow: none;">
            @forelse ($orders as $order)
                @php
                    $firstDetail = $order->orderDetails->first();
                    $productName = $firstDetail?->product?->name ?? 'Order #' . $order->id;
                    $productImage = $firstDetail?->product?->images?->where('is_main', 1)->first()?->image_path
                        ?? $firstDetail?->product?->images?->first()?->image_path
                        ?? null;
                    $extraCount = $order->orderDetails->count() - 1;
                @endphp

                {{-- Class diubah dari d-flex biasa menjadi order-item-card --}}
                <div class="order-item-card d-flex align-items-center gap-4 p-4">

                    {{-- Ditambahkan wrapper pembungkus gambar untuk efek zoom --}}
                    <div class="order-product-img-wrapper">
                        <img src="{{ $productImage ? asset($productImage) : asset('image/placeholder.png') }}"
                            alt="{{ $productName }}"
                            class="order-product-img">
                    </div>

                    <div class="flex-grow-1">
                        <h2 class="fw-bold mb-1" style="font-size: 18px; font-weight: 400;">
                            {{ $productName }}
                            @if ($extraCount > 0)
                                <span style="font-size: 13px; color: var(--jaced-muted); font-weight: 400;">
                                    +{{ $extraCount }} other item{{ $extraCount > 1 ? 's' : '' }}
                                </span>
                            @endif
                        </h2>

                        <p class="order-id-label mb-2">
                            ORDER #{{ $order->id }} &nbsp;·&nbsp; {{ $order->created_at->format('M d, Y') }}
                        </p>

                        <span class="status-badge {{ $statusClass[$order->status] ?? '' }}">
                            {{ $statusLabel[$order->status] ?? ucfirst($order->status) }}
                        </span>

                        @if ($order->status === 'delivered' && $order->delivered_at)
                            <p class="text-jaced-muted mb-0 mt-2" style="font-size: 12px;">
                                Delivered on
                                <span class="fw-semibold">
                                    {{ $order->delivered_at->format('l, M d') }}
                                </span>
                            </p>
                        @elseif ($order->status === 'packed' && $order->packed_at)
                            <p class="text-jaced-muted mb-0 mt-2" style="font-size: 12px;">
                                Packed on
                                <span class="fw-semibold">
                                    {{ $order->packed_at->format('l, M d') }}
                                </span>
                            </p>
                        @endif
                    </div>

                    <div class="d-flex flex-column align-items-end gap-2 flex-shrink-0">
                        <p class="fw-bold text-jaced-dark mb-0" style="font-size: 16px;">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </p>
                        <div class="d-flex gap-2">
                            @if ($order->status === 'unpaid')
                                <a href="{{ route('store.orderhistory.repay', $order->id) }}"
                                class="btn-order-details"
                                style="background: var(--jaced-sage);">
                                    Pay Now
                                </a>
                            @endif
                            <a href="{{ route('store.orderhistory_detail.show', $order->id) }}"
                            class="btn-order-details">
                                Order Details
                            </a>
                        </div>
                    </div>

                </div>

                {{-- Gak perlu pakai <hr> lagi karena tiap card udah punya batas visual sendiri saat di-hover --}}

            @empty
                <div class="p-5 text-center" style="background: white; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.04);">
                    <p style="color: var(--jaced-muted); font-size: 14px; margin: 0;">
                        No orders found.
                    </p>
                </div>
            @endforelse
        </div>

    </div>
</div>

@endsection

@push('scripts')
    <script>
        const scrollMenu = document.getElementById('scrollMenu');
        const scrollContainer = document.getElementById('scrollContainer');

        function updateFadeIndicators() {
            if (!scrollMenu || !scrollContainer) return; // Jaga-jaga agar tidak error jika elemen belum render

            const scrollLeft = scrollMenu.scrollLeft;
            // Mencari batas maksimal scroll kanan
            const maxScrollLeft = scrollMenu.scrollWidth - scrollMenu.clientWidth;

            // Cek posisi kiri (toleransi 2 pixel)
            if (scrollLeft <= 2) {
                scrollContainer.classList.add('is-at-start');
            } else {
                scrollContainer.classList.remove('is-at-start');
            }

            // Cek posisi kanan (toleransi 2 pixel)
            if (scrollLeft >= maxScrollLeft - 2) {
                scrollContainer.classList.add('is-at-end');
            } else {
                scrollContainer.classList.remove('is-at-end');
            }
        }

        // Jalankan fungsi saat user menggeser menu
        scrollMenu.addEventListener('scroll', updateFadeIndicators);

        // Jalankan sekali saat halaman pertama kali dimuat
        window.addEventListener('load', updateFadeIndicators);
        
        // Jalankan jika layar di-resize (misal dari desktop ke mobile)
        window.addEventListener('resize', updateFadeIndicators);
    </script>
@endpush