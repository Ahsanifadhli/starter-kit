@extends('layouts.contentNavbarLayout')

@section('title', 'Dashboard')

@section('content')

  <!-- ===== Dashboard Stats ===== -->
  {{--
  Bootstrap Grid:
  - row g-4 (memberi gutter/jarak)
  - col-lg-3 (3*4 = 12, jadi 4 kartu per baris di layar besar)
  - col-md-6 (6*2 = 12, jadi 2 kartu per baris di tablet)
  - col-12 (1 kartu per baris di HP)
  --}}
  <div class="row g-4 mb-4">

    <div class="col-12 col-md-6 col-lg-3">
      <div class="card">
        <div class="card-body">
          <div class="card-title d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Products</h5>
            <span class="avatar">
              <span class="avatar-initial rounded bg-label-success"><i class="ri-store-2-line"></i></span>
            </span>
          </div>
          <h4 class="card-value fw-medium mb-1" id="productsCount">—</h4>
          <small class="text-muted">Total products</small>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-6 col-lg-3">
      <div class="card">
        <div class="card-body">
          <div class="card-title d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Branches</h5>
            <span class="avatar">
              <span class="avatar-initial rounded bg-label-primary"><i class="ri-map-pin-2-line"></i></span>
            </span>
          </div>
          <h4 class="card-value fw-medium mb-1" id="branchesCount">—</h4>
          <small class="text-muted">Total branches</small>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-6 col-lg-3">
      <div class="card">
        <div class="card-body">
          <div class="card-title d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Products Sold (This Month)</h5>
            <span class="avatar">
              <span class="avatar-initial rounded bg-label-warning"><i class="ri-shopping-bag-3-line"></i></span>
            </span>
          </div>
          <h4 class="card-value fw-medium mb-1" id="phQuantitySum">—</h4>
          <small class="text-muted">Sum quantity this month</small>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-6 col-lg-3">
      <div class="card">
        <div class="card-body">
          <div class="card-title d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Expenses (This Month)</h5>
            <span class="avatar">
              <span class="avatar-initial rounded bg-label-danger"><i class="ri-file-dollar-line"></i></span>
            </span>
          </div>
          <h4 class="card-value fw-medium mb-1" id="expenseNominalSum">—</h4>
          <small class="text-muted">Sum expenses this month</small>
        </div>
      </div>
    </div>
  </div>
<style>
  .card {
    border-radius: 12px;
  }

  canvas {
    width: 100% !important;
  }
</style>

<div class="row g-4 mb-4 align-items-stretch">

  <!-- 📈 Profit Chart -->
<div class="col-12 col-lg-8 d-flex">
  <div class="card w-100 h-100" style="background:#f8f9fa;">
    <div class="card-body d-flex flex-column p-4">

      <!-- Header -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h5 class="mb-1">Profit and Loss</h5>
          <small class="text-muted">Percentages (%)</small>
        </div>
      </div>

      <!-- Chart -->
      <div class="flex-grow-1">
        <canvas id="profitChart"></canvas>
      </div>

    </div>
  </div>
</div>

  <!-- 🏪 Branch Performance -->
<div class="col-12 col-lg-4 d-flex">
  <div class="card w-100 h-100" style="background:#f8f9fa;">
    <div class="card-body d-flex flex-column p-4">

      <!-- Header -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h5 class="mb-1">Branch Performance</h5>
          <small class="text-muted">Overview</small>
        </div>
      </div>

      <!-- Chart Wrapper -->
      <div class="flex-grow-1 d-flex align-items-center justify-content-center">
        <div style="width: 100%; max-width: 360px;">
          <canvas id="branchChart"></canvas>
        </div>
      </div>

    </div>
  </div>
</div>

</div>

<!-- 🥇 Top Products -->
<div class="card">
  <div class="card-body">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">Top Selling Product</h5>
    </div>

    <!-- Table -->
    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th style="width: 60px;">#</th>
            <th>Product</th>
            <th class="text-center">Qty</th>
            <th class="text-end">Revenue</th>
          </tr>
        </thead>
        <tbody id="topProductsTable"></tbody>
      </table>
    </div>

  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  (function(){
    const API_URL = '{{ env("API_URL") }}';

    function getCookie(name) {
      const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
      return match ? decodeURIComponent(match[2]) : null;
    }

    function authHeaders() {
      const headers = { 'Content-Type': 'application/json' };
      const token = getCookie('token');
      if (token) headers['Authorization'] = `Bearer ${token}`;
      const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
      if (csrf) headers['X-CSRF-TOKEN'] = csrf;
      return headers;
    }

    async function fetchAdminDashboard() {
    try {
      const url = `${API_URL}/admin/dashboard`;
      const res = await fetch(url, { headers: authHeaders(), credentials: 'include' });

      if (!res.ok) throw new Error('API Error');

      const data = await res.json();

      // ========================
      // BASIC STATS
      // ========================
      document.getElementById('productsCount').textContent = data.products_count ?? '0';
      document.getElementById('branchesCount').textContent = data.branches_count ?? '0';
      document.getElementById('phQuantitySum').textContent = data.monthly_product_sold ?? '0';

      const expense = Number(data.monthly_expense || 0);
      document.getElementById('expenseNominalSum').textContent =
        expense > 0 ? 'Rp ' + expense.toLocaleString('id-ID') : 'Rp 0';

      // ========================
// ========================
// 📈 PROFIT CHART PREMIUM
// ========================
const labels = data.profit_chart.map(m => {
  const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
  return months[m.month - 1];
});

const income = data.profit_chart.map(m => m.income_percent);
const expenseChart = data.profit_chart.map(m => m.expense_percent);

const ctx = document.getElementById('profitChart').getContext('2d');

// 🎨 gradient halus (biar kayak referensi)
const gradientGreen = ctx.createLinearGradient(0, 0, 0, 300);
gradientGreen.addColorStop(0, 'rgba(124, 179, 66, 0.25)');
gradientGreen.addColorStop(1, 'rgba(124, 179, 66, 0)');

const gradientRed = ctx.createLinearGradient(0, 0, 0, 300);
gradientRed.addColorStop(0, 'rgba(255, 82, 82, 0.25)');
gradientRed.addColorStop(1, 'rgba(255, 82, 82, 0)');

new Chart(ctx, {
  type: 'line',
  data: {
    labels,
    datasets: [
      {
        label: 'Income',
        data: income,
        borderColor: '#7CB342',
        backgroundColor: gradientGreen,
        fill: true, // 🔥 area fill
        tension: 0.45,
        borderWidth: 3,
        pointRadius: 0
      },
      {
        label: 'Expense',
        data: expenseChart,
        borderColor: '#FF5252',
        backgroundColor: gradientRed,
        fill: true,
        tension: 0.45,
        borderWidth: 3,
        pointRadius: 0
      }
    ]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,

    layout: {
      padding: {
        top: 10,
        right: 10,
        left: 5,
        bottom: 0
      }
    },

    plugins: {
      legend: {
        display: false // kayak referensi (clean)
      },
      tooltip: {
        backgroundColor: '#222',
        titleColor: '#fff',
        bodyColor: '#fff',
        padding: 10,
        callbacks: {
          label: (ctx) => `${ctx.raw}%`
        }
      }
    },

    scales: {
      x: {
        grid: {
          display: true,
          color: 'rgba(0,0,0,0.05)'
        },
        ticks: {
          color: '#888',
          font: {
            size: 11
          }
        }
      },
      y: {
        beginAtZero: true,
        max: 100,
        grid: {
          color: 'rgba(0,0,0,0.05)'
        },
        ticks: {
          color: '#888',
          callback: (val) => val + '%',
          font: {
            size: 11
          }
        }
      }
    },

    elements: {
      line: {
        capBezierPoints: true
      }
    }
  }
});

      // ========================
      // ========================
// 🏪 BRANCH DOUGHNUT CHART (PREMIUM)
// ========================
const branchLabels = data.branch_performance.map(b => b.branch_name);
const branchData = data.branch_performance.map(b => b.performance);

// 🎨 warna kuning-orange soft (lebih elegan)
const colors = [
  '#FFC107',
  '#FFB300',
  '#FFA000',
  '#FFCA28',
  '#FFD54F',
  '#FFE082'
];

new Chart(document.getElementById('branchChart'), {
  type: 'doughnut',
  data: {
    labels: branchLabels,
    datasets: [{
      data: branchData,
      backgroundColor: colors,
      borderWidth: 0,
      hoverOffset: 8 // 🔥 efek hover naik dikit
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,

    layout: {
      padding: 10
    },

    plugins: {
      legend: {
        position: 'bottom',
        labels: {
          boxWidth: 10,
          padding: 15,
          color: '#666',
          font: {
            size: 11
          }
        }
      },
      tooltip: {
        backgroundColor: '#222',
        titleColor: '#fff',
        bodyColor: '#fff',
        padding: 10,
        callbacks: {
          label: (ctx) => `${ctx.label}: ${ctx.raw}%`
        }
      }
    },

    cutout: '70%' // 🔥 lebih tipis & modern
  }
});

      // ========================
      // 🥇 TOP PRODUCTS
      // ========================
      const table = document.getElementById('topProductsTable');
      table.innerHTML = '';

      data.top_products.forEach(item => {
        const row = `
          <tr>
            <td>${item.rank}</td>
            <td>${item.product_name}</td>
            <td>${item.quantity_sold}</td>
            <td>Rp ${Number(item.revenue).toLocaleString('id-ID')}</td>
          </tr>
        `;
        table.innerHTML += row;
      });

    } catch (err) {
      console.error(err);
    }
  }
    document.addEventListener('DOMContentLoaded', fetchAdminDashboard);
  })();
</script>

@endsection
