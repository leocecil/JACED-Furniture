@extends('base.base')

@section('content')

<div class="container-fluid px-4 py-3">

    {{-- Row 1: Stat Cards --}}
    <div class="row g-3 mb-3">

        {{-- Total Revenue --}}
        <div class="col-6 col-md-3">
            <div class="card h-100 p-3">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="stat-icon bg-primary-subtle rounded-3 p-2">
                        <i class="bi bi-receipt fs-4 text-primary"></i>
                    </div>
                    <span class="badge text-bg-warning rounded-pill">+12.5%</span>
                </div>
                <p class="text-muted small mb-1">Total Revenue</p>
                <h5 class="fw-bold mb-0">$128,430</h5>
            </div>
        </div>

        {{-- Total Orders --}}
        <div class="col-6 col-md-3">
            <div class="card h-100 p-3">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="stat-icon bg-primary-subtle rounded-3 p-2">
                        <i class="bi bi-basket fs-4 text-primary"></i>
                    </div>
                    <span class="badge text-bg-primary rounded-pill fw-normal">Monthly</span>
                </div>
                <p class="text-muted small mb-1">Total Orders</p>
                <h5 class="fw-bold mb-0">1,240</h5>
            </div>
        </div>

        {{-- In Delivery --}}
        <div class="col-6 col-md-3">
            <div class="card h-100 p-3">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="stat-icon bg-primary-subtle rounded-3 p-2">
                        <i class="bi bi-truck fs-4 text-primary"></i>
                    </div>
                    <span class="badge text-bg-info rounded-pill fw-normal">In Transit</span>
                </div>
                <p class="text-muted small mb-1">In Delivery</p>
                <h5 class="fw-bold mb-0">45</h5>
            </div>
        </div>

        {{-- Low Stock --}}
        <div class="col-6 col-md-3">
            <div class="card h-100 p-3">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="stat-icon bg-danger-subtle rounded-3 p-2">
                        <i class="bi bi-exclamation-triangle fs-4 text-danger"></i>
                    </div>
                    <span class="badge text-bg-danger rounded-pill fw-normal">Urgent</span>
                </div>
                <p class="text-muted small mb-1">Low Stock</p>
                <h5 class="fw-bold mb-0">12 items</h5>
            </div>
        </div>

    </div>

    {{-- Row 2: Sales Analytics | Monthly Target | Customer Traffic --}}
    <div class="row g-3 mb-3">

        {{-- Sales Analytics --}}
        <div class="col-12 col-md-5">
            <div class="card h-100 p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-semibold mb-0">Sales Analytics</h6>
                    <span class="text-muted small">Last 6 Months</span>
                </div>
                <div class="chart-wrapper" style="position: relative; height: 180px;">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Monthly Target --}}
        <div class="col-12 col-md-3">
            <div class="card h-100 p-3 text-center">
                <h6 class="fw-semibold mb-3">Monthly Target</h6>
                <div class="d-flex justify-content-center mb-3">
                    <div style="position: relative; width: 130px; height: 130px;">
                        <canvas id="targetChart"></canvas>
                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                            <span class="fw-bold fs-4">78%</span>
                        </div>
                    </div>
                </div>
                <p class="text-muted small mb-1">$17,000 / $125k</p>
                <p class="text-muted small mb-0">Remaining: <span class="text-dark fw-semibold">$30,000</span></p>
            </div>
        </div>

        {{-- Customer Traffic --}}
        <div class="col-12 col-md-4">
            <div class="card h-100 p-3">
                <h6 class="fw-semibold mb-3">Customer Traffic</h6>
                <div class="d-flex flex-column gap-3">

                    <div>
                        <div class="d-flex justify-content-between small mb-1">
                            <span class="text-muted">Organic Search</span>
                            <span class="fw-semibold">45%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-primary" style="width: 45%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="d-flex justify-content-between small mb-1">
                            <span class="text-muted">Social Media</span>
                            <span class="fw-semibold">30%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-info" style="width: 30%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="d-flex justify-content-between small mb-1">
                            <span class="text-muted">Referral</span>
                            <span class="fw-semibold">8%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-warning" style="width: 8%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="d-flex justify-content-between small mb-1">
                            <span class="text-muted">Direct</span>
                            <span class="fw-semibold">17%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success" style="width: 17%"></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- Row 3: Best Selling --}}
    <div class="col-12">
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-semibold mb-0">Best Selling Products</h6>
                <a href="#" class="small text-primary text-decoration-none">View All Products</a>
            </div>

            <div class="row g-3">

                <div class="col-12 col-md-4">
                    <div class="border rounded-3 p-3 d-flex align-items-center gap-3">
                        <img src="https://placehold.co/64x64" 
                            class="rounded-2 flex-shrink-0 object-fit-cover" 
                            style="width:64px; height:64px;" 
                            alt="Walnut Dining Table">
                        <div>
                            <p class="fw-semibold mb-0">Walnut Dining Table</p>
                            <p class="small text-muted mb-1">Craftsman Series • 24 Units</p>
                            <p class="fw-semibold mb-0">$2,800</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="border rounded-3 p-3 d-flex align-items-center gap-3">
                        <img src="https://placehold.co/64x64" 
                            class="rounded-2 flex-shrink-0 object-fit-cover" 
                            style="width:64px; height:64px;" 
                            alt="Eames-style Chair">
                        <div>
                            <p class="fw-semibold mb-0">Eames-style Chair</p>
                            <p class="small text-muted mb-1">Executive Lounge • 18 Units</p>
                            <p class="fw-semibold mb-0">$1,450</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="border rounded-3 p-3 d-flex align-items-center gap-3">
                        <img src="https://placehold.co/64x64" 
                            class="rounded-2 flex-shrink-0 object-fit-cover" 
                            style="width:64px; height:64px;" 
                            alt="Oak Sideboard">
                        <div>
                            <p class="fw-semibold mb-0">Oak Sideboard</p>
                            <p class="small text-muted mb-1">Studio Minimal • 14 Units</p>
                            <p class="fw-semibold mb-0">$1,200</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Row 4: Recent Orders --}}
    <div class="row g-3 mt-1">
        <div class="col-12">
            <div class="card p-3">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-semibold mb-0">Recent Orders</h6>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle border-0" 
                            type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-filter"></i> Filter By
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#">All Orders</a></li>
                            <li><a class="dropdown-item" href="#">On Site</a></li>
                            <li><a class="dropdown-item" href="#">Processing</a></li>
                            <li><a class="dropdown-item" href="#">Shipped</a></li>
                        </ul>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-borderless align-middle mb-0">
                        <thead>
                            <tr class="text-muted small border-bottom">
                                <th class="fw-normal pb-3">Order ID</th>
                                <th class="fw-normal pb-3">Customer</th>
                                <th class="fw-normal pb-3">Date</th>
                                <th class="fw-normal pb-3">Amount</th>
                                <th class="fw-normal pb-3">Status</th>
                                <th class="fw-normal pb-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr class="border-bottom">
                                <td class="small fw-semibold text-primary">#ORD-8821</td>
                                <td class="small">Jonathan Reed</td>
                                <td class="small text-muted">Oct 11, 2023</td>
                                <td class="small fw-semibold">$4,250.00</td>
                                <td>
                                    <span class="badge rounded-pill text-bg-success px-3 py-2 fw-normal">
                                        On Site
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-link text-muted p-0" 
                                            data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item small" href="#">View Detail</a></li>
                                            <li><a class="dropdown-item small" href="#">Edit</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item small text-danger" href="#">Delete</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>

                            <tr class="border-bottom">
                                <td class="small fw-semibold text-primary">#ORD-8820</td>
                                <td class="small">Sarah Jenkins</td>
                                <td class="small text-muted">Oct 11, 2023</td>
                                <td class="small fw-semibold">$1,800.00</td>
                                <td>
                                    <span class="badge rounded-pill text-bg-warning px-3 py-2 fw-normal">
                                        Processing
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-link text-muted p-0" 
                                            data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item small" href="#">View Detail</a></li>
                                            <li><a class="dropdown-item small" href="#">Edit</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item small text-danger" href="#">Delete</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td class="small fw-semibold text-primary">#ORD-8819</td>
                                <td class="small">Holloway Design Co.</td>
                                <td class="small text-muted">Oct 11, 2023</td>
                                <td class="small fw-semibold">$12,600.00</td>
                                <td>
                                    <span class="badge rounded-pill text-bg-primary px-3 py-2 fw-normal">
                                        Shipped
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-link text-muted p-0" 
                                            data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item small" href="#">View Detail</a></li>
                                            <li><a class="dropdown-item small" href="#">Edit</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item small text-danger" href="#">Delete</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

</div>

@endsection