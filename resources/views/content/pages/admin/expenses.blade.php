@extends('layouts.contentNavbarLayout')

@section('title', 'Management Expenses')

@section('content')

<style>
  /* Kustomisasi warna oranye untuk tombol Solid */
  .btn-orange {
    background-color: #fd7e14 !important;
    border-color: #fd7e14 !important;
    color: #ffffff !important;
  }
  .btn-orange:hover,
  .btn-orange:focus,
  .btn-orange:active {
    background-color: #e37112 !important;
    border-color: #d66a10 !important;
    color: #ffffff !important;
  }

  /* Kustomisasi warna oranye untuk tombol Outline (Garis Luar) */
  .btn-outline-orange {
    color: #fd7e14 !important;
    border-color: #fd7e14 !important;
    background-color: transparent !important;
  }
  .btn-outline-orange:hover,
  .btn-outline-orange.active,
  .btn-outline-orange:active,
  .btn-outline-orange:focus {
    color: #ffffff !important;
    background-color: #fd7e14 !important;
    border-color: #fd7e14 !important;
  }

  .text-orange {
    color: #fd7e14 !important;
  }

  /* Kustomisasi warna untuk Pagination */
  .pagination .page-link {
    color: #fd7e14 !important;
  }
  .pagination .page-link:hover {
    color: #e37112 !important;
    background-color: #fff3e6 !important;
    border-color: #dee2e6 !important;
  }
  .pagination .page-item.active .page-link {
    z-index: 3 !important;
    color: #ffffff !important;
    background-color: #fd7e14 !important;
    border-color: #fd7e14 !important;
  }
  .pagination .page-item.disabled .page-link {
    color: #f7a96a !important;
  }
</style>

  <div class="card">

    <div class="card-header d-flex flex-column-reverse flex-md-row align-items-md-center justify-content-between gap-3">

      {{-- Search and filter --}}
      <div class="w-100" style="max-width: 300px;">
        <input type="search" id="searchExpense" class="form-control" placeholder="Search expense name..." />
      </div>

      {{-- Tombol Tambah Expense --}}
      <div class="d-flex justify-content-md-end">
        {{-- Menggunakan btn-orange --}}
        <button class="btn btn-orange text-nowrap" type="button" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
          <i class="ri-add-line me-1"></i> Add Expense
        </button>
      </div>

    </div>

    <div class="table-responsive text-nowrap">
      <table class="table table-hover mb-0">
        <thead>
          <tr>
            <th>Name</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0" id="expenseTableBody">
          {{-- Data akan diisi oleh JavaScript --}}
        </tbody>
      </table>
    </div>

    <div class="card-footer d-flex justify-content-center">
      <nav aria-label="Page navigation">
        <ul class="pagination mb-0" id="expensePagination">
          {{-- Pagination akan diisi oleh JavaScript --}}
        </ul>
      </nav>
    </div>

  </div>


  <div class="modal fade" id="addExpenseModal" tabindex="-1" aria-labelledby="addExpenseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addExpenseModalLabel">Add New Expense</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="addExpenseForm">
            <div class="mb-3">
              <label for="addExpenseName" class="form-label">Expense Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="addExpenseName" placeholder="e.g., Flour Purchase" required>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-orange" id="saveExpenseBtn">Save Expense</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="editExpenseModal" tabindex="-1" aria-labelledby="editExpenseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editExpenseModalLabel">Edit Expense</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="editExpenseForm">
            <div class="mb-3">
              <label for="editExpenseName" class="form-label">Expense Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="editExpenseName" required>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-orange" id="saveEditExpenseBtn">Save Changes</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="deleteExpenseModal" tabindex="-1" aria-labelledby="deleteExpenseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteExpenseModalLabel">Delete Expense?</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p class="mb-0">Are you sure you want to delete this expense? This action cannot be undone.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-danger" id="confirmDeleteExpenseBtn">Delete</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    const API_URL = '{{ env("API_URL") }}';
    let currentPage = 1;
    let currentExpenseId = null;
    let expenseToDelete = null;
    let searchTimeout;

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

    function showAlert(message, type = 'success') {
      const alertDiv = document.createElement('div');
      alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
      alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      `;
      const container = document.querySelector('.layout-page') || document.body;
      container.insertBefore(alertDiv, container.firstChild);
      setTimeout(() => alertDiv.remove(), 5000);
    }

    // Fetch expenses from API
    async function fetchExpenses(page = 1) {
      try {
        const search = document.getElementById('searchExpense').value.trim();
        let url = `${API_URL}/expenses?page=${page}`;
        if (search) url += `&search=${encodeURIComponent(search)}`;

        const response = await fetch(url, {
          headers: authHeaders()
        });
        const data = await response.json();

        renderExpenseTable(data.data);
        renderPagination(data);
        currentPage = page;
      } catch (error) {
        console.error('Error fetching expenses:', error);
        showAlert('Failed to load expenses', 'danger');
      }
    }

    // Render expense table
    function renderExpenseTable(expenses) {
      const tbody = document.getElementById('expenseTableBody');
      tbody.innerHTML = '';

      if (!expenses || expenses.length === 0) {
        tbody.innerHTML = '<tr><td colspan="2" class="text-center">No expenses found</td></tr>';
        return;
      }

      expenses.forEach(expense => {
        const row = document.createElement('tr');
        row.innerHTML = `
          <td><strong>${expense.name}</strong></td>
          <td>
            <div class="d-flex">
              <a class="btn btn-sm btn-icon btn-outline-orange me-2" href="javascript:void(0);"
                 data-bs-toggle="modal" data-bs-target="#editExpenseModal" onclick="loadEditExpense(${expense.id})">
                <i class="ri-pencil-line"></i>
              </a>
              <a class="btn btn-sm btn-icon btn-outline-danger" href="javascript:void(0);"
                 data-bs-toggle="modal" data-bs-target="#deleteExpenseModal" onclick="prepareDeleteExpense(${expense.id})">
                <i class="ri-delete-bin-line"></i>
              </a>
            </div>
          </td>
        `;
        tbody.appendChild(row);
      });
    }

    // Render pagination
    function renderPagination(data) {
      const paginationList = document.getElementById('expensePagination');
      paginationList.innerHTML = '';

      // Previous button
      const prevLi = document.createElement('li');
      prevLi.className = `page-item ${data.current_page === 1 ? 'disabled' : ''}`;
      prevLi.innerHTML = `<a class="page-link" href="javascript:void(0);" onclick="fetchExpenses(${data.current_page - 1})"><i class="ri-arrow-left-s-line"></i></a>`;
      paginationList.appendChild(prevLi);

      // Page numbers
      for (let i = 1; i <= data.last_page; i++) {
        const li = document.createElement('li');
        li.className = `page-item ${i === data.current_page ? 'active' : ''}`;
        li.innerHTML = `<a class="page-link" href="javascript:void(0);" onclick="fetchExpenses(${i})">${i}</a>`;
        paginationList.appendChild(li);
      }

      // Next button
      const nextLi = document.createElement('li');
      nextLi.className = `page-item ${data.current_page === data.last_page ? 'disabled' : ''}`;
      nextLi.innerHTML = `<a class="page-link" href="javascript:void(0);" onclick="fetchExpenses(${data.current_page + 1})"><i class="ri-arrow-right-s-line"></i></a>`;
      paginationList.appendChild(nextLi);
    }

    // Load edit expense data
    async function loadEditExpense(id) {
      try {
        const response = await fetch(`${API_URL}/expenses/${id}`, {
          headers: authHeaders()
        });
        const data = await response.json();
        currentExpenseId = id;
        document.getElementById('editExpenseName').value = data.name;
      } catch (error) {
        console.error('Error loading expense:', error);
        showAlert('Failed to load expense', 'danger');
      }
    }

    // Prepare delete
    function prepareDeleteExpense(id) {
      expenseToDelete = id;
    }

    // Save new expense
    document.getElementById('saveExpenseBtn').addEventListener('click', async function() {
      const name = document.getElementById('addExpenseName').value.trim();

      if (!name) {
        showAlert('Please enter an expense name', 'warning');
        return;
      }

      try {
        const response = await fetch(`${API_URL}/expenses`, {
          method: 'POST',
          headers: authHeaders(),
          body: JSON.stringify({ name })
        });

        const data = await response.json();

        if (response.ok) {
          showAlert(data.message || 'Expense created successfully', 'success');
          document.getElementById('addExpenseForm').reset();
          bootstrap.Modal.getInstance(document.getElementById('addExpenseModal')).hide();
          fetchExpenses(1);
        } else {
          showAlert(data.message || 'Failed to create expense', 'danger');
        }
      } catch (error) {
        console.error('Error creating expense:', error);
        showAlert('Error creating expense', 'danger');
      }
    });

    // Save edited expense
    document.getElementById('saveEditExpenseBtn').addEventListener('click', async function() {
      const name = document.getElementById('editExpenseName').value.trim();

      if (!name) {
        showAlert('Please enter an expense name', 'warning');
        return;
      }

      try {
        const response = await fetch(`${API_URL}/expenses/${currentExpenseId}`, {
          method: 'PUT',
          headers: authHeaders(),
          body: JSON.stringify({ name })
        });

        const data = await response.json();

        if (response.ok) {
          showAlert(data.message || 'Expense updated successfully', 'success');
          bootstrap.Modal.getInstance(document.getElementById('editExpenseModal')).hide();
          fetchExpenses(currentPage);
        } else {
          showAlert(data.message || 'Failed to update expense', 'danger');
        }
      } catch (error) {
        console.error('Error updating expense:', error);
        showAlert('Error updating expense', 'danger');
      }
    });

    // Delete expense
    document.getElementById('confirmDeleteExpenseBtn').addEventListener('click', async function() {
      if (!expenseToDelete) return;

      try {
        const response = await fetch(`${API_URL}/expenses/${expenseToDelete}`, {
          method: 'DELETE',
          headers: authHeaders()
        });

        const data = await response.json();

        if (response.ok) {
          showAlert(data.message || 'Expense deleted successfully', 'success');
          bootstrap.Modal.getInstance(document.getElementById('deleteExpenseModal')).hide();
          fetchExpenses(currentPage);
        } else {
          showAlert(data.message || 'Failed to delete expense', 'danger');
        }
      } catch (error) {
        console.error('Error deleting expense:', error);
        showAlert('Error deleting expense', 'danger');
      }
    });

    // Load expenses on page load
    document.addEventListener('DOMContentLoaded', function() {
      fetchExpenses(1);
      document.getElementById('searchExpense').addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => fetchExpenses(1), 400);
      });
    });
  </script>

@endsection
