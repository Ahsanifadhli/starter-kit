@extends('layouts.contentNavbarLayoutBranch')

@section('title', 'Branch Dashboard')

@section('content')

<style>
  .dashboard-card {
    border: 0;
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.25s ease;
    background: #fff;
  }

  .dashboard-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
  }

  .dashboard-icon {
    width: 58px;
    height: 58px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    flex-shrink: 0;
  }

  .gradient-orange {
    background: linear-gradient(135deg, #ff9800, #ff6f00);
    color: white;
  }

  .gradient-green {
    background: linear-gradient(135deg, #4caf50, #2e7d32);
    color: white;
  }

  .gradient-blue {
    background: linear-gradient(135deg, #2196f3, #1565c0);
    color: white;
  }

  .hero-card {
    background: linear-gradient(135deg, #ff9800 0%, #ff6f00 100%);
    border-radius: 24px;
    overflow: hidden;
    position: relative;
    color: white;
    border: 0;
  }

  .hero-card::before {
    content: '';
    position: absolute;
    right: -40px;
    top: -40px;
    width: 180px;
    height: 180px;
    background: rgba(255,255,255,0.08);
    border-radius: 50%;
  }

  .hero-card::after {
    content: '';
    position: absolute;
    bottom: -60px;
    left: -60px;
    width: 220px;
    height: 220px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
  }

  .hero-content {
    position: relative;
    z-index: 2;
  }

  .summary-card {
    border-radius: 18px;
    border: 0;
    background: #fff;
  }

  .mini-badge {
    padding: 6px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 500;
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(10px);
  }

  .stat-label {
    color: #6c757d;
    font-size: 13px;
    margin-bottom: 6px;
  }

  .stat-value {
    font-size: 30px;
    font-weight: 700;
    line-height: 1;
    margin-bottom: 4px;
  }

  .soft-card {
    background: #f8f9fa;
    border-radius: 18px;
  }

  .activity-icon {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
  }
</style>

<div class="container-fluid py-4">

  <!-- HERO -->
  <div class="card hero-card mb-4">
    <div class="card-body p-4 p-lg-5 hero-content">

      <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-4">

        <div>
          <div class="mini-badge mb-3">
            Branch Management System
          </div>

          <h2 class="fw-bold text-white mb-2">
            Welcome Back 👋
          </h2>

          <p class="mb-0 opacity-75">
            Monitor branch performance, sales, and expenses in real time.
          </p>
        </div>

        <div class="d-flex gap-3 flex-wrap">

          <div class="text-center">
            <div class="fs-3 fw-bold" id="heroProductCount">0</div>
            <small class="opacity-75">Products</small>
          </div>

          <div class="text-center">
            <div class="fs-3 fw-bold" id="heroSoldCount">0</div>
            <small class="opacity-75">Sold</small>
          </div>

          <div class="text-center">
            <div class="fs-3 fw-bold" id="heroExpenseCount">Rp 0</div>
            <small class="opacity-75">Expenses</small>
          </div>

        </div>

      </div>

    </div>
  </div>

  <!-- STATS -->
  <div class="row g-4 mb-4">

    <!-- Products -->
    <div class="col-12 col-md-6 col-xl-4">
      <div class="card dashboard-card h-100">
        <div class="card-body p-4">

          <div class="d-flex justify-content-between align-items-start">

            <div>
              <div class="stat-label">
                Branch Products
              </div>

              <div class="stat-value" id="productCount">
                —
              </div>

              <small class="text-muted">
                Total products available
              </small>
            </div>

            <div class="dashboard-icon gradient-green">
              <i class="ri-store-2-line"></i>
            </div>

          </div>

        </div>
      </div>
    </div>

    <!-- Sold -->
    <div class="col-12 col-md-6 col-xl-4">
      <div class="card dashboard-card h-100">
        <div class="card-body p-4">

          <div class="d-flex justify-content-between align-items-start">

            <div>
              <div class="stat-label">
                Products Sold
              </div>

              <div class="stat-value" id="productHistoryCount">
                —
              </div>

              <small class="text-muted">
                This month transactions
              </small>
            </div>

            <div class="dashboard-icon gradient-orange">
              <i class="ri-shopping-bag-3-line"></i>
            </div>

          </div>

        </div>
      </div>
    </div>

    <!-- Expense -->
    <div class="col-12 col-md-6 col-xl-4">
      <div class="card dashboard-card h-100">
        <div class="card-body p-4">

          <div class="d-flex justify-content-between align-items-start">

            <div>
              <div class="stat-label">
                Monthly Expenses
              </div>

              <div class="stat-value fs-4" id="expenseHistoryCount">
                —
              </div>

              <small class="text-muted">
                Current month expenses
              </small>
            </div>

            <div class="dashboard-icon gradient-blue">
              <i class="ri-file-dollar-line"></i>
            </div>

          </div>

        </div>
      </div>
    </div>

  </div>

  <!-- BOTTOM SECTION -->
  <div class="row g-4">

    <!-- Overview -->
    <div class="col-12 col-lg-8">
      <div class="card summary-card h-100">
        <div class="card-body p-4">

          <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
              <h5 class="mb-1">
                Branch Overview
              </h5>
              <small class="text-muted">
                Current branch performance summary
              </small>
            </div>

            <span class="badge bg-label-warning">
              Live Data
            </span>
          </div>

          <div class="row g-4">

            <div class="col-md-4">
              <div class="soft-card p-4 h-100">
                <div class="activity-icon bg-label-success mb-3">
                  <i class="ri-store-2-line"></i>
                </div>

                <h4 class="mb-1" id="overviewProduct">
                  0
                </h4>

                <small class="text-muted">
                  Available Products
                </small>
              </div>
            </div>

            <div class="col-md-4">
              <div class="soft-card p-4 h-100">
                <div class="activity-icon bg-label-warning mb-3">
                  <i class="ri-shopping-cart-2-line"></i>
                </div>

                <h4 class="mb-1" id="overviewSold">
                  0
                </h4>

                <small class="text-muted">
                  Monthly Sales
                </small>
              </div>
            </div>

            <div class="col-md-4">
              <div class="soft-card p-4 h-100">
                <div class="activity-icon bg-label-primary mb-3">
                  <i class="ri-wallet-3-line"></i>
                </div>

                <h4 class="mb-1" id="overviewExpense">
                  Rp 0
                </h4>

                <small class="text-muted">
                  Operational Expenses
                </small>
              </div>
            </div>

          </div>

        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-12 col-lg-4">
      <div class="card summary-card h-100">
        <div class="card-body p-4">

          <h5 class="mb-1">
            Quick Actions
          </h5>

          <small class="text-muted d-block mb-4">
            Shortcut to manage branch data
          </small>

          <div class="d-grid gap-3">

            <a href="{{ url('/branch/products') }}"
               class="btn btn-outline-warning text-start rounded-4 p-3">

              <div class="d-flex align-items-center">
                <i class="ri-shopping-bag-3-line ri-22px me-3"></i>

                <div>
                  <div class="fw-medium">
                    Manage Products
                  </div>

                  <small class="text-muted">
                    Add and update branch products
                  </small>
                </div>
              </div>
            </a>

            <a href="{{ url('/branch/history/products') }}"
               class="btn btn-outline-warning text-start rounded-4 p-3">

              <div class="d-flex align-items-center">
                <i class="ri-history-line ri-22px me-3"></i>

                <div>
                  <div class="fw-medium">
                    Product History
                  </div>

                  <small class="text-muted">
                    View sales transactions
                  </small>
                </div>
              </div>
            </a>

            <a href="{{ url('/branch/history/expenses') }}"
               class="btn btn-outline-warning text-start rounded-4 p-3">

              <div class="d-flex align-items-center">
                <i class="ri-file-list-3-line ri-22px me-3"></i>

                <div>
                  <div class="fw-medium">
                    Expense History
                  </div>

                  <small class="text-muted">
                    Monitor operational expenses
                  </small>
                </div>
              </div>
            </a>

          </div>

        </div>
      </div>
    </div>

  </div>

</div>

<script>
(function () {

  const API_URL = '{{ env("API_URL") }}';

  function getCookie(name) {
    const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
    return match ? decodeURIComponent(match[2]) : null;
  }

  function authHeaders() {
    const headers = { 'Content-Type': 'application/json' };

    const token = getCookie('token');

    if (token) {
      headers['Authorization'] = `Bearer ${token}`;
    }

    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;

    if (csrf) {
      headers['X-CSRF-TOKEN'] = csrf;
    }

    return headers;
  }

  async function fetchDashboard() {

    try {

      const res = await fetch(
        `${API_URL}/branch/dashboard`,
        {
          headers: authHeaders()
        }
      );

      if (!res.ok) {
        throw new Error('Failed to load dashboard');
      }

      const data = await res.json();

      const productCount =
        Number(data.branch_product_count || 0);

      const soldCount =
        Number(data.branch_product_history_count || 0);

      const expense =
        Number(data.branch_expense_history_count || 0);

      const formattedExpense =
        expense > 0
          ? 'Rp ' + expense.toLocaleString('id-ID')
          : 'Rp 0';

      // MAIN CARDS
      document.getElementById('productCount').textContent =
        productCount.toLocaleString('id-ID');

      document.getElementById('productHistoryCount').textContent =
        soldCount.toLocaleString('id-ID');

      document.getElementById('expenseHistoryCount').textContent =
        formattedExpense;

      // HERO
      document.getElementById('heroProductCount').textContent =
        productCount.toLocaleString('id-ID');

      document.getElementById('heroSoldCount').textContent =
        soldCount.toLocaleString('id-ID');

      document.getElementById('heroExpenseCount').textContent =
        formattedExpense;

      // OVERVIEW
      document.getElementById('overviewProduct').textContent =
        productCount.toLocaleString('id-ID');

      document.getElementById('overviewSold').textContent =
        soldCount.toLocaleString('id-ID');

      document.getElementById('overviewExpense').textContent =
        formattedExpense;

    } catch (err) {

      console.error(err);

      const container = document.querySelector('.container-fluid');

      if (container) {

        const msg = document.createElement('div');

        msg.className = 'alert alert-warning rounded-4';

        msg.innerHTML = `
          <i class="ri-error-warning-line me-2"></i>
          Failed to load dashboard data. Please refresh the page.
        `;

        container.prepend(msg);
      }
    }
  }

  document.addEventListener(
    'DOMContentLoaded',
    fetchDashboard
  );

})();
</script>

@endsection
