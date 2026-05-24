@extends('base.base')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/jaced.css') }}">
<style>
    .catalogue-page {
        background-color: var(--jaced-cream);
        padding: 40px 24px;
        min-height: 100vh;
    }
    .points-sticky-card {
        background: white;
        border-radius: 12px;
        padding: 16px 24px;
        border: 1px solid var(--jaced-input);
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }
    .filter-wrapper { margin-bottom: 32px; }
    .category-scroll {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 8px;
        scrollbar-width: none;
    }
    .category-scroll::-webkit-scrollbar { display: none; }
    .filter-pill {
        background: white;
        border: 1px solid var(--jaced-input);
        padding: 8px 16px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 500;
        color: var(--jaced-muted);
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.2s;
    }
    .filter-pill.active {
        background: var(--jaced-caramel);
        color: white;
        border-color: var(--jaced-caramel);
    }
    .utility-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 16px;
    }
    .select-sort {
        background: white;
        border: 1px solid var(--jaced-input);
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 13px;
        color: var(--jaced-brown-dark);
        outline: none;
        cursor: pointer;
    }
    .toggle-container {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        user-select: none;
        font-size: 13px;
        color: var(--jaced-brown-dark);
        font-weight: 500;
    }
    .toggle-switch {
        position: relative;
        width: 36px;
        height: 20px;
        background: var(--jaced-input);
        border-radius: 999px;
        transition: background 0.2s;
    }
    .toggle-switch::after {
        content: '';
        position: absolute;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: white;
        top: 3px;
        left: 3px;
        transition: transform 0.2s;
    }
    .toggle-container.active .toggle-switch { background: var(--jaced-sage); }
    .toggle-container.active .toggle-switch::after { transform: translateX(16px); }

    .reward-grid-card {
        background: white;
        border-radius: 14px;
        overflow: hidden;
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: transform 0.2s;
    }
    .reward-grid-card:hover { transform: translateY(-2px); }
    .reward-body {
        padding: 16px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    .reward-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--jaced-brown-dark);
        margin: 0 0 8px;
    }
    .reward-pts {
        font-size: 13px;
        font-weight: 600;
        color: var(--jaced-brown-dark);
        margin-bottom: 4px;
    }
    .reward-pts-val {
        color: var(--jaced-caramel);
        font-weight: 700;
        font-size: 18px;
    }
    .reward-action-btn { margin-top: auto; }
    .btn-redeem-active {
        background: var(--jaced-sage);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 9px 16px;
        font-size: 13px;
        font-weight: 600;
        width: 100%;
        cursor: pointer;
        transition: background .2s;
    }
    .btn-redeem-active:hover { background: #4a5d4b; }
    .btn-redeem-locked {
        background: var(--jaced-input);
        color: var(--jaced-muted);
        border: none;
        border-radius: 8px;
        padding: 9px 16px;
        font-size: 13px;
        font-weight: 600;
        width: 100%;
        cursor: not-allowed;
    }

    /* MODAL */
    .jaced-modal-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(28, 28, 26, 0.6);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }
    .jaced-modal-overlay.show { opacity: 1; pointer-events: auto; }
    .jaced-modal-box {
        background: white;
        border-radius: 16px;
        padding: 32px 24px;
        max-width: 400px;
        width: 90%;
        text-align: center;
        box-shadow: 0 10px 30px rgba(39, 46, 29, 0.08);
        transform: scale(0.9);
        transition: transform 0.3s ease;
    }
    .jaced-modal-overlay.show .jaced-modal-box { transform: scale(1); }
    .modal-icon-wrap {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }
    .modal-icon-wrap.confirmation { background: #FAF2EB; color: var(--jaced-caramel); }
    .modal-icon-wrap.success { background: #EAF0EB; color: var(--jaced-sage); }
    .modal-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--jaced-brown-dark);
        margin-bottom: 8px;
    }
    .modal-text {
        font-size: 13px;
        color: var(--jaced-muted);
        line-height: 1.5;
        margin: 0;
    }
    .btn-modal-primary {
        background: var(--jaced-sage);
        color: white !important;
        border: none;
        padding: 11px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        flex: 1;
        transition: background 0.2s;
    }
    .btn-modal-primary:hover { background: #4a5d4b; }
    .btn-modal-secondary {
        background: transparent;
        border: 1px solid var(--jaced-input);
        color: var(--jaced-brown-dark);
        padding: 11px 20px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 13px;
        cursor: pointer;
        flex: 1;
        transition: all 0.2s;
    }
    .btn-modal-secondary:hover { background: var(--jaced-cream); }
</style>
@endpush

@section('content')

<div class="catalogue-page">
    <div style="max-width: 1000px; margin: 0 auto;">

        {{-- BACK --}}
        <a href="{{ route('reward') }}" class="back-link mb-3">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            <span>Back to Reward Center</span>
        </a>

        {{-- BALANCE BAR --}}
        <div class="points-sticky-card">
            <div>
                <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--jaced-brown-dark); margin: 0;">Redeem Rewards</h1>
                <p style="font-size: 12px; color: var(--jaced-muted); margin: 0;">Tukarkan poin kamu dengan voucher diskon eksklusif.</p>
            </div>
            <div class="text-end">
                <p style="font-size: 11px; font-weight: 600; color: var(--jaced-muted); text-transform: uppercase; margin: 0;">Your Balance</p>
                <p style="font-size: 24px; font-weight: 700; color: var(--jaced-brown-dark); margin: 0;">
                    <span style="color: var(--jaced-caramel);">{{ number_format($currentPoints) }}</span>
                    <span style="font-size: 14px; font-weight: 500;">Points</span>
                </p>
            </div>
        </div>

        {{-- FLASH MESSAGE --}}
        @if(session('success'))
            <div class="alert alert-success mb-3" style="font-size: 13px;">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger mb-3" style="font-size: 13px;">{{ session('error') }}</div>
        @endif

        {{-- FILTER PANEL --}}
        <div class="filter-wrapper">
            <div class="category-scroll">
                <div class="filter-pill active" data-category="all">All Vouchers</div>
                <div class="filter-pill" data-category="shipping">Gratis Ongkir</div>
                <div class="filter-pill" data-category="product">Diskon Produk</div>
            </div>

            <div class="utility-bar">
                <select class="select-sort" id="sortPoints">
                    <option value="default">Sort: Featured</option>
                    <option value="low">Points: Low to High</option>
                    <option value="high">Points: High to Low</option>
                </select>

                <div class="toggle-container" id="affordableToggle">
                    <div class="toggle-switch"></div>
                    <span>Show Affordable Only</span>
                </div>
            </div>
        </div>

        {{-- REWARDS GRID --}}
        <div class="row g-3" id="rewardsContainer">
            @forelse ($redeemGoals as $reward)
                @php 
                    $isEnough = $currentPoints >= $reward->point_cost; 
                    $voucherImage = match(true) {
                        $reward->used_for === 'delivery'       => 'disc-ongkir.png',
                        $reward->discount_percentage === 100     => 'disc-100.png',
                        $reward->discount_percentage === 90     => 'disc-90.png',
                        $reward->discount_percentage === 80     => 'disc-80.png',
                        $reward->discount_percentage === 70     => 'disc-70.png',
                        $reward->discount_percentage === 60     => 'disc-60.png',
                        $reward->discount_percentage === 50     => 'disc-50.png',
                        $reward->discount_percentage === 40     => 'disc-40.png',
                        $reward->discount_percentage === 30     => 'disc-30.png',
                        $reward->discount_percentage === 20     => 'disc-20.png',
                        $reward->discount_percentage === 10     => 'disc-10.png',
                        default                               => 'disc-product-default.png',
                    };
                @endphp

                <div class="col-12 col-sm-6 col-md-4 reward-item-card"
                     data-category="{{ $reward->used_for }}"
                     data-points="{{ $reward->point_cost }}"
                     data-affordable="{{ $isEnough ? 'true' : 'false' }}">

                    <div class="reward-grid-card">
                        <img src="{{ asset('image/vouchers/' . $voucherImage) }}"
                            alt="{{ $reward->name }}"
                            style="width: 100%; height: 140px; object-fit: cover;">

                        <div class="reward-body">

                            {{-- Badge jenis voucher --}}
                            <div class="mb-2">
                                @if($reward->used_for === 'shipping')
                                    <span class="badge" style="background-color: #f1f4f2; color: #5c695d; font-size: 11px;">🚚 Gratis Ongkir</span>
                                @else
                                    <span class="badge" style="background-color: #fcf5f3; color: #bd654e; font-size: 11px;">🏷️ Diskon Produk</span>
                                @endif
                            </div>

                            <p class="reward-title">{{ $reward->name }}</p>
                            <p class="text-muted mb-2" style="font-size: 12px;">{{ $reward->description }}</p>

                            <p class="reward-pts">
                                <span class="reward-pts-val">{{ number_format($reward->point_cost) }}</span> Points
                            </p>

                            <p class="text-muted mb-3" style="font-size: 12px;">
                                Diskon {{ $reward->discount_percentage }}% &bull;
                                Max Rp {{ number_format($reward->max_discount, 0, ',', '.') }}
                            </p>

                            <div class="reward-action-btn">
                                @if ($isEnough)
                                    <button class="btn-redeem-active"
                                        onclick="confirmRedeem('{{ $reward->name }}', {{ $reward->point_cost }}, {{ $reward->id }})">
                                        Redeem Now
                                    </button>
                                @else
                                    <button class="btn-redeem-locked" disabled>
                                        Need {{ number_format($reward->point_cost - $currentPoints) }} Pts
                                    </button>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>

            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <p class="text-muted">Belum ada voucher yang tersedia untuk ditukar.</p>
                    </div>
                </div>
            @endforelse
        </div>

    </div>
</div>

{{-- MODAL KONFIRMASI REDEEM --}}
<div class="jaced-modal-overlay" id="redeemModal">
    <div class="jaced-modal-box">

        {{-- STATE 1: Konfirmasi --}}
        <div id="modalConfirmState">
            <div class="modal-icon-wrap confirmation">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
            </div>
            <h3 class="modal-title">Redeem Voucher?</h3>
            <p class="modal-text">
                Tukarkan <strong id="modalRewardName"></strong> seharga <strong id="modalRewardPoints"></strong> poin?
            </p>
            <p class="modal-text mt-1" style="color: #c5221f; font-size: 12px;">Poin kamu akan berkurang setelah konfirmasi.</p>
        </div>

        {{-- STATE 2: Sukses --}}
        <div id="modalSuccessState" style="display: none;">
            <div class="modal-icon-wrap success">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <h3 class="modal-title">Voucher Berhasil!</h3>
            <p class="modal-text">Voucher sudah tersimpan di My Vouchers kamu.</p>
        </div>

        {{-- Form hidden submit ke controller --}}
        <form id="redeemForm" action="{{ route('reward.redeem') }}" method="POST">
            @csrf
            <input type="hidden" name="voucher_type_id" id="modalVoucherTypeId">
        </form>

        <div class="d-flex gap-2 mt-4" id="modalButtons">
            <button class="btn-modal-secondary" onclick="closeRedeemModal()">Batal</button>
            <button class="btn-modal-primary" id="btnConfirmAction" onclick="processRedeem()">Ya, Tukar</button>
        </div>

    </div>
</div>

@push('scripts')
<script>
    // === FILTER & SORT ===
    document.addEventListener('DOMContentLoaded', function () {
        const pills = document.querySelectorAll('.filter-pill');
        const affordableToggle = document.getElementById('affordableToggle');
        const sortSelect = document.getElementById('sortPoints');
        const container = document.getElementById('rewardsContainer');

        let currentCategory = 'all';
        let showAffordableOnly = false;

        pills.forEach(pill => {
            pill.addEventListener('click', function () {
                pills.forEach(p => p.classList.remove('active'));
                this.classList.add('active');
                currentCategory = this.getAttribute('data-category');
                applyFilters();
            });
        });

        affordableToggle.addEventListener('click', function () {
            this.classList.toggle('active');
            showAffordableOnly = this.classList.contains('active');
            applyFilters();
        });

        function applyFilters() {
            const items = container.querySelectorAll('.reward-item-card');
            items.forEach(item => {
                const cat = item.getAttribute('data-category');
                const isAffordable = item.getAttribute('data-affordable') === 'true';
                const matchCat = (currentCategory === 'all' || cat === currentCategory);
                const matchAffordable = (!showAffordableOnly || isAffordable);
                item.style.display = (matchCat && matchAffordable) ? 'block' : 'none';
            });
        }

        sortSelect.addEventListener('change', function () {
            const items = Array.from(container.querySelectorAll('.reward-item-card'));
            const sortBy = this.value;
            if (sortBy === 'default') return;
            items.sort((a, b) => {
                const ptsA = parseInt(a.getAttribute('data-points'));
                const ptsB = parseInt(b.getAttribute('data-points'));
                return sortBy === 'low' ? ptsA - ptsB : ptsB - ptsA;
            });
            items.forEach(item => container.appendChild(item));
        });
    });

    // === MODAL ===
    const modal = document.getElementById('redeemModal');
    const confirmState = document.getElementById('modalConfirmState');
    const successState = document.getElementById('modalSuccessState');
    const btnConfirm = document.getElementById('btnConfirmAction');

    function confirmRedeem(name, points, voucherTypeId) {
        document.getElementById('modalRewardName').innerText = name;
        document.getElementById('modalRewardPoints').innerText = points.toLocaleString('id-ID');
        document.getElementById('modalVoucherTypeId').value = voucherTypeId;

        confirmState.style.display = 'block';
        successState.style.display = 'none';
        document.getElementById('modalButtons').innerHTML = `
            <button class="btn-modal-secondary" onclick="closeRedeemModal()">Batal</button>
            <button class="btn-modal-primary" id="btnConfirmAction" onclick="processRedeem()">Ya, Tukar</button>
        `;
        modal.classList.add('show');
    }

    function processRedeem() {
        const btn = document.getElementById('btnConfirmAction');
        btn.innerText = "Processing...";
        btn.disabled = true;

        setTimeout(() => {
            confirmState.style.display = 'none';
            successState.style.display = 'block';
            document.getElementById('modalButtons').innerHTML = `
                <button class="btn-modal-primary" onclick="document.getElementById('redeemForm').submit()">
                    Lihat My Vouchers
                </button>
            `;
        }, 1000);
    }

    function closeRedeemModal() {
        modal.classList.remove('show');
    }

    modal.addEventListener('click', function (e) {
        if (e.target === modal) closeRedeemModal();
    });
</script>
@endpush

@endsection