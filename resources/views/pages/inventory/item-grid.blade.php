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

@foreach($items as $item)
<div class="col-md-4">
    <div class="jaced-card shadow-sm p-0 overflow-hidden h-100 position-relative">
        <!-- Status Badge Overlapping Image -->
        <div class="position-absolute p-3 w-100 d-flex justify-content-between">
            <span class="badge px-3 py-2 fw-bold" style="background: {{ $item['color'] }}; color: {{ $item['text'] }}; font-size: 10px;">
                @if($item['status'] == 'LOW STOCK') <i class="bi bi-exclamation-triangle me-1"></i> @endif
                {{ $item['status'] }}
            </span>
        </div>

        <!-- Product Image Placeholder -->
        <div style="height: 200px; background: #e0e0e0; background-image: url('https://via.placeholder.com/400x300'); background-size: cover; background-position: center;"></div>

        <!-- Product Info -->
        <div class="p-4">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h5 class="fw-bold m-0" style="font-size: 1.1rem;">{{ $item['name'] }}</h5>
                <span class="fw-bold">{{ $item['price'] }}</span>
            </div>
            <p class="text-jaced-muted extra-small mb-4" style="font-size: 11px; letter-spacing: 0.5px;">
                {{ $item['cat'] }} • {{ $item['mat'] }}
            </p>
            
            <div class="d-flex justify-content-between align-items-center border-top pt-3 divider-jaced">
                <div>
                    <div class="text-jaced-muted small mb-1" style="font-size: 10px; font-weight: 600;">AVAILABLE UNITS</div>
                    <div class="fw-bold h5 m-0 {{ $item['status'] == 'LOW STOCK' ? 'text-danger' : '' }}">{{ $item['stock'] }}</div>
                </div>
                <button class="btn btn-light rounded-circle" style="width: 35px; height: 35px; padding: 0;">
                    <i class="bi bi-three-dots-vertical"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach