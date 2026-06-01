const config = window.voucherConfig;

// ── Filter ───────────────────────────────────────────────────────────
window.toggleFilter = function () {
    document.getElementById('filterBar').classList.toggle('open');
};
window.applyFilters = function () {
    const params = new URLSearchParams({
        type:         document.getElementById('fType').value,
        status:       document.getElementById('fStatus').value,
        min_discount: document.getElementById('fMinDiscount').value,
        max_discount: document.getElementById('fMaxDiscount').value,
        page: 1,
    });
    window.location.href = '?' + params.toString();
};
window.clearFilters = function () { window.location.href = '?'; };

// ── Create Drawer ─────────────────────────────────────────────────────
window.openDrawer = function () {
    document.getElementById('drawer').classList.add('open');
    document.getElementById('drawerOverlay').classList.add('open');
    document.body.style.overflow = 'hidden';
};
window.closeDrawer = function () {
    document.getElementById('drawer').classList.remove('open');
    document.getElementById('drawerOverlay').classList.remove('open');
    if (!document.getElementById('detailPanel').classList.contains('open')) {
        document.body.style.overflow = '';
    }
};

// ── Point cost preview ────────────────────────────────────────────────
window.updatePointPreview = function () {
    const maxDiscount = parseFloat(document.getElementById('dMaxDiscount').value.replace(/\./g, ''));
    const preview     = document.getElementById('pointPreview');
    const previewVal  = document.getElementById('pointPreviewValue');
    if (maxDiscount > 0) {
        previewVal.textContent = Math.round(maxDiscount / 250).toLocaleString('id-ID') + ' pts';
        preview.style.display = 'flex';
    } else {
        preview.style.display = 'none';
    }
};

// ── Submit new voucher ────────────────────────────────────────────────
window.submitDrawer = function () {
    const usedFor = document.querySelector('input[name="used_for"]:checked').value;
    const name    = document.getElementById('dName').value.trim();
    const desc    = document.getElementById('dDesc').value.trim();
    const discPct = document.getElementById('dDiscountPct').value;
    const maxDisc = document.getElementById('dMaxDiscount').value.replace(/\./g, '');
    const qty     = document.getElementById('dQuantity').value;

    if (!name || !desc || !discPct || !maxDisc || !qty) {
        showToast('⚠ Please fill in all fields.');
        return;
    }

    fetch(config.storeUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': config.csrf },
        body: JSON.stringify({ used_for: usedFor, name, description: desc, discount_percentage: discPct, max_discount: maxDisc, quantity: qty }),
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
};

// ── Format Rupiah ─────────────────────────────────────────────────────
window.formatRupiah = function (input) {
    const value = input.value.replace(/\D/g, '');
    input.value = new Intl.NumberFormat('id-ID').format(value);
};

// ── Detail Panel ──────────────────────────────────────────────────────
let currentDetailId = null;

window.openDetailPanel = function (id) {
    // Mark active row
    document.querySelectorAll('tr.clickable-row').forEach(r => r.classList.remove('active-row'));
    const row = document.getElementById('row-' + id);
    if (row) row.classList.add('active-row');

    currentDetailId = id;

    const panel = document.getElementById('detailPanel');
    panel.classList.add('open');
    document.body.style.overflow = 'hidden';

    loadDetailPanel(id);
};

window.closeDetailPanel = function () {
    document.getElementById('detailPanel').classList.remove('open');
    document.querySelectorAll('tr.clickable-row').forEach(r => r.classList.remove('active-row'));
    document.body.style.overflow = '';
    currentDetailId = null;
};

function loadDetailPanel(id) {
    const panel = document.getElementById('detailPanel');

    // Show loading state
    panel.querySelector('.detail-panel-name').textContent = 'Loading…';
    panel.querySelector('.detail-meta-grid').innerHTML = '';
    panel.querySelector('.detail-used-count').textContent = '—';
    panel.querySelector('.detail-used-discount-val').textContent = '—';
    document.getElementById('detailTabCodes').innerHTML = '<div class="detail-loading"><div class="skel" style="height:48px"></div><div class="skel" style="height:48px"></div><div class="skel" style="height:48px"></div></div>';
    document.getElementById('detailTabOrders').innerHTML = '<div class="detail-loading"><div class="skel" style="height:60px"></div><div class="skel" style="height:60px"></div></div>';

    // Switch to first tab
    switchDetailTab('codes');

    fetch(`${config.baseUrl}/${id}/detail`)
        .then(r => r.json())
        .then(data => {
            if (!data.success) {
                showToast('Error: ' + data.error);
                return;
            }
            renderDetailPanel(data);
        })
        .catch(() => showToast('Failed to load voucher details.'));
}

function renderDetailPanel(data) {
    const panel = document.getElementById('detailPanel');

    // Name
    panel.querySelector('.detail-panel-name').textContent = data.name;

    // Type badge
    const typeIcon = data.used_for === 'product' ? '🛍️' : '🚚';
    const typeLabel = data.used_for === 'product' ? 'Product Discount' : 'Free Delivery';

    // Meta grid
    panel.querySelector('.detail-meta-grid').innerHTML = `
        <div class="detail-meta-item">
            <div class="detail-meta-item-label">Type</div>
            <div class="detail-meta-item-value">${typeIcon} ${typeLabel}</div>
        </div>
        <div class="detail-meta-item">
            <div class="detail-meta-item-label">Discount</div>
            <div class="detail-meta-item-value accent">${data.discount_percentage}%</div>
        </div>
        <div class="detail-meta-item">
            <div class="detail-meta-item-label">Max Discount</div>
            <div class="detail-meta-item-value">Rp ${Number(data.max_discount).toLocaleString('id-ID')}</div>
        </div>
        <div class="detail-meta-item">
            <div class="detail-meta-item-label">Point Cost</div>
            <div class="detail-meta-item-value sage">${Number(data.point_cost).toLocaleString('id-ID')} pts</div>
        </div>
    `;

    // Used banner
    panel.querySelector('.detail-used-count').textContent = data.total_used;
    panel.querySelector('.detail-used-discount-val').textContent =
        'Rp ' + Number(data.total_discount_given).toLocaleString('id-ID');

    // Codes tab
    renderCodesTab(data.codes, data.name, data.max_discount);

    // Orders tab
    renderOrdersTab(data.orders);
}

function renderCodesTab(codes, groupName, maxDiscount) {
    if (!codes || codes.length === 0) {
        document.getElementById('detailTabCodes').innerHTML = '<div class="detail-empty"><i class="bi bi-ticket-perforated"></i>No codes found.</div>';
        return;
    }

    let html = `
        <div class="detail-group-actions">
            <button class="btn-sm-outline" onclick="toggleGroup('${encodeURIComponent(groupName)}', '${encodeURIComponent(maxDiscount)}')">
                <i class="bi bi-toggle-on"></i> Toggle All
            </button>
            <button class="btn-sm-outline danger" onclick="deleteGroup('${currentDetailId}', '${encodeURIComponent(groupName)}')">
                <i class="bi bi-trash3"></i> Delete All
            </button>
        </div>
        <div class="codes-list">
    `;

    codes.forEach(code => {
        const isRedeemed = code.redeemed > 0;
        const statusBadge = isRedeemed
            ? '<span class="code-badge redeemed">Redeemed</span>'
            : code.is_active
                ? '<span class="code-badge active">Active</span>'
                : '<span class="code-badge inactive">Inactive</span>';

        const toggleIcon = code.is_active ? 'bi-toggle-on' : 'bi-toggle-off';
        const toggleClass = !code.is_active ? 'is-inactive' : '';
        const toggleTitle = code.is_active ? 'Deactivate' : 'Activate';

        html += `
            <div class="code-item ${!code.is_active ? 'is-inactive' : ''}" id="code-item-${code.id}">
                <div>
                    <div class="code-id">${code.id}</div>
                    <div class="code-meta">${code.assigned} assigned · ${code.redeemed} redeemed</div>
                </div>
                <div class="code-actions">
                    ${statusBadge}
                    <button class="code-btn ${toggleClass}" id="code-toggle-${code.id}"
                        title="${toggleTitle}"
                        onclick="toggleCode('${code.id}', this)">
                        <i class="bi ${toggleIcon}"></i>
                    </button>
                    ${!isRedeemed ? `
                    <button class="code-btn danger" title="Delete code"
                        onclick="deleteCode('${code.id}', '${encodeURIComponent(groupName)}')">
                        <i class="bi bi-trash3"></i>
                    </button>` : ''}
                </div>
            </div>
        `;
    });

    html += `</div>`;
    document.getElementById('detailTabCodes').innerHTML = html;
}

function renderOrdersTab(orders) {
    if (!orders || orders.length === 0) {
        document.getElementById('detailTabOrders').innerHTML =
            '<div class="detail-empty"><i class="bi bi-bag-x"></i>No orders have used this voucher yet.</div>';
        return;
    }

    const statusClass = s => {
        if (['completed','arrived','delivered'].includes(s)) return 'completed';
        if (s === 'pending') return 'pending';
        if (s === 'cancelled') return 'cancelled';
        return 'default';
    };

    let html = '<div class="orders-list">';
    orders.forEach(order => {
        const customer = `${order.first_name ?? ''} ${order.last_name ?? ''}`.trim() || 'Unknown';
        const date = new Date(order.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
        html += `
            <div class="order-item">
                <div class="order-item-top">
                    <span class="order-id">#${order.id}</span>
                    <span class="order-discount">−Rp ${Number(order.discount_amount).toLocaleString('id-ID')}</span>
                </div>
                <div class="order-item-bottom">
                    <span class="order-customer">${customer} · <span style="font-family:monospace;font-size:10px;">${order.voucher_code}</span></span>
                    <span class="order-date">${date}</span>
                </div>
                <div style="margin-top:6px; display:flex; align-items:center; justify-content:space-between;">
                    <span class="order-status-badge ${statusClass(order.status)}">${order.status}</span>
                    <span style="font-size:12px; color:var(--jaced-muted);">Total: Rp ${Number(order.total_price).toLocaleString('id-ID')}</span>
                </div>
            </div>
        `;
    });
    html += '</div>';
    document.getElementById('detailTabOrders').innerHTML = html;
}

window.switchDetailTab = function (tab) {
    document.querySelectorAll('.detail-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.detail-tab-content').forEach(c => c.classList.remove('active'));
    document.querySelector(`.detail-tab[data-tab="${tab}"]`).classList.add('active');
    document.getElementById(`detailTab${tab.charAt(0).toUpperCase() + tab.slice(1)}`).classList.add('active');
};

// ── Toggle single code ────────────────────────────────────────────────
window.toggleCode = function (id, btn) {
    fetch(`${config.baseUrl}/${id}/toggle`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': config.csrf },
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const icon = btn.querySelector('i');
            const item = document.getElementById('code-item-' + id);
            if (data.is_active) {
                icon.className = 'bi bi-toggle-on';
                btn.classList.remove('is-inactive');
                btn.title = 'Deactivate';
                item.classList.remove('is-inactive');
                // Update badge
                item.querySelector('.code-badge').outerHTML = '<span class="code-badge active">Active</span>';
            } else {
                icon.className = 'bi bi-toggle-off';
                btn.classList.add('is-inactive');
                btn.title = 'Activate';
                item.classList.add('is-inactive');
                item.querySelector('.code-badge').outerHTML = '<span class="code-badge inactive">Inactive</span>';
            }
            showToast('✓ ' + data.message);
            refreshStats();
        } else {
            showToast('Error: ' + (data.error || 'Something went wrong.'));
        }
    });
};

// ── Toggle group ──────────────────────────────────────────────────────
window.toggleGroup = function (encodedName) {
    const name = decodeURIComponent(encodedName);
    fetch(`${config.baseUrl}/group/toggle`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': config.csrf },
        body: JSON.stringify({ name }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast('✓ ' + data.message);
            refreshStats();
            // Reload detail panel to reflect new states
            if (currentDetailId) loadDetailPanel(currentDetailId);
        } else {
            showToast('Error: ' + (data.error || 'Something went wrong.'));
        }
    });
};

// ── Delete single code ────────────────────────────────────────────────
window.deleteCode = function (id, encodedName) {
    const name = decodeURIComponent(encodedName);
    if (!confirm(`Delete code "${id}"?\n\nThis cannot be undone.`)) return;

    fetch(`${config.baseUrl}/${id}/code`, {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': config.csrf },
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast('✓ ' + data.message);
            // Remove from DOM
            const item = document.getElementById('code-item-' + id);
            if (item) item.remove();
            refreshStats();
        } else {
            showToast('⚠ ' + data.error);
        }
    });
};

// ── Delete group ──────────────────────────────────────────────────────
window.deleteGroup = function (id, encodedName) {
    const name = decodeURIComponent(encodedName);
    if (!confirm(`Delete ALL "${name}" vouchers?\n\nThis cannot be undone.`)) return;

    fetch(`${config.baseUrl}/${id}`, {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': config.csrf },
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast('✓ ' + data.message);
            closeDetailPanel();
            refreshStats();
            setTimeout(() => window.location.reload(), 1500);
        } else {
            showToast('⚠ ' + data.error);
        }
    });
};

// ── Stats refresh ─────────────────────────────────────────────────────
window.refreshStats = function () {
    fetch(config.statsUrl)
        .then(r => r.json())
        .then(data => {
            document.getElementById('statTotalTypes').textContent    = data.totalTypes;
            document.getElementById('statActiveTypes').textContent   = data.activeTypes;
            document.getElementById('statInactiveTypes').textContent = data.inactiveTypes;
            document.getElementById('statTotalRedeemed').textContent = data.totalRedeemed;
            document.getElementById('statTotalDiscount').textContent = data.totalDiscountFull;
        });
};

// ── Toast ─────────────────────────────────────────────────────────────
window.showToast = function (msg) {
    const t = document.getElementById('toastMsg');
    t.textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3000);
};