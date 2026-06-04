<style>
    .inv-view-btn { width: 32px; height: 32px; border: none; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 15px; cursor: pointer; transition: all 0.15s; background: transparent; color: #9c9890; }
    .inv-view-btn.active { background: #1a1a18; color: #f5f2ee; }
    .inv-view-btn:not(.active):hover { background: #f0eeeb; color: #1a1a18; }

    /* Grid layout */
    #inventoryContainer.view-grid { display: flex; flex-wrap: wrap; gap: 20px; }
    #inventoryContainer.view-grid .inv-item { width: calc(33.333% - 14px); }
    @media (max-width: 992px) { #inventoryContainer.view-grid .inv-item { width: calc(50% - 10px); } }
    @media (max-width: 576px) { #inventoryContainer.view-grid .inv-item { width: 100%; } }

    /* List layout */
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

    /* Grid card system */
    .grid-card { background: #fff; border: 1px solid #e2ddd8; border-radius: 14px; overflow: hidden; transition: box-shadow 0.2s, transform 0.2s; }
    .grid-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.08); transform: translateY(-2px); }
    .grid-card-img { width: 100%; height: 200px; object-fit: cover; background: #f0eeeb; display: block; }

    /* List card system */
    .list-card-img { width: 68px; height: 68px; border-radius: 10px; object-fit: cover; background: #f0eeeb; flex-shrink: 0; }
    .list-card-info { flex: 1; min-width: 0; }
    .list-card-name { font-size: 14px; font-weight: 700; color: #1a1a18; margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .list-card-meta { font-size: 11px; color: #9c9890; }
    .list-card-price { font-size: 15px; font-weight: 700; color: #1a1a18; white-space: nowrap; }

    /* Shared components styling */
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
    
    /* Image Uploader Style Inside Modal */
    .image-preview-item { position: relative; width: 80px; height: 80px; border-radius: 8px; overflow: hidden; border: 1px solid #e2ddd8; }
    .image-preview-item img { width: 100%; height: 100%; object-fit: cover; }
    .image-preview-item .remove-img { position: absolute; top: 2px; right: 2px; background: rgba(192, 57, 43, 0.85); color: white; border: none; border-radius: 50%; width: 18px; height: 18px; font-size: 11px; display: flex; align-items: center; justify-content: center; cursor: pointer; }
    .image-upload-area { border: 2px dashed #e2ddd8; padding: 20px; text-align: center; border-radius: 10px; background: #faf9f7; position: relative; cursor: pointer; }
    .image-upload-area input[type="file"] { position: absolute; top:0; left:0; width:100%; height:100%; opacity:0; cursor:pointer; }
    .modal-section-title { font-size: 12px; font-weight: 700; text-transform: uppercase; color: #5a4d47; letter-spacing: 0.05em; margin: 20px 0 10px; padding-bottom: 4px; border-bottom: 1px solid #f0eeeb; }
</style>

{{-- Toggle Buttons View Layout --}}
<div id="inv-toggle-wrap" class="d-flex gap-1 p-1 rounded-3" style="background: #f0eeeb; border: 1px solid #e2ddd8;">
    <button class="inv-view-btn active" id="btnGrid" onclick="setInvView('grid')" title="Grid view">
        <i class="bi bi-grid-fill"></i>
    </button>
    <button class="inv-view-btn" id="btnList" onclick="setInvView('list')" title="List view">
        <i class="bi bi-list-ul"></i>
    </button>
</div>

{{-- Main Container Dynamic Ledger --}}
<div id="inventoryContainer" class="view-grid mt-3">

    @forelse($products as $product)
        @php
            // ── PERBAIKAN MEMBACA GAMBAR MURNI DARI FOLDER PUBLIC/IMAGE ──
            if ($product->main_image) {
                $cleanPath = str_starts_with($product->main_image, 'image/') ? $product->main_image : 'image/' . $product->main_image;
                $thumb = asset($cleanPath);
            } elseif ($product->images->first()?->image_path) {
                $cleanPath = str_starts_with($product->images->first()->image_path, 'image/') ? $product->images->first()->image_path : 'image/' . $product->images->first()->image_path;
                $thumb = asset($cleanPath);
            } else {
                $thumb = 'https://placehold.co/400x300/e8e4df/6b6860?text=No+Image';
            }

            $priceFormatted = 'Rp ' . number_format($product->price, 0, ',', '.');
            $oldPriceFormatted = $product->old_price ? 'Rp ' . number_format($product->old_price, 0, ',', '.') : null;

            if ($product->stock <= 0)     { $badgeClass = 'badge-outstock'; $badgeText = 'Out of Stock'; }
            elseif ($product->stock <= 5) { $badgeClass = 'badge-lowstock'; $badgeText = 'Low Stock (' . $product->stock . ')'; }
            else                           { $badgeClass = 'badge-instock';  $badgeText = $product->stock . ' units'; }

            $productPayload = [
                'id'          => $product->id,
                'name'        => $product->name,
                'description' => $product->description,
                'length'      => $product->length,
                'width'       => $product->width,
                'height'      => $product->height,
                'unit'        => $product->unit,
                'price'       => $product->price,
                'stock'       => $product->stock,
                'low_stock'   => $product->low_stock,
                'label'       => $product->label,
                'category_id' => $product->category_id,
                'images'      => $product->images->map(fn($img) => [
                    'id'         => $img->id,
                    'image_path' => $img->image_path,
                    'is_main'    => $img->is_main,
                ])->values()->toArray(),
            ];
        @endphp

        <div class="inv-item {{ !$product->is_active ? 'item-inactive' : '' }}">

            {{-- ══ VIEW 1: GRID CARD ══ --}}
            <div class="grid-card">
                <div class="position-relative">
                    <img src="{{ $thumb }}" alt="{{ $product->name }}" class="grid-card-img">
                    @if($product->label)
                        <span class="position-absolute"
                              style="top:10px; left:10px; background:#1e1c18; color:#c4a882; font-size:10px; font-weight:700; padding:3px 8px; border-radius:4px; text-transform:uppercase; letter-spacing:.05em;">
                            {{ $product->label }}
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

                    @if($product->description)
                        <p style="font-size:12px; color:#9c9890; margin-bottom:10px; overflow:hidden; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical;">
                            {{ $product->description }}
                        </p>
                    @endif

                    <div class="d-flex align-items-center justify-content-between mt-2">
                        <span class="stock-badge {{ $badgeClass }}">{{ $badgeText }}</span>
                        <div class="d-flex gap-1">
                            <button type="button" class="action-btn" title="Edit" onclick="openEditModal({{ json_encode($productPayload) }})">
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

            {{-- ══ VIEW 2: LIST CARD ══ --}}
            <div class="list-card">
                <img src="{{ $thumb }}" alt="{{ $product->name }}" class="list-card-img">

                <div class="list-card-info">
                    <div class="list-card-name">{{ $product->name }}</div>
                    <div class="list-card-meta">
                        {{ $product->category?->name ?? '—' }}
                        @if($product->label) · <span style="color:#c4a882;">{{ $product->label }}</span> @endif
                        @if(!$product->is_active) · <span style="color:#c0392b;">Inactive</span> @endif
                    </div>
                </div>

                <span class="stock-badge {{ $badgeClass }} d-none d-md-inline">{{ $badgeText }}</span>

                <div class="text-end flex-shrink-0">
                    @if($oldPriceFormatted)
                        <div class="price-old">{{ $oldPriceFormatted }}</div>
                    @endif
                    <div class="list-card-price">{{ $priceFormatted }}</div>
                </div>

                <div class="d-flex gap-1 flex-shrink-0">
                    <button type="button" class="action-btn" title="Edit" onclick="openEditModal({{ json_encode($productPayload) }})">
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
        if (!container) return;

        if (view === 'grid') {
            container.className = 'view-grid mt-3';
            if(btnGrid) btnGrid.classList.add('active');
            if(btnList) btnList.classList.remove('active');
        } else {
            container.className = 'view-list mt-3';
            if(btnList) btnList.classList.add('active');
            if(btnGrid) btnGrid.classList.remove('active');
        }
        localStorage.setItem('invView', view);
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (localStorage.getItem('invView') === 'list') setInvView('list');
    });
</script>

@push('modals')
<div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow" style="border-radius: 16px;">

            <div class="modal-header border-0 pt-4 px-4 pb-0">
                <h5 class="modal-title fw-bold" id="editItemModalLabel">Edit Product</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>

            <form id="editProductForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="modal-body px-4 pb-2" style="max-height: 70vh; overflow-y: auto;">

                    <div class="modal-section-title">Basic Information</div>
                    <div class="mb-3">
                        <label class="form-label">Product Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" id="edit_name" placeholder="e.g., Sculptural Lounge Chair" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" id="edit_description" rows="3" placeholder="Describe the product..." style="resize: none;"></textarea>
                    </div>

                    <div class="modal-section-title">Dimensions</div>
                    <div class="row g-3 mb-3">
                        <div class="col-4">
                            <label class="form-label">Length <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">L</span>
                                <input type="number" class="form-control" name="length" id="edit_length" placeholder="0" min="0" step="0.1" required>
                            </div>
                        </div>
                        <div class="col-4">
                            <label class="form-label">Width <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">W</span>
                                <input type="number" class="form-control" name="width" id="edit_width" placeholder="0" min="0" step="0.1" required>
                            </div>
                        </div>
                        <div class="col-4">
                            <label class="form-label">Height <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">H</span>
                                <input type="number" class="form-control" name="height" id="edit_height" placeholder="0" min="0" step="0.1" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Unit (Satuan) <span class="text-danger">*</span></label>
                        <select class="form-select" name="unit" id="edit_unit" required>
                            <option value="cm">cm — Centimeter</option>
                            <option value="m">m — Meter</option>
                            <option value="inch">inch — Inch</option>
                        </select>
                    </div>

                    <div class="modal-section-title">Pricing & Stock</div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Price <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" name="price" id="edit_price" placeholder="0" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Stock <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-box-seam" style="font-size:12px;"></i></span>
                                <input type="number" class="form-control" name="stock" id="edit_stock" placeholder="0" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Low Stock Alert <span class="text-muted fw-normal">(threshold)</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-exclamation-triangle" style="font-size:12px;"></i></span>
                                <input type="number" class="form-control" name="low_stock" id="edit_low_stock" placeholder="5" min="0">
                            </div>
                        </div>
                    </div>

                    <div class="modal-section-title">Category & Label</div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select class="form-select" name="category_id" id="edit_category_id" required>
                                <option value="" disabled>Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Label <span class="text-muted fw-normal">(tag produk)</span></label>
                            <input type="text" class="form-control" name="label" id="edit_label" placeholder="e.g., Bestseller, New Arrival">
                        </div>
                    </div>

                    <div class="modal-section-title">Current Images</div>
                    <div class="d-flex flex-wrap gap-2 mb-3" id="editExistingImages"></div>

                    <div class="modal-section-title">Add New Images</div>
                    <div class="image-upload-area">
                        <input type="file" name="images[]" id="editImageInput" accept="image/*" multiple onchange="previewEditImages(this)">
                        <i class="bi bi-cloud-upload fs-3 text-muted d-block mb-2"></i>
                        <div style="font-size:13px; font-weight:600; color:#3a3a36;">Click or drag & drop to add more images</div>
                        <div style="font-size:11px; color:#9c9890; margin-top:4px;">JPG, PNG, WEBP — max 2MB each</div>
                    </div>
                    <div class="image-preview-wrap" id="editImagePreviewWrap"></div>

                </div>

                <div class="modal-footer border-0 pb-4 px-4 pt-3 d-flex gap-2">
                    <button type="button" class="btn btn-sm flex-grow-1 py-2 rounded-3" data-bs-dismiss="modal" style="background:#f0eeeb; color:#1a1a18; border:none;">Cancel</button>
                    <button type="submit" class="btn btn-sm flex-grow-1 py-2 rounded-3 fw-bold" style="background:#c4a882; color:white; border:none;"><i class="bi bi-check-lg me-1"></i> Update Product</button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
function openEditModal(product) {
    const form = document.getElementById('editProductForm');
    if (!form) return;
    
    form.action = '/admin/inventory/' + product.id;

    document.getElementById('edit_name').value        = product.name        ?? '';
    document.getElementById('edit_description').value = product.description ?? '';
    document.getElementById('edit_length').value      = product.length      ?? '';
    document.getElementById('edit_width').value       = product.width       ?? '';
    document.getElementById('edit_height').value      = product.height      ?? '';
    document.getElementById('edit_price').value       = product.price       ?? '';
    document.getElementById('edit_stock').value       = product.stock       ?? '';
    document.getElementById('edit_low_stock').value   = product.low_stock   ?? 5;
    document.getElementById('edit_label').value       = product.label       ?? '';

    document.getElementById('edit_unit').value        = product.unit        ?? 'cm';
    document.getElementById('edit_category_id').value = product.category_id ?? '';

    document.getElementById('editImagePreviewWrap').innerHTML = '';
    document.getElementById('editImageInput').value = '';

    const existingWrap = document.getElementById('editExistingImages');
    existingWrap.innerHTML = '';

    if (product.images && product.images.length > 0) {
        product.images.forEach(function (img) {
            // ── PERBAIKAN JS: Mengarahkan path pratinjau foto lama langsung ke /image/ ──
            const cleanPath = img.image_path.startsWith('image/') ? img.image_path : 'image/' + img.image_path;
            const imgUrl = '/' + cleanPath;
            
            const div = document.createElement('div');
            div.className = 'image-preview-item';
            div.dataset.imgId = img.id;
            div.innerHTML = `
                <img src="${imgUrl}" alt="">
                <button type="button" class="remove-img"
                        onclick="deleteExistingImage(${img.id}, this)"
                        title="Remove image">×</button>
            `;
            existingWrap.appendChild(div);
        });
    } else {
        existingWrap.innerHTML = '<p class="text-muted small">No images yet.</p>';
    }

    const modalElement = document.getElementById('editItemModal');
    const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
    modal.show();
}

function previewEditImages(input) {
    const wrap = document.getElementById('editImagePreviewWrap');
    if (!wrap) return;
    
    Array.from(input.files).forEach(file => {
        if (!file.type.startsWith('image/')) return;
        const reader = new FileReader();
        reader.onload = e => {
            const div = document.createElement('div');
            div.className = 'image-preview-item';
            div.innerHTML = `
                <img src="${e.target.result}" alt="">
                <button type="button" class="remove-img"
                        onclick="this.closest('.image-preview-item').remove()">×</button>`;
            wrap.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}

function deleteExistingImage(imageId, btn) {
    if (!confirm('Remove this image permanently?')) return;

    fetch('/admin/inventory/image/' + imageId, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            btn.closest('.image-preview-item').remove();
        } else {
            alert('Failed to delete image.');
        }
    })
    .catch(() => alert('Something went wrong.'));
}
</script>
@endpush