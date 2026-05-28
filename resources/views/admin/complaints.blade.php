@extends('base.base')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/jaced.css') }}">
<style>
    .complaint-badge {
        font-size: 11px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 999px;
        text-transform: uppercase;
    }
    .complaint-badge.pending  { background: #fff3cd; color: #856404; }
    .complaint-badge.resolved { background: #e4f0e8; color: #4a7c59; }
    .complaint-badge.missing  { background: #f5e4e4; color: #a33d3d; }
    .complaint-badge.damaged  { background: #fff3cd; color: #856404; }
    .complaint-badge.wrong_item { background: #eeeef5; color: #5a5a8a; }
</style>
@endpush

@section('content')
<div style="max-width: 1000px; margin: 0 auto;">
    <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--jaced-brown-dark); margin-bottom: 24px;">
        Customer Complaints
    </h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($complaints->isEmpty())
        <div class="jaced-card p-5 text-center">
            <p class="text-muted">Tidak ada komplain masuk.</p>
        </div>
    @else
        <div class="d-flex flex-column gap-3">
            @foreach($complaints as $c)
            <div class="jaced-card p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="fw-bold mb-1" style="font-size: 15px;">
                            Order #{{ str_pad($c->order_id, 4, '0', STR_PAD_LEFT) }}
                            &nbsp;·&nbsp;
                            <span class="complaint-badge {{ $c->type }}">
                                {{ match($c->type) {
                                    'missing'    => 'Barang Hilang',
                                    'damaged'    => 'Barang Rusak',
                                    'wrong_item' => 'Salah Kirim',
                                    default      => $c->type
                                } }}
                            </span>
                        </p>
                        <p class="text-muted mb-0" style="font-size: 12px;">
                            {{ $c->customer_name }} · {{ $c->customer_email }}
                            &nbsp;·&nbsp; {{ \Carbon\Carbon::parse($c->created_at)->format('d M Y, H:i') }}
                        </p>
                    </div>
                    <span class="complaint-badge {{ $c->status }}">{{ ucfirst($c->status) }}</span>
                </div>

                <p style="font-size: 13px; color: var(--jaced-brown-dark);">{{ $c->description }}</p>

                @if($c->photo_path)
                    <img src="{{ asset('storage/' . $c->photo_path) }}"
                        alt="Bukti"
                        style="max-width: 200px; border-radius: 8px; margin-bottom: 12px;">
                @endif

                @if($c->admin_note)
                    <p style="font-size: 12px; color: var(--jaced-muted);">
                        <strong>Admin note:</strong> {{ $c->admin_note }}
                    </p>
                @endif

                @if($c->status === 'pending')
                <hr>
                <p class="fw-semibold mb-2" style="font-size: 13px;">Resolusi:</p>
                <form action="{{ route('admin.complaints.resolve', $c->id) }}" method="POST">
                    @csrf
                    <div class="row g-2">
                        <div class="col-12 col-md-4">
                            <select name="resolution" class="form-select form-select-sm" required>
                                <option value="">Pilih resolusi...</option>
                                <option value="refund_money">Refund Uang</option>
                                <option value="resend">Kirim Ulang Barang</option>
                                <option value="reject">Tolak Komplain</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <input type="number" name="refund_amount" class="form-control form-control-sm"
                                placeholder="Nominal refund (jika ada)"
                                max="{{ $c->total_price }}">
                        </div>
                        <div class="col-12 col-md-3">
                            <input type="text" name="admin_note" class="form-control form-control-sm"
                                placeholder="Catatan admin (opsional)">
                        </div>
                        <div class="col-12 col-md-2">
                            <button type="submit" class="btn btn-sm btn-dark w-100">
                                Resolve
                            </button>
                        </div>
                    </div>
                </form>
                @endif
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection