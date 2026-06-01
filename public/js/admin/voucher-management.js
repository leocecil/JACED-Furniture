    const config = window.voucherConfig;

    // ── Filter ───────────────────────────────────────────────────────
    window.toggleFilter = function toggleFilter() {
        document.getElementById('filterBar').classList.toggle('open');
    }
    window.applyFilters = function applyFilters() {
        const params = new URLSearchParams({
            type:         document.getElementById('fType').value,
            status:       document.getElementById('fStatus').value,
            min_discount: document.getElementById('fMinDiscount').value,
            max_discount: document.getElementById('fMaxDiscount').value,
            page: 1,
        });
        window.location.href = '?' + params.toString();
    }
    window.clearFilters = function clearFilters() {
        window.location.href = '?';
    }

    // ── Drawer ────────────────────────────────────────────────────────
    window.openDrawer = function openDrawer() {
        document.getElementById('drawer').classList.add('open');
        document.getElementById('drawerOverlay').classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    window.closeDrawer = function closeDrawer() {
        document.getElementById('drawer').classList.remove('open');
        document.getElementById('drawerOverlay').classList.remove('open');
        document.body.style.overflow = '';
    }

    // ── Point cost preview ────────────────────────────────────────────
    window.updatePointPreview = function updatePointPreview() {
        const maxDiscount = parseFloat(document.getElementById('dMaxDiscount').value.replace(/\./g, ''));
        const preview     = document.getElementById('pointPreview');
        const previewVal  = document.getElementById('pointPreviewValue');

        if (maxDiscount > 0) {
            const pts = Math.round(maxDiscount / 250);
            previewVal.textContent = pts.toLocaleString('id-ID') + ' pts';
            preview.style.display = 'flex';
        } else {
            preview.style.display = 'none';
        }
    }

    // ── Submit new voucher ────────────────────────────────────────────
    window.submitDrawer = function submitDrawer() {
        const usedFor  = document.querySelector('input[name="used_for"]:checked').value;
        const name     = document.getElementById('dName').value.trim();
        const desc     = document.getElementById('dDesc').value.trim();
        const discPct  = document.getElementById('dDiscountPct').value;
        const maxDisc  = document.getElementById('dMaxDiscount').value.replace(/\./g, '');
        const qty      = document.getElementById('dQuantity').value;

        if (!name || !desc || !discPct || !maxDisc || !qty) {
            showToast('⚠ Please fill in all fields.');
            return;
        }

        fetch(config.storeUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': config.csrf,
            },
            body: JSON.stringify({
                used_for:             usedFor,
                name:                 name,
                description:          desc,
                discount_percentage:  discPct,
                max_discount:         maxDisc,
                quantity:             qty,
            }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                closeDrawer();
                showToast('✓ ' + data.message);
                refreshStats();
                setTimeout(() => window.location.reload(), 1500);
            } else {
                showToast('Error: ' + (data.error || 'Something went wrong.'));
            }
        })
        .catch(() => showToast('Network error. Please try again.'));
    }

    // ── Toggle active/inactive ────────────────────────────────────────
    window.toggleVoucher = function toggleVoucher(id, btn) {
        fetch(`${config.baseUrl}/${id}/toggle`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': config.csrf,
            },
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const badge = document.getElementById('badge-' + id);
                const icon  = btn.querySelector('i');

                if (data.is_active) {
                    badge.textContent = 'Active';
                    badge.className   = 'badge-status badge-active';
                    icon.className    = 'bi bi-toggle-on';
                    btn.classList.remove('is-inactive');
                    btn.title = 'Deactivate';
                } else {
                    badge.textContent = 'Inactive';
                    badge.className   = 'badge-status badge-inactive';
                    icon.className    = 'bi bi-toggle-off';
                    btn.classList.add('is-inactive');
                    btn.title = 'Activate';
                }
                showToast('✓ ' + data.message);
                refreshStats();
            } else {
                showToast('Error: ' + (data.error || 'Something went wrong.'));
            }
        });
    }

    // ── Delete ────────────────────────────────────────────────────────
    window.deleteVoucher = function deleteVoucher(id, name) {
        if (!confirm(`Delete all "${name}" vouchers?\n\nThis cannot be undone.`)) return;

        fetch(`${config.baseUrl}/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': config.csrf,
            },
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showToast('✓ ' + data.message);
                refreshStats();
                setTimeout(() => window.location.reload(), 1500);
            } else {
                showToast('⚠ ' + data.error);
            }
        });
    }

    // ── Toast ─────────────────────────────────────────────────────────
    window.showToast = function showToast(msg) {
        const t = document.getElementById('toastMsg');
        t.textContent = msg;
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 3000);
    }

    window.refreshStats = function refreshStats() {
        fetch(config.statsUrl)
            .then(r => r.json())
            .then(data => {
                document.getElementById('statTotalTypes').textContent    = data.totalTypes;
                document.getElementById('statActiveTypes').textContent   = data.activeTypes;
                document.getElementById('statInactiveTypes').textContent = data.inactiveTypes + ' inactive';
                document.getElementById('statTotalRedeemed').textContent = data.totalRedeemed;
                document.getElementById('statTotalDiscount').textContent = data.totalDiscount;
                document.getElementById('statTotalDiscountFull').textContent = data.totalDiscountFull;
            });
    }

    window.viewUsedOrders = function viewUsedOrders(id, voucherName) {

        document.getElementById('usedOrdersTitle').textContent =
            `Orders Using "${voucherName}"`;

        document.getElementById('usedOrdersContent').innerHTML =
            `<p style="color:var(--jaced-muted);">Loading...</p>`;

        document.getElementById('usedOrdersDrawer').classList.add('open');
        document.getElementById('usedOrdersOverlay').classList.add('open');

        fetch(`${config.baseUrl}/${id}/used-orders`)
            .then(r => r.json())
            .then(data => {

                if (!data.success) {
                    document.getElementById('usedOrdersContent').innerHTML =
                        `<p style="color:#c0392b;">${data.error}</p>`;
                    return;
                }

                if (data.orders.length === 0) {
                    document.getElementById('usedOrdersContent').innerHTML = `
                        <p style="color:var(--jaced-muted);">
                            No orders have used this voucher yet.
                        </p>
                    `;
                    return;
                }

                let html = `
                    <table class="jaced-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                data.orders.forEach(order => {

                    const customer =
                        `${order.first_name ?? ''} ${order.last_name ?? ''}`;

                    html += `
                        <tr>
                            <td>#${order.id}</td>
                            <td>${customer}</td>
                            <td>Rp ${Number(order.total_price).toLocaleString('id-ID')}</td>
                            <td>${order.status}</td>
                            <td>
                                ${new Date(order.created_at).toLocaleDateString('id-ID')}
                            </td>
                        </tr>
                    `;
                });

                html += `</tbody></table>`;

                document.getElementById('usedOrdersContent').innerHTML = html;
            });
    }

    window.closeUsedOrdersModal = function closeUsedOrdersModal() {
        document.getElementById('usedOrdersDrawer').classList.remove('open');
        document.getElementById('usedOrdersOverlay').classList.remove('open');
    }

    window.formatRupiah = function formatRupiah(input) {
        // Ambil angka aja
        let value = input.value.replace(/\D/g, '');

        // Format jadi ribuan
        input.value = new Intl.NumberFormat('id-ID').format(value);
    }