document.addEventListener('DOMContentLoaded', function () {

    // Move modal overlay to body to avoid z-index/transform issues
    const overlay = document.getElementById('targetOverlay');
    if (overlay) document.body.appendChild(overlay);

    // ── Sales data from window object ─────────────────────────────
    let salesLabels = window.dashboardData.salesLabels;
    let salesData   = window.dashboardData.salesData;

    // ── Sales Line Chart ──────────────────────────────────────────
    const salesCtx = document.getElementById('salesChart').getContext('2d');

    const makeGradient = () => {
        const g = salesCtx.createLinearGradient(0, 0, 0, 190);
        g.addColorStop(0, 'rgba(184,115,51,0.18)');
        g.addColorStop(1, 'rgba(184,115,51,0)');
        return g;
    };

    const salesChart = new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: salesLabels,
            datasets: [{
                data: salesData,
                borderColor: '#B87333',
                borderWidth: 2.5,
                pointBackgroundColor: '#B87333',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                tension: 0.35,
                fill: true,
                backgroundColor: makeGradient(),
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: false,
                    ticks: {
                        callback: v => 'Rp ' + (v / 1000000).toFixed(0) + 'M',
                        color: '#7A7369',
                        font: { size: 11 },
                        maxTicksLimit: 5,
                    },
                    grid: { color: '#EAE5DB', drawBorder: false },
                    border: { display: false }
                },
                x: {
                    ticks: { color: '#7A7369', font: { size: 11 } },
                    grid: { display: false },
                    border: { display: false }
                }
            }
        }
    });

    // ── Range buttons ─────────────────────────────────────────────
    document.querySelectorAll('.range-btn').forEach(btn => {
        btn.addEventListener('click', function () {

            const months = this.dataset.months;

            document.querySelectorAll('.range-btn')
                .forEach(b => b.classList.remove('active'));

            this.classList.add('active');

            fetch(`${window.dashboardData.salesChartUrl}?months=${months}`)
                .then(r => r.json())
                .then(json => {

                    salesChart.data.labels = json.labels;
                    salesChart.data.datasets[0].data = json.data;
                    salesChart.data.datasets[0].backgroundColor = makeGradient();

                    salesChart.update();
                });
        });
    });

    // ── Donut Chart ───────────────────────────────────────────────
    const targetCtx = document.getElementById('targetChart').getContext('2d');

    const pct = window.dashboardData.pct;

    new Chart(targetCtx, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [pct, 100 - pct],
                backgroundColor: ['#B87333', '#EAE5DB'],
                borderWidth: 0,
                hoverOffset: 0,
            }]
        },
        options: {
            responsive: true,
            cutout: '76%',
            plugins: {
                legend: { display: false },
                tooltip: { enabled: false }
            },
            animation: {
                animateRotate: true,
                duration: 900
            }
        }
    });

    // ── Stat Card Filter ─────────────────────────────────────────
    const filterLabels = {
        'all'  : 'All Time',
        'week' : 'This Week',
        'month': 'This Month',
        '3m'   : 'Last 3 Months',
        'year' : 'This Year',
    };

    document.querySelectorAll('.stat-filter-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.stat-filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const range = this.dataset.range;

            fetch(`${window.dashboardData.statFilterUrl}?range=${range}`)
                .then(r => r.json())
                .then(json => {
                    document.getElementById('revenue-value').textContent =
                        'Rp ' + json.totalRevenue.toLocaleString('id-ID');
                    document.getElementById('orders-value').textContent =
                        json.totalOrders.toLocaleString('id-ID');

                    document.getElementById('revenue-pill').textContent = filterLabels[range];
                    document.getElementById('orders-pill').textContent  = filterLabels[range];
                });
        });
    });

});

// ── Modal Controls ───────────────────────────────────────────────
function openTargetModal() {
    const overlay = document.getElementById('targetOverlay');

    overlay.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeTargetModal() {
    const overlay = document.getElementById('targetOverlay');

    overlay.style.display = 'none';
    document.body.style.overflow = '';
}

// Close on backdrop click
document.getElementById('targetOverlay')?.addEventListener('click', function (e) {
    if (e.target === this) closeTargetModal();
});

// Make global
window.openTargetModal = openTargetModal;
window.closeTargetModal = closeTargetModal;