@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h2 class="fw-bold mb-1">Inventory Ledger</h2>
            <p class="text-jaced-muted small">Manage your premium stock items, monitor material availability, and track upcoming shipments.</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary px-4 py-2" style="border-color: var(--jaced-input); color: var(--jaced-brown-dark);">
                <i class="bi bi-box-arrow-up me-2"></i> Export
            </button>
            <button class="btn btn-jaced-primary px-4 py-2">
                <i class="bi bi-plus-lg me-2"></i> Add New Item
            </button>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom divider-jaced">
        <div class="d-flex gap-3 overflow-auto">
            <button class="btn btn-sm rounded-pill px-3 py-1 fw-bold" style="background: var(--jaced-caramel); color: white;">All Collections</button>
            <button class="btn btn-sm text-jaced-muted px-3 py-1">Seating</button>
            <button class="btn btn-sm text-jaced-muted px-3 py-1">Tables</button>
            <button class="btn btn-sm text-jaced-muted px-3 py-1">Storage</button>
            <button class="btn btn-sm text-jaced-muted px-3 py-1">Lighting</button>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="text-jaced-muted small fw-bold"><i class="bi bi-filter-left me-1"></i> SORT BY: NEWEST</span>
            <div class="bg-white rounded p-1 shadow-sm d-flex gap-1">
                <button class="btn btn-sm p-1"><i class="bi bi-grid-fill"></i></button>
                <button class="btn btn-sm p-1 text-jaced-muted"><i class="bi bi-list"></i></button>
            </div>
        </div>
    </div>

    <!-- Inventory Grid -->
    <div class="row g-4">
        @include('pages.inventory.item-grid')
    </div>

    <!-- Footer Action -->
    <div class="text-center mt-5">
        <p class="text-jaced-muted small mb-3">Showing 6 of 124 furniture items</p>
        <button class="btn btn-outline-secondary px-5" style="border-color: var(--jaced-input); color: var(--jaced-brown-dark);">Load More Stock</button>
    </div>
</div>
@endsection