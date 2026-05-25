@extends('base.base')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/jaced.css') }}">
<style>
    .addresses-page {
        background-color: var(--jaced-cream);
        min-height: 100vh;
        padding: 40px 16px 80px;
    }
    .address-card {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--jaced-input);
        padding: 20px 24px;
        margin-bottom: 12px;
        position: relative;
        transition: all .2s;
    }
    .address-card.is-default {
        border-color: var(--jaced-sage);
        border-left: 4px solid var(--jaced-sage);
    }
    .badge-default {
        font-size: 10px;
        font-weight: 700;
        color: var(--jaced-sage);
        background: #e8ede8;
        border: 1px solid var(--jaced-sage);
        border-radius: 999px;
        padding: 2px 10px;
    }
    .btn-address-action {
        background: none;
        border: none;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 6px;
        transition: all .15s;
    }
    .btn-address-edit { color: var(--jaced-caramel); }
    .btn-address-edit:hover { background: var(--jaced-caramel-bg); }
    .btn-address-delete { color: #a33d3d; }
    .btn-address-delete:hover { background: #f5e4e4; }
    .btn-address-default { color: var(--jaced-sage); }
    .btn-address-default:hover { background: #e8ede8; }
    .btn-add-new {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 14px;
        background: white;
        border: 1.5px dashed var(--jaced-sage);
        color: var(--jaced-sage);
        font-size: 13px;
        font-weight: 600;
        border-radius: 12px;
        cursor: pointer;
        transition: all .2s;
        margin-top: 4px;
    }
    .btn-add-new:hover { background: #f0f4f0; }

    .toast-notif {
        position: fixed;
        top: 24px;
        left: 50%;
        transform: translateX(-50%) translateY(-10px);
        background: var(--jaced-brown-dark);
        color: white;
        padding: 14px 20px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 500;
        z-index: 9999;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 4px 20px rgba(0,0,0,.15);
        opacity: 0;
        transition: all .3s ease;
        white-space: nowrap;
    }
    .toast-notif.show {
        transform: translateX(-50%) translateY(0);
        opacity: 1;
    }
    .toast-notif.success { background: var(--jaced-sage); }
    .toast-notif.error { background: #a33d3d; }
</style>
@endpush

@section('content')
<div class="addresses-page">
    <div style="max-width: 640px; margin: 0 auto;">

        <a href="{{ route('profile') }}" class="back-link">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            <span>Back</span>
        </a>

        <h3 class="fw-bold mb-1" style="color: var(--jaced-brown-dark);">Shipping Addresses</h3>
        <p class="mb-4" style="font-size: 13px; color: var(--jaced-muted);">Manage your saved delivery addresses.</p>

        {{-- ADDRESS LIST --}}
        @forelse ($addresses as $addr)
            <div class="address-card {{ $addr->is_default ? 'is-default' : '' }}">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="fw-bold" style="font-size: 15px; color: var(--jaced-brown-dark);">
                            {{ $addr->receiver_name }}
                        </span>
                        <span style="color: var(--jaced-input);">|</span>
                        <span style="font-size: 13px; color: var(--jaced-muted);">{{ $addr->receiver_phone }}</span>
                        @if($addr->is_default)
                            <span class="badge-default">Utama</span>
                        @endif
                    </div>
                </div>

                <p class="mb-1" style="font-size: 13px; color: var(--jaced-brown-dark);">
                    {{ $addr->address_line1 }}
                </p>
                <p class="mb-3" style="font-size: 12px; color: var(--jaced-muted);">
                    {{ $addr->village_name }}, {{ $addr->district_name }}, {{ $addr->city_name }}, {{ $addr->province_name }}, {{ $addr->postal_code }}
                </p>

                <div class="d-flex gap-2 flex-wrap">
                    {{-- Edit --}}
                    <button class="btn-address-action btn-address-edit"
                            onclick="openEditModal({{ json_encode($addr) }})">
                        ✏️ Edit
                    </button>

                    {{-- Set Default --}}
                    @if(!$addr->is_default)
                        <form action="{{ route('profile.addresses.default', $addr->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn-address-action btn-address-default">
                                ⭐ Set as Default
                            </button>
                        </form>
                    @endif

                    {{-- Delete --}}
                    @if(!$addr->is_default || $addresses->count() === 1)
                        <form action="{{ route('profile.addresses.destroy', $addr->id) }}" method="POST"
                              onsubmit="return confirm('Hapus alamat ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-address-action btn-address-delete">
                                🗑️ Hapus
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-5" style="color: var(--jaced-muted); font-size: 14px;">
                Kamu belum memiliki alamat tersimpan.
            </div>
        @endforelse

        {{-- ADD NEW BUTTON --}}
        <button class="btn-add-new" onclick="openAddModal()">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Tambah Alamat Baru
        </button>

    </div>
</div>

{{-- MODAL ADD / EDIT --}}
<div class="modal fade" id="addressModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 14px; border: none;">
            <div class="modal-header" style="border-bottom: 1px solid var(--jaced-input); background: #faf9f6;">
                <h5 class="modal-title fw-bold" id="modalTitle" style="color: var(--jaced-brown-dark);">Tambah Alamat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="addressForm" method="POST">
                @csrf
                <span id="methodSpoof"></span>

                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label" style="font-size: 13px; font-weight: 600;">Nama Penerima</label>
                            <input type="text" name="receiver_name" id="f_receiver_name" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" style="font-size: 13px; font-weight: 600;">Nomor Telepon</label>
                            <input type="text" name="receiver_phone" id="f_receiver_phone" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label" style="font-size: 13px; font-weight: 600;">Alamat Lengkap</label>
                            <input type="text" name="address_line1" id="f_address_line1" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" style="font-size: 13px; font-weight: 600;">Provinsi</label>
                            <select name="province_code" id="f_province" class="form-select form-select-sm" onchange="loadCities(this.value)" required>
                                <option value="">Pilih Provinsi</option>
                                @foreach ($provinces as $p)
                                    <option value="{{ $p->code }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="province_name" id="f_province_name">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" style="font-size: 13px; font-weight: 600;">Kota / Kabupaten</label>
                            <select name="city_code" id="f_city" class="form-select form-select-sm" onchange="loadDistricts(this.value)" disabled required>
                                <option value="">Pilih Kota</option>
                            </select>
                            <input type="hidden" name="city_name" id="f_city_name">
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" style="font-size: 13px; font-weight: 600;">Kecamatan</label>
                            <select name="district_code" id="f_district" class="form-select form-select-sm" onchange="loadVillages(this.value)" disabled>
                                <option value="">Pilih Kecamatan</option>
                            </select>
                            <input type="hidden" name="district_name" id="f_district_name">
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" style="font-size: 13px; font-weight: 600;">Kelurahan</label>
                            <select name="village_code" id="f_village" class="form-select form-select-sm" disabled>
                                <option value="">Pilih Kelurahan</option>
                            </select>
                            <input type="hidden" name="village_name" id="f_village_name">
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" style="font-size: 13px; font-weight: 600;">Kode Pos</label>
                            <input type="text" name="postal_code" id="f_postal_code" class="form-control form-control-sm" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer" style="border-top: 1px solid var(--jaced-input); background: #faf9f6;">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm text-white" style="background-color: var(--jaced-sage);">Simpan Alamat</button>
                </div>
            </form>
        </div>
    </div>
</div>
    @if(session('success'))
        <div class="toast-notif success" id="toastNotif">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
            <span>{{ session('success') }}</span>
            <button onclick="closeToast()" style="background:none; border:none; color:white; cursor:pointer; padding:0; margin-left:8px; opacity:.7; line-height:1;">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="toast-notif error" id="toastNotif">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
            <span>{{ session('error') }}</span>
            <button onclick="closeToast()" style="background:none; border:none; color:white; cursor:pointer; padding:0; margin-left:8px; opacity:.7; line-height:1;">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
    @endif

@push('scripts')
<script>
    const bsModal = new bootstrap.Modal(document.getElementById('addressModal'));

    function openAddModal() {
        document.getElementById('modalTitle').innerText = 'Tambah Alamat Baru';
        document.getElementById('addressForm').action = '{{ route('profile.addresses.store') }}';
        document.getElementById('methodSpoof').innerHTML = '';

        // Reset semua field
        ['f_receiver_name','f_receiver_phone','f_address_line1','f_postal_code','f_province_name','f_city_name','f_district_name','f_village_name'].forEach(id => {
            document.getElementById(id).value = '';
        });
        document.getElementById('f_province').value = '';
        resetSelect('f_city', 'Pilih Kota', true);
        resetSelect('f_district', 'Pilih Kecamatan', true);
        resetSelect('f_village', 'Pilih Kelurahan', true);

        bsModal.show();
    }

    function openEditModal(addr) {
        document.getElementById('modalTitle').innerText = 'Edit Alamat';
        document.getElementById('addressForm').action = `/profile/addresses/${addr.id}`;
        document.getElementById('methodSpoof').innerHTML = `<input type="hidden" name="_method" value="PUT">`;

        document.getElementById('f_receiver_name').value  = addr.receiver_name;
        document.getElementById('f_receiver_phone').value = addr.receiver_phone;
        document.getElementById('f_address_line1').value  = addr.address_line1;
        document.getElementById('f_postal_code').value    = addr.postal_code;
        document.getElementById('f_province_name').value  = addr.province_name;
        document.getElementById('f_city_name').value      = addr.city_name;
        document.getElementById('f_district_name').value  = addr.district_name;
        document.getElementById('f_village_name').value   = addr.village_name;

        // Set province
        document.getElementById('f_province').value = addr.province_code;

        // Set kota (pre-fill tanpa fetch ulang)
        const citySelect = document.getElementById('f_city');
        citySelect.innerHTML = `<option value="${addr.city_code}" selected>${addr.city_name}</option>`;
        citySelect.disabled = false;

        const distSelect = document.getElementById('f_district');
        distSelect.innerHTML = `<option value="${addr.district_code}" selected>${addr.district_name}</option>`;
        distSelect.disabled = false;

        const villSelect = document.getElementById('f_village');
        villSelect.innerHTML = `<option value="${addr.village_code}" selected>${addr.village_name}</option>`;
        villSelect.disabled = false;

        bsModal.show();
    }

    function resetSelect(id, placeholder, disable) {
        const el = document.getElementById(id);
        el.innerHTML = `<option value="">${placeholder}</option>`;
        el.disabled = disable;
    }

    // Update hidden name fields saat dropdown berubah
    document.getElementById('f_province').addEventListener('change', function() {
        const opt = this.options[this.selectedIndex];
        document.getElementById('f_province_name').value = opt.text !== 'Pilih Provinsi' ? opt.text : '';
    });

    document.getElementById('f_city').addEventListener('change', function() {
        const opt = this.options[this.selectedIndex];
        document.getElementById('f_city_name').value = opt.text !== 'Pilih Kota' ? opt.text : '';
    });

    document.getElementById('f_district').addEventListener('change', function() {
        const opt = this.options[this.selectedIndex];
        document.getElementById('f_district_name').value = opt.text !== 'Pilih Kecamatan' ? opt.text : '';
    });

    document.getElementById('f_village').addEventListener('change', function() {
        const opt = this.options[this.selectedIndex];
        document.getElementById('f_village_name').value = opt.text !== 'Pilih Kelurahan' ? opt.text : '';
    });

    function loadCities(provinceCode) {
        resetSelect('f_city', 'Pilih Kota', true);
        resetSelect('f_district', 'Pilih Kecamatan', true);
        resetSelect('f_village', 'Pilih Kelurahan', true);
        if (!provinceCode) return;

        fetch(`/api/cities?province_code=${provinceCode}`)
            .then(r => r.json())
            .then(cities => {
                const sel = document.getElementById('f_city');
                cities.forEach(c => sel.innerHTML += `<option value="${c.code}">${c.name}</option>`);
                sel.disabled = false;
            });
    }

    function loadDistricts(cityCode) {
        resetSelect('f_district', 'Pilih Kecamatan', true);
        resetSelect('f_village', 'Pilih Kelurahan', true);
        if (!cityCode) return;

        fetch(`/api/districts?city_code=${cityCode}`)
            .then(r => r.json())
            .then(districts => {
                const sel = document.getElementById('f_district');
                districts.forEach(d => sel.innerHTML += `<option value="${d.code}">${d.name}</option>`);
                sel.disabled = false;
            });
    }

    function loadVillages(districtCode) {
        resetSelect('f_village', 'Pilih Kelurahan', true);
        if (!districtCode) return;

        fetch(`/api/villages?district_code=${districtCode}`)
            .then(r => r.json())
            .then(villages => {
                const sel = document.getElementById('f_village');
                villages.forEach(v => sel.innerHTML += `<option value="${v.code}">${v.name}</option>`);
                sel.disabled = false;
            });
    }
    // Toast notification
    const toast = document.getElementById('toastNotif');
    let toastTimer;

    function closeToast() {
        if (!toast) return;
        clearTimeout(toastTimer);
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }

    if (toast) {
        setTimeout(() => toast.classList.add('show'), 100);
        toastTimer = setTimeout(() => closeToast(), 3000);
    }
</script>
@endpush

@endsection