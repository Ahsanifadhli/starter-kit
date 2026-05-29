@extends('layouts.contentNavbarLayoutBranch')

@section('title', 'Branch Overview')

@section('content')

<style>
  /* ── Orange palette ── */
  :root {
    --orange:        #fd7e14;
    --orange-hover:  #e37112;
    --orange-light:  #fff3e6;
    --orange-border: #fdd8b0;
    --orange-text:   #7c3800;
  }

  /* ── Solid button ── */
  .btn-orange {
    background-color: #fd7e14 !important;
    border-color:     #fd7e14 !important;
    color:            #ffffff !important;
  }
  .btn-orange:hover,
  .btn-orange:focus,
  .btn-orange:active {
    background-color: #e37112 !important;
    border-color:     #d66a10 !important;
    color:            #ffffff !important;
  }

  /* ── Outline button ── */
  .btn-outline-orange {
    color:            #fd7e14 !important;
    border-color:     #fd7e14 !important;
    background-color: transparent !important;
  }
  .btn-outline-orange:hover,
  .btn-outline-orange.active,
  .btn-outline-orange:active,
  .btn-outline-orange:focus {
    color:            #ffffff !important;
    background-color: #fd7e14 !important;
    border-color:     #fd7e14 !important;
  }

  /* ── Pagination ── */
  .pagination .page-link {
    color: #fd7e14 !important;
  }
  .pagination .page-link:hover {
    color:            #e37112 !important;
    background-color: #fff3e6 !important;
    border-color:     #dee2e6 !important;
  }
  .pagination .page-item.active .page-link {
    z-index:          3 !important;
    color:            #ffffff !important;
    background-color: #fd7e14 !important;
    border-color:     #fd7e14 !important;
  }
  .pagination .page-item.disabled .page-link {
    color: #f7a96a !important;
  }

  /* ── Cards ── */
  .ov-card {
    background: #ffffff;
    border:     1px solid #f0f0f0;
    border-radius: 14px;
  }

  /* ── Hero banner ── */
  .hero-card {
    background: #fd7e14;
    border-radius: 14px;
    color: #ffffff;
    position: relative;
    overflow: hidden;
  }
  .hero-card::before {
    content: '';
    position: absolute;
    inset: 0;
    background: repeating-linear-gradient(
      -45deg,
      rgba(255,255,255,.04) 0px,
      rgba(255,255,255,.04) 1px,
      transparent 1px,
      transparent 12px
    );
    pointer-events: none;
  }
  .hero-card .hero-icon {
    width:  48px;
    height: 48px;
    border-radius: 10px;
    background: rgba(255,255,255,.18);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
  }
  .hero-card h2,
  .hero-card h5,
  .hero-card p,
  .hero-card small {
    color: #ffffff !important;
  }
  .hero-card .hero-badge {
    display: inline-block;
    background: rgba(255,255,255,.2);
    color: #ffffff;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .5px;
    text-transform: uppercase;
    padding: 4px 10px;
    border-radius: 999px;
  }
  .hero-card .stat-label {
    font-size: 12px;
    opacity: .78;
    margin-bottom: 2px;
  }
  .hero-card .stat-value {
    font-size: 18px;
    font-weight: 700;
  }
  .hero-divider {
    border-color: rgba(255,255,255,.2) !important;
  }

  /* ── Report card ── */
  .report-card {
    background: #ffffff;
    border: 1px solid #f0f0f0;
    border-radius: 14px;
  }
  .report-badge {
    background: #fff3e6;
    color:       #7c3800;
    font-size:   11px;
    font-weight: 600;
    padding:     4px 10px;
    border-radius: 999px;
  }

  /* ── Filter card ── */
  .filter-card {
    background: #ffffff;
    border: 1px solid #f0f0f0;
    border-radius: 14px;
  }
  .filter-card .form-control:focus {
    border-color: #fd7e14;
    box-shadow: 0 0 0 3px rgba(253,126,20,.12);
  }
  .filter-card .form-label {
    font-size:    12px;
    font-weight:  600;
    color:        #888;
    text-transform: uppercase;
    letter-spacing: .4px;
    margin-bottom: 4px;
  }

  /* ── Section titles ── */
  .section-title {
    font-size: 15px;
    font-weight: 700;
    color: #1a1a1a;
    margin: 0;
  }

  /* ── Table ── */
  .ov-table thead th {
    font-size:    11px;
    text-transform: uppercase;
    letter-spacing: .5px;
    color:        #aaa;
    border-bottom: 1px solid #f0f0f0;
    padding-bottom: 10px;
    font-weight: 600;
  }
  .ov-table tbody tr {
    border-bottom: 1px solid #f9f9f9;
    transition: background .12s;
  }
  .ov-table tbody tr:last-child {
    border-bottom: none;
  }
  .ov-table tbody tr:hover {
    background: #fffaf6;
  }
  .ov-table tbody td {
    vertical-align: middle;
    padding: 10px 12px;
    font-size: 14px;
  }
  .ov-table tbody td .product-name {
    font-weight: 600;
    color: #1a1a1a;
    font-size: 14px;
  }
  .ov-table .num {
    font-variant-numeric: tabular-nums;
  }

  /* ── Percent pill ── */
  .pct-pill {
    display: inline-block;
    background: #fff3e6;
    color:       #7c3800;
    font-size:   12px;
    font-weight: 600;
    padding:     3px 8px;
    border-radius: 999px;
  }

  /* ── Empty state ── */
  .empty-state {
    padding: 2.5rem 0;
    text-align: center;
    color: #bbb;
    font-size: 14px;
  }
  .empty-state i {
    font-size: 32px;
    display: block;
    margin-bottom: 8px;
    color: #e0e0e0;
  }
</style>

<!-- ═══════════════════════════════════════════
     HEADER ROW
═══════════════════════════════════════════ -->
<div class="row g-4 mb-4">

  <!-- Hero -->
  <div class="col-12 col-lg-8">
    <div class="hero-card p-4 h-100">

      <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
          <span class="hero-badge mb-2 d-inline-block">Branch Dashboard</span>
          <h2 class="fw-bold mb-1 mt-2" id="branchName" style="font-size:1.6rem;">
            Loading…
          </h2>
          <p class="mb-0" style="opacity:.8; font-size:14px;">
            Monitor branch performance, products, and expenses in real-time.
          </p>
        </div>
        <div class="hero-icon">
          <i class="ri-store-2-line"></i>
        </div>
      </div>

      <hr class="hero-divider my-3">

      <div class="row g-3">
        <div class="col-6 col-md-3">
          <div class="stat-label">Total Products</div>
          <div class="stat-value" id="totalProducts">0</div>
        </div>
        <div class="col-6 col-md-3">
          <div class="stat-label">Total Revenue</div>
          <div class="stat-value" id="totalRevenue">Rp 0</div>
        </div>
        <div class="col-6 col-md-3">
          <div class="stat-label">Total Expenses</div>
          <div class="stat-value" id="totalExpenses">Rp 0</div>
        </div>
        <div class="col-6 col-md-3">
          <div class="stat-label">Products Sold</div>
          <div class="stat-value" id="totalQty">0</div>
        </div>
      </div>

    </div>
  </div>

  <!-- Report generator -->
  <div class="col-12 col-lg-4">
    <div class="report-card p-4 h-100 d-flex flex-column justify-content-between">

      <div>
        <div class="d-flex align-items-center justify-content-between mb-2">
          <h5 class="section-title">Report Generator</h5>
          <span class="report-badge">PDF Export</span>
        </div>
        <p class="text-muted mb-0" style="font-size:13.5px; line-height:1.6;">
          Generate a branch profit &amp; loss report based on the selected date range.
        </p>
      </div>

      <button id="generateLabaRugiBtn" class="btn btn-orange w-100 mt-4">
        <i class="ri-file-chart-line me-1"></i>
        Generate Laba Rugi
      </button>

    </div>
  </div>

</div>

<!-- ═══════════════════════════════════════════
     FILTER BAR
═══════════════════════════════════════════ -->
<div class="filter-card p-4 mb-4">
  <div class="row g-3 align-items-end">

    <div class="col-12 col-md-3">
      <label class="form-label">Search Product</label>
      <input type="text" id="productSearch" class="form-control" placeholder="Product name…">
    </div>

    <div class="col-12 col-md-3">
      <label class="form-label">Search Expense</label>
      <input type="text" id="expenseSearch" class="form-control" placeholder="Expense name…">
    </div>

    <div class="col-6 col-md-2">
      <label class="form-label">Start Date</label>
      <input type="date" id="startDate" class="form-control">
    </div>

    <div class="col-6 col-md-2">
      <label class="form-label">End Date</label>
      <input type="date" id="endDate" class="form-control">
    </div>

    <div class="col-12 col-md-2 d-grid gap-2">
      <button class="btn btn-orange" id="searchBothBtn">
        <i class="ri-search-line me-1"></i> Search
      </button>
      <button class="btn btn-outline-secondary" id="resetBtn">
        Reset
      </button>
    </div>

  </div>
</div>

<!-- ═══════════════════════════════════════════
     PRODUCTS TABLE
═══════════════════════════════════════════ -->
<div class="ov-card p-4 mb-4">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h5 class="section-title">Top Product Performance</h5>
      <small class="text-muted">Product sales and performance percentage</small>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table ov-table align-middle mb-0">
      <thead>
        <tr>
          <th style="width:40px">#</th>
          <th>Product</th>
          <th class="text-end">Price</th>
          <th class="text-end">Qty</th>
          <th class="text-end">Revenue</th>
          <th class="text-end">Retail</th>
          <th class="text-end">Pesanan</th>
          <th class="text-end">Pagi</th>
          <th class="text-end">Siang</th>
        </tr>
      </thead>
      <tbody id="productsTableBody"></tbody>
    </table>
  </div>

  <div class="d-flex justify-content-center mt-4">
    <ul class="pagination mb-0" id="productsPagination"></ul>
  </div>

</div>

<!-- ═══════════════════════════════════════════
     EXPENSES TABLE
═══════════════════════════════════════════ -->
<div class="ov-card p-4">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h5 class="section-title">Expense Analytics</h5>
      <small class="text-muted">Branch expense monitoring</small>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table ov-table align-middle mb-0">
      <thead>
        <tr>
          <th style="width:40px">#</th>
          <th>Expense</th>
          <th class="text-end">Nominal</th>
          <th class="text-end">Pagi</th>
          <th class="text-end">Siang</th>
        </tr>
      </thead>
      <tbody id="expensesTableBody"></tbody>
    </table>
  </div>

  <div class="d-flex justify-content-center mt-4">
    <ul class="pagination mb-0" id="expensesPagination"></ul>
  </div>

</div>

<!-- ═══════════════════════════════════════════
     SCRIPTS
═══════════════════════════════════════════ -->
<script>
  const API_URL = '{{ env("API_URL") }}';

  function getCookie(name) {
    const m = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
    return m ? decodeURIComponent(m[2]) : null;
  }

  function authHeaders() {
    const h = { 'Content-Type': 'application/json' };
    const t = getCookie('token');
    if (t) h['Authorization'] = `Bearer ${t}`;
    return h;
  }

  function formatCurrency(v) {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency', currency: 'IDR', minimumFractionDigits: 0
    }).format(v);
  }

  function getFirstDayOfMonth() {
    const n = new Date();
    return new Date(n.getFullYear(), n.getMonth(), 1);
  }

  function getLastDayOfMonth() {
    const n = new Date();
    return new Date(n.getFullYear(), n.getMonth() + 1, 0);
  }

  function formatForInput(d) {
    const y   = d.getFullYear(),
          m   = String(d.getMonth() + 1).padStart(2, '0'),
          day = String(d.getDate()).padStart(2, '0');
    return `${y}-${m}-${day}`;
  }

  document.getElementById('startDate').value = formatForInput(getFirstDayOfMonth());
  document.getElementById('endDate').value   = formatForInput(getLastDayOfMonth());

  /* ── fetchBranch ── */
  async function fetchBranch() {
    try {
      const res  = await fetch(`${API_URL}/branch`, { headers: authHeaders(), credentials: 'include' });
      const data = await res.json();
      document.getElementById('branchName').textContent = branchName1 || 'Branch';
    } catch (e) { console.error(e); }
  }

  /* ── Products ── */
  async function fetchProducts(page = 1) {
    const search = document.getElementById('productSearch').value.trim();
    const start  = document.getElementById('startDate').value;
    const end    = document.getElementById('endDate').value;

    let url = `${API_URL}/branch/products?page=${page}`;
    if (search) url += `&search=${encodeURIComponent(search)}`;
    if (start)  url += `&start_date=${encodeURIComponent(start)}`;
    if (end)    url += `&end_date=${encodeURIComponent(end)}`;

    const res  = await fetch(url, { headers: authHeaders(), credentials: 'include' });
    const data = await res.json();
    renderProducts(data);
  }

  function pctPill(val) {
    const n = val ? parseFloat(val).toFixed(2) : '0.00';
    return `<span class="pct-pill">${n}%</span>`;
  }

  function renderProducts(data) {
    const rows  = data.data || data;
    const tbody = document.getElementById('productsTableBody');
    tbody.innerHTML = '';

    let totalRevenue = 0, totalQty = 0;

    if (!rows || rows.length === 0) {
      tbody.innerHTML = `
        <tr>
          <td colspan="9">
            <div class="empty-state">
              <i class="ri-box-3-line"></i>
              No products found
            </div>
          </td>
        </tr>`;
      return;
    }

    rows.forEach((p, idx) => {
      const revenue = Number(p.total_revenue || 0);
      totalRevenue += revenue;
      totalQty     += Number(p.total_quantity || 0);

      tbody.innerHTML += `
        <tr>
          <td class="text-muted" style="font-size:13px;">${idx + 1}</td>
          <td><span class="product-name">${p.product?.name || '—'}</span></td>
          <td class="text-end num">${formatCurrency(p.branch_price || 0)}</td>
          <td class="text-end num fw-semibold">${p.total_quantity || 0}</td>
          <td class="text-end num fw-bold" style="color:#fd7e14;">${formatCurrency(revenue)}</td>
          <td class="text-end">${pctPill(p.retail_percent)}</td>
          <td class="text-end">${pctPill(p.pesanan_percent)}</td>
          <td class="text-end">${pctPill(p.pagi_percent)}</td>
          <td class="text-end">${pctPill(p.siang_percent)}</td>
        </tr>`;
    });

    document.getElementById('totalProducts').textContent = rows.length;
    document.getElementById('totalRevenue').textContent  = formatCurrency(totalRevenue);
    document.getElementById('totalQty').textContent      = totalQty;

    renderPagination('productsPagination', data, fetchProducts);
  }

  /* ── Expenses ── */
  async function fetchExpenses(page = 1) {
    const search = document.getElementById('expenseSearch').value.trim();
    const start  = document.getElementById('startDate').value;
    const end    = document.getElementById('endDate').value;

    let url = `${API_URL}/branch/expenses?page=${page}`;
    if (search) url += `&search=${encodeURIComponent(search)}`;
    if (start)  url += `&start_date=${encodeURIComponent(start)}`;
    if (end)    url += `&end_date=${encodeURIComponent(end)}`;

    const res  = await fetch(url, { headers: authHeaders(), credentials: 'include' });
    const data = await res.json();
    renderExpenses(data);
  }

  function renderExpenses(data) {
    const rows  = data.data || data;
    const tbody = document.getElementById('expensesTableBody');
    tbody.innerHTML = '';

    let totalExpenses = 0;

    if (!rows || rows.length === 0) {
      tbody.innerHTML = `
        <tr>
          <td colspan="5">
            <div class="empty-state">
              <i class="ri-money-dollar-circle-line"></i>
              No expenses found
            </div>
          </td>
        </tr>`;
      return;
    }

    rows.forEach((r, idx) => {
      const nominal = Number(r.total_nominal || 0);
      totalExpenses += nominal;
      const name = r.expense?.name || r.name || '—';

      tbody.innerHTML += `
        <tr>
          <td class="text-muted" style="font-size:13px;">${idx + 1}</td>
          <td><span class="product-name">${name}</span></td>
          <td class="text-end num fw-bold" style="color:#fd7e14;">${formatCurrency(nominal)}</td>
          <td class="text-end">${pctPill(r.pagi_percent)}</td>
          <td class="text-end">${pctPill(r.siang_percent)}</td>
        </tr>`;
    });

    document.getElementById('totalExpenses').textContent = formatCurrency(totalExpenses);

    renderPagination('expensesPagination', data, fetchExpenses);
  }

  /* ── Generic pagination renderer ── */
  function renderPagination(listId, data, fetchFn) {
    const list = document.getElementById(listId);
    list.innerHTML = '';
    if (!data || !data.last_page) return;

    for (let i = 1; i <= data.last_page; i++) {
      list.innerHTML += `
        <li class="page-item ${i === data.current_page ? 'active' : ''}">
          <a class="page-link" href="#" onclick="event.preventDefault(); (${fetchFn.name})(${i})">${i}</a>
        </li>`;
    }
  }

  /* ── Event listeners ── */
  document.getElementById('searchBothBtn').addEventListener('click', () => {
    fetchProducts(1);
    fetchExpenses(1);
  });

  document.getElementById('resetBtn').addEventListener('click', () => {
    document.getElementById('productSearch').value = '';
    document.getElementById('expenseSearch').value = '';
    document.getElementById('startDate').value = formatForInput(getFirstDayOfMonth());
    document.getElementById('endDate').value   = formatForInput(getLastDayOfMonth());
    fetchProducts(1);
    fetchExpenses(1);
  });

  document.getElementById('generateLabaRugiBtn').addEventListener('click', (e) => {
    e.preventDefault();
    const start = document.getElementById('startDate').value;
    const end   = document.getElementById('endDate').value;
    window.location.href = `/branch/export/labarugi?start_date=${encodeURIComponent(start)}&end_date=${encodeURIComponent(end)}`;
  });

  document.addEventListener('DOMContentLoaded', () => {
    fetchBranch();
    fetchProducts(1);
    fetchExpenses(1);
  });
</script>

@endsection
