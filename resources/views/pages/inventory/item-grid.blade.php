@php
    $items = [
        ['name' => 'Sculptural Lounge', 'price' => '$4,250', 'cat' => 'SEATING', 'mat' => 'PREMIUM WALNUT', 'stock' => '02', 'status' => 'LOW STOCK', 'color' => '#f8d7da', 'text' => '#721c24'],
        ['name' => 'Solid Oak Plenum', 'price' => '$3,800', 'cat' => 'TABLES', 'mat' => 'EUROPEAN OAK', 'stock' => '12', 'status' => 'IN DELIVERY', 'color' => '#cfe2ff', 'text' => '#084298'],
        ['name' => 'Linear Credenza', 'price' => '$2,400', 'cat' => 'STORAGE', 'mat' => 'STEEL & MAHOGANY', 'stock' => '08', 'status' => 'AVAILABLE', 'color' => '#fff3cd', 'text' => '#856404'],
        ['name' => 'Carrara Pivot', 'price' => '$1,850', 'cat' => 'TABLES', 'mat' => 'ITALIAN MARBLE', 'stock' => '03', 'status' => 'LOW STOCK', 'color' => '#f8d7da', 'text' => '#721c24'],
        ['name' => 'Velvet Horizon', 'price' => '$5,600', 'cat' => 'SEATING', 'mat' => 'MOHAIR VELVET', 'stock' => '05', 'status' => 'AVAILABLE', 'color' => '#fff3cd', 'text' => '#856404'],
        ['name' => 'Smoked Oak Dresser', 'price' => '$3,200', 'cat' => 'STORAGE', 'mat' => 'SMOKED OAK', 'stock' => '15', 'status' => 'IN DELIVERY', 'color' => '#cfe2ff', 'text' => '#084298'],
    ];
@endphp

<style>
    /* ══════════════════════════
       GRID VIEW
    ══════════════════════════ */
    #inventoryContainer {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    /* Grid mode: 3 kolom */
    #inventoryContainer.view-grid .inv-item {
        width: calc(33.333% - 14px);
    }
    @media (max-width: 992px) {
        #inventoryContainer.view-grid .inv-item { width: calc(50% - 10px); }
    }
    @media (max-width: 576px) {
        #inventoryContainer.view-grid .inv-item { width: 100%; }
    }

    /* List mode: full width */
    #inventoryContainer.view-list .inv-item {
        width: 100%;
    }

    /* ══════════════════════════
       GRID CARD (tampilan default)
    ══════════════════════════ */
    .inv-item .grid-view {
        display: block;
    }
    .inv-item .list-view {
        display: none;
    }

    /* ══════════════════════════
       LIST CARD
    ══════════════════════════ */
    #inventoryContainer.view-list .inv-item .grid-view {
        display: none;
    }
    #inventoryContainer.view-list .inv-item .list-view {
        display: flex;
        align-items: center;
        gap: 16px;
        background: #fff;
        border: 1px solid #e2ddd8;
        border-radius: 14px;
        padding: 14px 18px;
        transition: box-shadow 0.2s;
    }
    #inventoryContainer.view-list .inv-item .list-view:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.07);
    }

    .list-view-img {
        width: 68px; height: 68px;
        border-radius: 10px;
        object-fit: cover;
        background: #e0e0e0;
        flex-shrink: 0;
    }
    .list-view-info { flex: 1; min-width: 0; }
    .list-view-name { font-size: 14px; font-weight: 700; color: #1a1a18; margin-bottom: 2px; }
    .list-view-meta { font-size: 11px; color: #9c9890; letter-spacing: 0.3px; }
    .list-view-price { font-size: 15px; font-weight: 700; color: #1a1a18; white-space: nowrap; }
    .list-view-stock { text-align: center; min-width: 60px; }
    .list-view-stock .units-label { font-size: 10px; font-weight: 600; color: #9c9890; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px; }
    .list-view-stock .units-value { font-size: 18px; font-weight: 700; color: #1a1a18; }

    /* Toggle buttons */
    .inv-view-btn {
        width: 32px; height: 32px;
        border: none; border-radius: 6px;
        display: flex; align-items: center; justify-content: center;
        font-size: 15px; cursor: pointer;
        transition: all 0.15s; background: transparent; color: #9c9890;
    }
    .inv-view-btn.active { background: #1a1a18; color: #f5f2ee; }
    .inv-view-btn:not(.active):hover { background: #f0eeeb; color: #1a1a18; }
</style>

{{-- ── View Toggle (replace tombol lama di index.blade.php) ── --}}
{{-- Tombol ini menggantikan .bg-white.rounded-3 yang ada di filter bar --}}
<div id="inv-toggle-wrap" class="d-flex gap-1 p-1 rounded-3" style="background: #f0eeeb; border: 1px solid #e2ddd8;">
    <button class="inv-view-btn active" id="btnGrid" onclick="setInvView('grid')" title="Grid view">
        <i class="bi bi-grid-fill"></i>
    </button>
    <button class="inv-view-btn" id="btnList" onclick="setInvView('list')" title="List view">
        <i class="bi bi-list-ul"></i>
    </button>
</div>

{{-- ── Container ── --}}
<div id="inventoryContainer" class="view-grid mt-3">

    @foreach($items as $item)
    <div class="inv-item">

        {{-- ══ GRID CARD ══ --}}
        <div class="grid-view jaced-card shadow-sm p-0 overflow-hidden h-100 position-relative">
            {{-- Status Badge --}}
            <div class="position-absolute p-3 w-100 d-flex justify-content-between" style="z-index:1;">
                <span class="badge px-3 py-2 fw-bold"
                      style="background: {{ $item['color'] }}; color: {{ $item['text'] }}; font-size: 10px;">
                    @if($item['status'] == 'LOW STOCK')
                        <i class="bi bi-exclamation-triangle me-1"></i>
                    @endif
                    {{ $item['status'] }}
                </span>
            </div>

            {{-- Image --}}
            <div style="height: 200px; background: #e0e0e0; background-image: url('https://placehold.co/400x300/e8e4df/6b6860?text={{ urlencode($item['name']) }}'); background-size: cover; background-position: center;"></div>

            {{-- Info --}}
            <div class="p-4">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="fw-bold m-0" style="font-size: 1.1rem;">{{ $item['name'] }}</h5>
                    <span class="fw-bold">{{ $item['price'] }}</span>
                </div>
                <p class="text-jaced-muted mb-4" style="font-size: 11px; letter-spacing: 0.5px;">
                    {{ $item['cat'] }} &bull; {{ $item['mat'] }}
                </p>
                <div class="d-flex justify-content-between align-items-center border-top pt-3 divider-jaced">
                    <div>
                        <div class="text-jaced-muted mb-1" style="font-size: 10px; font-weight: 600; text-transform:uppercase; letter-spacing:.5px;">Available Units</div>
                        <div class="fw-bold h5 m-0 {{ $item['status'] == 'LOW STOCK' ? 'text-danger' : '' }}">
                            {{ $item['stock'] }}
                        </div>
                    </div>
                    <button class="btn btn-light rounded-circle" style="width: 35px; height: 35px; padding: 0;">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- ══ LIST CARD ══ --}}
        <div class="list-view">
            {{-- Gambar kecil --}}
            <div class="list-view-img"
                 style="background-image: url('https://placehold.co/68x68/e8e4df/6b6860?text={{ urlencode(substr($item['name'],0,3)) }}'); background-size: cover; background-position: center;">
            </div>

            {{-- Nama & meta --}}
            <div class="list-view-info">
                <div class="list-view-name">{{ $item['name'] }}</div>
                <div class="list-view-meta">{{ $item['cat'] }} &bull; {{ $item['mat'] }}</div>
            </div>

            {{-- Status badge --}}
            <span class="badge px-3 py-2 fw-bold d-none d-md-inline"
                  style="background: {{ $item['color'] }}; color: {{ $item['text'] }}; font-size: 10px; white-space:nowrap;">
                @if($item['status'] == 'LOW STOCK')
                    <i class="bi bi-exclamation-triangle me-1"></i>
                @endif
                {{ $item['status'] }}
            </span>

            {{-- Stock --}}
            <div class="list-view-stock d-none d-sm-block">
                <div class="units-label">Units</div>
                <div class="units-value {{ $item['status'] == 'LOW STOCK' ? 'text-danger' : '' }}">
                    {{ $item['stock'] }}
                </div>
            </div>

            {{-- Harga --}}
            <div class="list-view-price">{{ $item['price'] }}</div>

            {{-- Actions --}}
            <button class="btn btn-light rounded-circle flex-shrink-0" style="width:35px; height:35px; padding:0;">
                <i class="bi bi-three-dots-vertical"></i>
            </button>
        </div>

    </div>
    @endforeach

</div>

<script>
    function setInvView(view) {
        const container = document.getElementById('inventoryContainer');
        const btnGrid   = document.getElementById('btnGrid');
        const btnList   = document.getElementById('btnList');

        if (view === 'grid') {
            container.className = 'view-grid mt-3';
            btnGrid.classList.add('active');
            btnList.classList.remove('active');
        } else {
            container.className = 'view-list mt-3';
            btnList.classList.add('active');
            btnGrid.classList.remove('active');
        }
        localStorage.setItem('invView', view);
    }

    // Restore view dari localStorage saat halaman dibuka
    document.addEventListener('DOMContentLoaded', function () {
        const saved = localStorage.getItem('invView');
        if (saved === 'list') setInvView('list');
    });
</script>