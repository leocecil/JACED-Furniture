    let pendingOrderId    = null;
    let pendingNextStatus = null;
    let searchTimer       = null;

    const statusColors = {
        unpaid:     { bg: '#FFF3E0', color: '#E65100' },
        on_process: { bg: '#E8EAF6', color: '#283593' },
        packed:     { bg: '#E3F2FD', color: '#1565C0' },
        delivered:  { bg: '#F3E5F5', color: '#6A1B9A' },
        shipped:    { bg: '#E0F7FA', color: '#00695C' },
        arrived:    { bg: '#E8F5E9', color: '#2E7D32' },
        cancelled:  { bg: '#FFEBEE', color: '#C62828' },
    };

    // ── Live search (debounced) ───────────────────────────────────────
    document.getElementById('searchInput').addEventListener('input', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => window.fetchOrders(1), 400);
    });

    // ── Intercept pagination clicks ───────────────────────────────────
    document.addEventListener('click', function (e) {
        const pageLink = e.target.closest('.pagination .page-link');
        if (pageLink) {
            e.preventDefault();
            const urlString = pageLink.getAttribute('href');
            if (urlString) {
                try {
                    const url  = new URL(urlString, window.location.origin);
                    const page = url.searchParams.get('page');
                    if (page) window.fetchOrders(page);
                } catch (err) {
                    console.error('Pagination error:', err);
                }
            }
        }
    });

    // ── Clear filters ─────────────────────────────────────────────────
    window.clearFilters = function() {
        ['searchInput','filterDateFrom','filterDateTo'].forEach(id => document.getElementById(id).value = '');
        ['filterStatus','filterPayment'].forEach(id => document.getElementById(id).value = 'all');
        fetchOrders(1);
    }

    // ── AJAX fetch orders ─────────────────────────────────────────────
    window.fetchOrders = function(page = 1) {
        const params = new URLSearchParams({
            search:    document.getElementById('searchInput').value,
            status:    document.getElementById('filterStatus').value,
            payment:   document.getElementById('filterPayment').value,
            date_from: document.getElementById('filterDateFrom').value,
            date_to:   document.getElementById('filterDateTo').value,
            page,
        });

        fetch(`${window.orderSearchUrl}?${params}`)
        .then(r => r.json())
        .then(data => {
            document.getElementById('orderTableBody').innerHTML = data.html;

            if (data.pagination) {
                document.getElementById('paginationLinksContainer').innerHTML = data.pagination;
            }

            const info = document.getElementById('paginationInfo');

            if (info) {
                info.textContent = data.total > 0
                    ? `Showing ${data.from}–${data.to} of ${data.total} orders`
                    : 'No orders found';
            }

            info.textContent = data.total > 0
                ? `Showing ${data.from}–${data.to} of ${data.total} orders`
                : 'No orders found';
        })
        .catch(err => {
            console.error(err);
            window.showToast('Failed to load orders.');
        });
    }

    // ── Toggle panel — only one open at a time ────────────────────────
    window.togglePanel = function(id) {
        const panel   = document.getElementById('panel-' + id);
        const chev    = document.getElementById('chev-' + id);
        const chevMob = document.getElementById('chev-mob-' + id);
        const isOpen  = panel.style.display !== 'none';

        // Close all other open panels first
        document.querySelectorAll('[id^="panel-"]').forEach(p => {
            if (p.id !== 'panel-' + id && p.style.display !== 'none') {
                const otherId = p.id.replace('panel-', '');
                p.style.display = 'none';
                const oc  = document.getElementById('chev-' + otherId);
                const ocm = document.getElementById('chev-mob-' + otherId);
                if (oc)  oc.style.transform  = '';
                if (ocm) ocm.style.transform = '';
            }
        });

        // Toggle the clicked panel
        panel.style.display = isOpen ? 'none' : 'block';
        if (chev)    chev.style.transform    = isOpen ? '' : 'rotate(180deg)';
        if (chevMob) chevMob.style.transform = isOpen ? '' : 'rotate(180deg)';
    }

    // ── Status modal ──────────────────────────────────────────────────
    window.openStatusModal = function(orderId, currentStatus, nextStatus, label) {
        pendingOrderId    = orderId;
        pendingNextStatus = nextStatus;

        const curr = statusColors[currentStatus] || { bg:'#F5F5F5', color:'#616161' };
        const next = statusColors[nextStatus]    || { bg:'#F5F5F5', color:'#616161' };

        document.getElementById('modalOrderId').textContent = '#ORD-' + String(orderId).padStart(4, '0');

        const cc = document.getElementById('modalCurrentChip');
        cc.textContent      = currentStatus.replace('_',' ').replace(/\b\w/g, c => c.toUpperCase());
        cc.style.background = curr.bg;
        cc.style.color      = curr.color;

        const nc = document.getElementById('modalNextChip');
        nc.textContent      = nextStatus.replace('_',' ').replace(/\b\w/g, c => c.toUpperCase());
        nc.style.background = next.bg;
        nc.style.color      = next.color;

        document.getElementById('modalConfirmLabel').textContent = label;
        document.getElementById('statusModalOverlay').classList.add('open');
    }

    window.closeStatusModal = function() {
        document.getElementById('statusModalOverlay').classList.remove('open');
        pendingOrderId = null; pendingNextStatus = null;
    }

    document.getElementById('statusModalOverlay').addEventListener('click', function(e) {
        if (e.target === this) window.closeStatusModal();
    });

    window.confirmStatusUpdate = function() {
        if (!pendingOrderId) return;
        const btn = document.getElementById('modalConfirmBtn');
        const originalBtnText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Updating...';

        fetch(`${window.orderBaseUrl}/${pendingOrderId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            },
            body: JSON.stringify({
                status: pendingNextStatus
            })
        })
        .then(r => r.json())
        .then(data => {
            window.closeStatusModal();
            btn.disabled = false;
            btn.innerHTML = originalBtnText;
            if (data.success) {
                window.showToast('✓ ' + data.message);
                window.fetchOrders(1);
            } else {
                window.showToast('⚠ ' + (data.error || 'Something went wrong.'));
            }
        })
        .catch(() => {
            btn.disabled = false;
            window.showToast('Network error. Please try again.');
        });
    }

    let toastTimer;

    window.showToast = function(msg) {
        const t = document.getElementById('toastMsg');

        clearTimeout(toastTimer);

        t.textContent = msg;
        t.classList.add('show');

        toastTimer = setTimeout(() => {
            t.classList.remove('show');
        }, 3000);
    }

    window.showExchangeTracking = function(disputeId) {
        const field = document.getElementById('exchange-tracking-field-' + disputeId);
        if (field) field.style.display = 'block';
    }

    // ── Dispute action modal ──────────────────────────────────────────────
    let disputeModalAction   = null;
    let disputeModalId       = null;
    let disputeModalMaxRefund = 0;

    window.openDisputeModal = function(disputeId, action, totalPrice, deliveryFee, serviceTax, discountAmount) {
        disputeModalId     = disputeId;
        disputeModalAction = action;

        // Subtotal = total_price - delivery_fee - service_tax + discount_amount
        const subtotal = totalPrice - deliveryFee - serviceTax + discountAmount;
        disputeModalMaxRefund = Math.floor(subtotal);

        // Reset semua field & error
        document.getElementById('disputeNoteInput').value = '';
        document.getElementById('disputeTrackingInput').value = '';
        document.getElementById('disputeRefundRange').value = 0;
        document.getElementById('disputeRefundInput').value = '';
        document.getElementById('disputeRefundDisplay').textContent = 'Rp 0';
        ['disputeNoteErr','disputeTrackingErr','disputeRefundErr'].forEach(id => {
            document.getElementById(id).style.display = 'none';
        });
        ['disputeNoteInput','disputeTrackingInput','disputeRefundInput'].forEach(id => {
            document.getElementById(id).style.borderColor = '';
            document.getElementById(id).style.boxShadow   = '';
        });

        // Set range max
        document.getElementById('disputeRefundRange').max = disputeModalMaxRefund;
        document.getElementById('disputeRefundInput').max = disputeModalMaxRefund;

        // Tampilkan/sembunyikan section sesuai action
        document.getElementById('disputeRefundSection').style.display   = action === 'refund'    ? 'block' : 'none';
        document.getElementById('disputeTrackingSection').style.display = action === 'exchange'  ? 'block' : 'none';

        // Title & confirm button styling
        const titles   = { refund: 'Approve Refund', exchange: 'Send Replacement', reject: 'Reject Dispute' };
        const btnColors = { refund: '#1A237E', exchange: '#004D40', reject: '#B71C1C' };
        const btnHover  = { refund: '#0D1257', exchange: '#00251A', reject: '#7F0000' };

        document.getElementById('disputeModalTitle').textContent = titles[action];

        const btn = document.getElementById('disputeModalConfirmBtn');
        btn.style.background = btnColors[action];
        btn.onmouseover = () => btn.style.background = btnHover[action];
        btn.onmouseout  = () => btn.style.background = btnColors[action];
        btn.disabled    = false;
        btn.textContent = 'Confirm';

        // Show modal
        const overlay = document.getElementById('disputeModalOverlay');
        overlay.style.display = 'flex';
    }

    window.closeDisputeModal = function() {
        document.getElementById('disputeModalOverlay').style.display = 'none';
        disputeModalAction = null;
        disputeModalId     = null;
    }

    document.getElementById('disputeModalOverlay').addEventListener('click', function(e) {
        if (e.target === this) window.closeDisputeModal();
    });

    window.syncRefundInput = function(val) {
        const num = parseInt(val) || 0;
        document.getElementById('disputeRefundInput').value  = num;
        document.getElementById('disputeRefundDisplay').textContent = 'Rp ' + num.toLocaleString('id-ID');
    }

    window.syncRefundRange = function(val) {
        let num = parseInt(val) || 0;
        if (num > disputeModalMaxRefund) num = disputeModalMaxRefund;
        if (num < 0) num = 0;
        document.getElementById('disputeRefundRange').value = num;
        document.getElementById('disputeRefundInput').value = num;
        document.getElementById('disputeRefundDisplay').textContent = 'Rp ' + num.toLocaleString('id-ID');
    }

    window.submitDisputeAction = function() {
        let valid = true;

        const note = document.getElementById('disputeNoteInput').value.trim();
        if (!note) {
            document.getElementById('disputeNoteErr').style.display = 'block';
            document.getElementById('disputeNoteInput').style.borderColor = '#C62828';
            document.getElementById('disputeNoteInput').style.boxShadow   = '0 0 0 3px rgba(198,40,40,0.15)';
            valid = false;
        } else {
            document.getElementById('disputeNoteErr').style.display = 'none';
            document.getElementById('disputeNoteInput').style.borderColor = '';
            document.getElementById('disputeNoteInput').style.boxShadow   = '';
        }

        let refundAmount = null;
        if (disputeModalAction === 'refund') {
            refundAmount = parseInt(document.getElementById('disputeRefundInput').value) || 0;
            if (refundAmount <= 0) {
                document.getElementById('disputeRefundErr').style.display = 'block';
                document.getElementById('disputeRefundInput').style.borderColor = '#C62828';
                valid = false;
            } else {
                document.getElementById('disputeRefundErr').style.display = 'none';
                document.getElementById('disputeRefundInput').style.borderColor = '';
            }
        }

        let tracking = null;
        if (disputeModalAction === 'exchange') {
            tracking = document.getElementById('disputeTrackingInput').value.trim();
            if (!tracking) {
                document.getElementById('disputeTrackingErr').style.display = 'block';
                document.getElementById('disputeTrackingInput').style.borderColor = '#C62828';
                document.getElementById('disputeTrackingInput').style.boxShadow   = '0 0 0 3px rgba(198,40,40,0.15)';
                valid = false;
            } else {
                document.getElementById('disputeTrackingErr').style.display = 'none';
                document.getElementById('disputeTrackingInput').style.borderColor = '';
                document.getElementById('disputeTrackingInput').style.boxShadow   = '';
            }
        }

        if (!valid) return;

        const btn = document.getElementById('disputeModalConfirmBtn');
        btn.disabled    = true;
        btn.textContent = 'Processing...';

        const payload = {
            action:     disputeModalAction,
            admin_note: note,
        };
        if (refundAmount !== null) payload.refund_amount = refundAmount;
        if (tracking)              payload.replacement_tracking_number = tracking;

        fetch(`${window.disputeBaseUrl}/${disputeModalId}/resolve`, {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': window.csrfToken },
            body:    JSON.stringify(payload),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                window.closeDisputeModal();
                window.showToast('✓ ' + data.message);
                window.fetchOrders(1);
            } else {
                btn.disabled    = false;
                btn.textContent = 'Confirm';
                window.showToast('⚠ ' + (data.error || 'Something went wrong.'));
            }
        })
        .catch(() => {
            btn.disabled    = false;
            btn.textContent = 'Confirm';
            window.showToast('Network error. Please try again.');
        });
    }
    window.resolveDispute = function(disputeId, action) {
        const noteEl = document.getElementById('dispute-note-' + disputeId);
        const note   = noteEl?.value?.trim();

        // Inline warning jika note kosong
        if (!note) {
            noteEl.style.borderColor = '#C62828';
            noteEl.style.boxShadow   = '0 0 0 3px rgba(198,40,40,0.15)';
            noteEl.focus();

            let errEl = document.getElementById('note-err-' + disputeId);
            if (!errEl) {
                errEl    = document.createElement('p');
                errEl.id = 'note-err-' + disputeId;
                errEl.style.cssText = 'font-size:11px; color:#C62828; margin:4px 0 0; font-weight:600;';
                errEl.textContent   = '⚠ Admin note is required before taking action.';
                noteEl.parentNode.insertBefore(errEl, noteEl.nextSibling);
            }
            return;
        }

        // Reset style jika sudah diisi
        if (noteEl) {
            noteEl.style.borderColor = '';
            noteEl.style.boxShadow   = '';
            const errEl = document.getElementById('note-err-' + disputeId);
            if (errEl) errEl.remove();
        }

        // Khusus exchange: cek apakah tracking field muncul & ada isinya
        const tracking = document.getElementById('tracking-input-' + disputeId)?.value?.trim();

        const payload = { action, admin_note: note };
        if (action === 'exchange' && tracking) payload.replacement_tracking_number = tracking;

        fetch(`${window.disputeBaseUrl}/${disputeId}/resolved`, {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': window.csrfToken },
            body:    JSON.stringify(payload),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) { window.showToast('✓ ' + data.message); window.fetchOrders(1); }
            else              { window.showToast('⚠ ' + (data.error || 'Something went wrong.')); }
        })
        .catch(() => window.showToast('Network error. Please try again.'));
    }

    window.markDisputeResolved = function(disputeId) {
        const btn = document.getElementById('resolveModalConfirm');
        btn.disabled = true;
        btn.textContent = 'Processing...';

        fetch(`${window.disputeBaseUrl}/${disputeId}/resolved`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': window.csrfToken },
        })
        .then(r => r.json())
        .then(data => {
            window.closeResolveModal();
            btn.disabled = false;
            if (data.success) {
                window.showToast('✓ ' + data.message);
                window.fetchOrders(1);
            } else {
                window.showToast('⚠ ' + (data.error || 'Something went wrong.'));
            }
        })
        .catch(() => {
            window.closeResolveModal();
            btn.disabled = false;
            window.showToast('Network error. Please try again.');
        });
    }

    window.updateTracking = function(disputeId) {
        const tracking = document.getElementById('tracking-update-' + disputeId)?.value?.trim();
        if (!tracking) { window.showToast('⚠ Please enter a tracking number.'); return; }

        fetch(`${window.disputeBaseUrl}/${disputeId}/tracking`, {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': window.csrfToken },
            body:    JSON.stringify({ replacement_tracking_number: tracking }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) { window.showToast('✓ ' + data.message); window.fetchOrders(1); }
            else              { window.showToast('⚠ ' + (data.error || 'Something went wrong.')); }
        });
    }
    