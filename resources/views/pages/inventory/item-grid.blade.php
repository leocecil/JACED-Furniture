<style>
    .inv-view-btn { width: 32px; height: 32px; border: none; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 15px; cursor: pointer; transition: all 0.15s; background: transparent; color: #9c9890; }
    .inv-view-btn.active { background: #1a1a18; color: #f5f2ee; }
    .inv-view-btn:not(.active):hover { background: #f0eeeb; color: #1a1a18; }

    /* Grid */
    #inventoryContainer.view-grid { display: flex; flex-wrap: wrap; gap: 20px; }
    #inventoryContainer.view-grid .inv-item { width: calc(33.333% - 14px); }
    @media (max-width: 992px) { #inventoryContainer.view-grid .inv-item { width: calc(50% - 10px); } }
    @media (max-width: 576px) { #inventoryContainer.view-grid .inv-item { width: 100%; } }

    /* List */
    #inventoryContainer.view-list { display: flex; flex-direction: column; gap: 10px; }
    #inventoryContainer.view-list .inv-item { width: 100%; }

    .inv-item .grid-card { display: block; }
    .inv-item .list-card { display: none; }
    #inventoryContainer.view-list .inv-item .grid-card { display: none; }
    #inventoryContainer.view-list .inv-item .list-card {
        display: flex; align-items: center; gap: 16px;
        background: #fff; border: 1px solid #e2ddd8;
        border-radius: 12px; padding: 12px 16px; transition: box-shadow 0.2s;
    }
    #inventoryContainer.view-list .inv-item .list-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.07); }

    /* Grid card */
    .grid-card { background: #fff; border: 1px solid #e2ddd8; border-radius: 14px; overflow: hidden; transition: box-shadow 0.2s, transform 0.2s; }
    .grid-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.08); transform: translateY(-2px); }
    .grid-card-img { width: 100%; height: 200px; object-fit: cover; background: #f0eeeb; display: block; }

    /* List card */
    .list-card-img { width: 68px; height: 68px; border-radius: 10px; object-fit: cover; background: #f0eeeb; flex-shrink: 0; }
    .list-card-info { flex: 1; min-width: 0; }
    .list-card-name { font-size: 14px; font-weight: 700; color: #1a1a18; margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .list-card-meta { font-size: 11px; color: #9c9890; }
    .list-card-price { font-size: 15px; font-weight: 700; color: #1a1a18; white-space: nowrap; }

    /* Shared */
    .item-badge-cat { font-size: 10px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; background: #f0eeeb; color: #6b6860; padding: 3px 8px; border-radius: 4px; display: inline-block; }
    .item-badge-label { font-size: 10px; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; background: #1e1c18; color: #c4a882; padding: 3px 8px; border-radius: 4px; display: inline-block; }
    .item-inactive { opacity: 0.55; }

    .stock-badge { font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px; white-space: nowrap; }
    .badge-instock  { background: #eaf7ec; color: #1d7a35; }
    .badge-lowstock { background: #fef9e7; color: #9a7d0a; }
    .badge-outstock { background: #fdecea; color: #c0392b; }

    .action-btn { width: 30px; height: 30px; border-radius: 6px; border: 1px solid #e2ddd8; background: #fff; display: inline-flex; align-items: center; justify-content: center; font-size: 13px; cursor: pointer; transition: all 0.15s; }
    .action-btn:hover { background: #f0eeeb; }
    .action-btn.delete { color: #c0392b; border-color: #f5c6c2; background: #fef8f7; }
    .action-btn.delete:hover { background: #fdecea; }

    .price-old { font-size: 12px; color: #9c9890; text-decoration: line-through; margin-right: 4px; }
</style>

{{-- Toggle Buttons --}}
<div id="inv-toggle-wrap" class="d-flex gap-1 p-1 rounded-3" style="background: #f0eeeb; border: 1px solid #e2ddd8;">
    <button class="inv-view-btn active" id="btnGrid" onclick="setInvView('grid')" title="Grid view">
        <i class="bi bi-grid-fill"></i>
    </button>
    <button class="inv-view-btn" id="btnList" onclick="setInvView('list')" title="List view">
        <i class="bi bi-list-ul"></i>
    </button>
</div>

{{-- Container --}}
<div id="inventoryContainer" class="view-grid mt-3">

    @forelse($products as $product)

        @php
            $thumb = $product->main_image
                ? asset('storage/' . $product->main_image)
                : ($product->images->first()?->image_path
                    ? asset('storage/' . $product->images->first()->image_path)
                    : 'https://placehold.co/400x300/e8e4df/6b6860?text=No+Image');

            $priceFormatted = 'Rp ' . number_format($product->price, 0, ',', '.');
            $oldPriceFormatted = $product->old_price ? 'Rp ' . number_format($product->old_price, 0, ',', '.') : null;

            if ($product->stock <= 0)     { $badgeClass = 'badge-outstock'; $badgeText = 'Out of Stock'; }
            elseif ($product->stock <= 5) { $badgeClass = 'badge-lowstock'; $badgeText = 'Low Stock (' . $product->stock . ')'; }
            else                           { $badgeClass = 'badge-instock';  $badgeText = $product->stock . ' units'; }
        @endphp

        <div class="inv-item {{ !$product->is_active ? 'item-inactive' : '' }}">

            {{-- ══ GRID CARD ══ --}}
            <div class="grid-card">
                <div class="position-relative">
                    <img src="{{ $thumb }}" alt="{{ $product->name }}" class="grid-card-img">
                    @if($product->badge)
                        <span class="position-absolute"
                              style="top:10px; left:10px; background:#1e1c18; color:#c4a882; font-size:10px; font-weight:700; padding:3px 8px; border-radius:4px; text-transform:uppercase; letter-spacing:.05em;">
                            {{ $product->badge }}
                        </span>
                    @endif
                    @if(!$product->is_active)
                        <span class="position-absolute"
                              style="top:10px; right:10px; background:#fdecea; color:#c0392b; font-size:10px; font-weight:700; padding:3px 8px; border-radius:4px;">
                            Inactive
                        </span>
                    @endif
                </div>

                <div class="p-3">
                    <div class="mb-2">
                        <span class="item-badge-cat">{{ $product->category?->name ?? '—' }}</span>
                        @if($product->is_recommended)
                            <span class="ms-1" style="font-size:10px; font-weight:700; background:#fef9e7; color:#9a7d0a; padding:3px 8px; border-radius:4px;">★ Recommended</span>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <div style="font-size:14px; font-weight:700; color:#1a1a18; flex:1; padding-right:8px;">{{ $product->name }}</div>
                        <div class="text-end flex-shrink-0">
                            @if($oldPriceFormatted)
                                <div class="price-old">{{ $oldPriceFormatted }}</div>
                            @endif
                            <div style="font-size:15px; font-weight:700; color:#1a1a18;">{{ $priceFormatted }}</div>
                        </div>
                    </div>

                    @if($product->short_description)
                        <p style="font-size:12px; color:#9c9890; margin-bottom:10px; overflow:hidden; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical;">
                            {{ $product->short_description }}
                        </p>
                    @endif

                    <div class="d-flex align-items-center justify-content-between mt-2">
                        <span class="stock-badge {{ $badgeClass }}">{{ $badgeText }}</span>
                        <div class="d-flex gap-1">
                            <button class="action-btn" title="Edit" onclick="openEditModal({{ json_encode($product) }})">
                                <i class="bi bi-pencil"></i>
                            </button>
                            
                            <form action="{{ route('inventory.destroy', $product->id) }}" method="POST"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan {{ addslashes($product->name) }}?')">
                                @csrf 
                                @method('DELETE')
                                <button type="submit" class="action-btn delete" title="Delete"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ══ LIST CARD ══ --}}
            <div class="list-card">
                <img src="{{ $thumb }}" alt="{{ $product->name }}" class="list-card-img">

                <div class="list-card-info">
                    <div class="list-card-name">{{ $product->name }}</div>
                    <div class="list-card-meta">
                        {{ $product->category?->name ?? '—' }}
                        @if($product->badge) · <span style="color:#c4a882;">{{ $product->badge }}</span> @endif
                        @if(!$product->is_active) · <span style="color:#c0392b;">Inactive</span> @endif
                    </div>
                </div>

                @if($product->is_recommended)
                    <span style="font-size:10px; font-weight:700; background:#fef9e7; color:#9a7d0a; padding:3px 8px; border-radius:4px; white-space:nowrap;" class="d-none d-md-inline">★ Featured</span>
                @endif

                <span class="stock-badge {{ $badgeClass }} d-none d-md-inline">{{ $badgeText }}</span>

                <div class="text-end flex-shrink-0">
                    @if($oldPriceFormatted)
                        <div class="price-old">{{ $oldPriceFormatted }}</div>
                    @endif
                    <div class="list-card-price">{{ $priceFormatted }}</div>
                </div>

                <div class="d-flex gap-1 flex-shrink-0">
                    <button class="action-btn" title="Edit" onclick="openEditModal({{ json_encode($product) }})">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <form action="{{ route('inventory.destroy', $product->id) }}" method="POST"
                          onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan {{ addslashes($product->name) }}?')">
                        @csrf 
                        @method('DELETE')
                        <button type="submit" class="action-btn delete" title="Delete"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            </div>

        </div>
    @empty
        <div style="width:100%; text-align:center; padding:60px 0; color:#9c9890;">
            <i class="bi bi-box-seam" style="font-size:3rem; opacity:.2; display:block; margin-bottom:12px;"></i>
            <p class="fw-semibold">No products found.</p>
            <p style="font-size:13px;">Click <strong>Add New Item</strong> to add your first product.</p>
        </div>
    @endforelse
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
    document.addEventListener('DOMContentLoaded', function () {
        if (localStorage.getItem('invView') === 'list') setInvView('list');
    });

    // Fungsi untuk melempar data lama ke dalam form modal edit
    function openEditModal(product) {
        // Logika penyiapan boks modal kustom bisa ditaruh di sini sewaktu-waktu
        console.log("Mengedit produk:", product);
        // Contoh memicu trigger Bootstrap Modal:
        // jQuery('#editItemModal').modal('show');
    }
</script>