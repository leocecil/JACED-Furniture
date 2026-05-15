<div class="jaced-card shadow-sm mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="fw-bold">12 Orders selected</div>
        <div class="d-flex gap-3">
            <button class="btn btn-link text-jaced-muted p-0 text-decoration-none small">Mark Shipped</button>
            <button class="btn btn-link text-jaced-muted p-0 text-decoration-none small">Download Invoice</button>
            <button class="btn btn-link text-danger p-0 text-decoration-none small">Archive</button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table align-middle" style="--bs-table-bg: transparent;">
            <thead>
                <tr class="text-jaced-muted small">
                    <th>ORDER ID</th>
                    <th>CUSTOMER</th>
                    <th>ORDER DATE</th>
                    <th>STATUS</th>
                    <th>AMOUNT</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr style="border-bottom: 1px solid var(--jaced-input);">
                    <td class="fw-bold small py-3">{{ $order['id'] }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle me-2 d-flex align-items-center justify-content-center fw-bold" 
                                 style="width: 32px; height: 32px; background: var(--jaced-input); color: var(--jaced-brown); font-size: 10px;">
                                {{ strtoupper(substr($order['customer'], 0, 2)) }}
                            </div>
                            <div>
                                <div class="fw-bold small">{{ $order['customer'] }}</div>
                                <div class="text-jaced-muted" style="font-size: 11px;">{{ $order['email'] }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="text-jaced-muted small">{{ $order['date'] }}</td>
                    <td>
                        <span class="badge rounded-pill px-3 py-2" 
                              style="background-color: var(--jaced-input); color: var(--jaced-brown-dark); font-size: 10px;">
                            {{ $order['status'] }}
                        </span>
                    </td>
                    <td class="fw-bold small">{{ $order['amount'] }}</td>
                    <td><i class="bi bi-eye text-jaced-muted"></i></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>