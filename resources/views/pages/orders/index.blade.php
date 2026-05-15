@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold">Order Management</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item text-uppercase small">Studio</li>
                    <li class="breadcrumb-item active text-uppercase small">Active Orders</li>
                </ol>
            </nav>
        </div>
        <div>
            <button class="btn btn-outline-secondary btn-sm me-2"><i class="bi bi-filter"></i> Advanced Filters</button>
            <button class="btn btn-outline-secondary btn-sm"><i class="bi bi-download"></i> Export Manifest</button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3">
                <small class="text-muted fw-bold">PROCESSING</small>
                <h3 class="fw-bold">24</h3>
            </div>
        </div>
        <!-- Copy for In Production, Out for Delivery, etc -->
        <div class="col-md-3">
            <div class="card border-0 bg-dark text-white shadow-sm p-3">
                <small class="opacity-75 fw-bold">WEEKLY REVENUE</small>
                <h3 class="fw-bold">$124,500</h3>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    @include('pages.orders.table')

    <!-- Featured Item Section -->
    <div class="row mt-4">
        <div class="col-md-8">
            @include('pages.orders.featured-item')
        </div>
        <div class="col-md-4">
            <!-- Placeholder for Fulfillment Efficiency Chart -->
            <div class="card border-0 shadow-sm p-4 text-center">
                <h6 class="fw-bold">FULFILLMENT EFFICIENCY</h6>
                <div class="display-4 fw-bold my-3">85%</div>
                <p class="small text-muted">On Track for Q4 Delivery Goals</p>
            </div>
        </div>
    </div>
</div>
@endsection