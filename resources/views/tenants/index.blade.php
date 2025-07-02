@extends('layouts.app')
@section('content')
@if ($errors->any())
    <div class="error-message animate__animated animate__fadeIn">
        <ul class="list-disc list-inside text-red-600 text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="tenants-container">
    <di
    v class="tenants-wrapper">
        <!-- Header Section -->
        <div class="tenants-header">
            <h1 class="tenants-title animate__animated animate__fadeInDown">قائمة المستأجرين</h1>
            <div class="header-actions">
                <input type="text" id="searchInput" class="search-bar" placeholder="ابحث عن مستأجر..." />
                <select id="statusFilter" class="filter-select">
                    <option value="">جميع الحالات</option>
                    <option value="active">نشط</option>
                    <option value="suspended">موقوف</option>
                    <option value="terminated">منتهي</option>
                </select>
                <button id="toggleView" class="toggle-view-btn">
                    <svg class="toggle-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                    </svg>
                </button>
                <a href="{{ route('tenants.create') }}" class="add-tenant-btn">
                    <span>+</span> إضافة مستأجر جديد
                </a>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="success-message animate__animated animate__fadeIn">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="error-message animate__animated animate__fadeIn">
                {{ session('error') }}
            </div>
        @endif

        <!-- Table View -->
        <div id="tableView" class="tenants-table-container">
            <table id="tenantsTable" class="tenants-table table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th class="table-header">الاسم</th>
                        <th class="table-header">الهاتف</th>
                        <th class="table-header">البريد</th>
                        <th class="table-header">نوع الهوية</th>
                        <th class="table-header">الدخل</th>
                        <th class="table-header">الحالة</th>
                        <th class="table-header">صورة الهوية</th>
                        <th class="table-header">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tenants as $tenant)
                    <tr class="table-row" data-status="{{ $tenant->status }}">
                        <td class="table-cell">
                            {{ $tenant->first_name }} 
                            {{ $tenant->father_name ? $tenant->father_name . ' ' : '' }}
                            {{ $tenant->last_name }}
                        </td>
                        <td class="table-cell">{{ $tenant->phone }}</td>
                        <td class="table-cell">{{ $tenant->email ?? '-' }}</td>
                        @php
                            $idTypes = [
                                'national_card' => 'بطاقة وطنية',
                                'passport' => 'جواز سفر',
                                'residence' => 'إقامة',
                            ];
                        @endphp
                        <td class="table-cell">
                            {{ $idTypes[$tenant->id_type] ?? 'غير معروف' }}
                        </td>
                        <td class="table-cell">
                            {{ $tenant->monthly_income ? number_format($tenant->monthly_income, 2) . ' ريال' : '-' }}
                        </td>
                        <td class="table-cell">
                            @switch($tenant->status)
                                @case('active')
                                    <span class="status-badge active">نشط</span>
                                    @break
                                @case('suspended')
                                    <span class="status-badge suspended">موقوف</span>
                                    @break
                                @default
                                    <span class="status-badge terminated">منتهي</span>
                            @endswitch
                        </td>
                        <td class="table-cell">
                            @if($tenant->image_path)
                                <div class="tenant-image" onclick="showImageModal('{{ asset('storage/' . $tenant->image_path) }}')">
                                    <img src="{{ asset('storage/' . $tenant->image_path) }}" 
                                        alt="هوية {{ $tenant->first_name }}" class="animate__animated animate__zoomIn">
                                </div>
                            @else
                                <span class="no-image">لا توجد</span>
                            @endif
                        </td>
                        <td class="table-cell actions-cell">
                            <div class="actions-wrapper">
                                <a href="{{ route('tenants.show', $tenant->id) }}" class="action-link view">
                                    <svg class="action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <a href="{{ route('tenants.edit', $tenant->id) }}" class="action-link edit">
                                    <svg class="action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('tenants.destroy', $tenant->id) }}" 
                                      method="POST" 
                                      class="delete-form"
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا المستأجر؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-link delete">
                                        <svg class="action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="empty-table">
                            <div class="empty-content animate__animated animate__pulse">
                                <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <p>لا يوجد مستأجرين حاليًا</p>
                                <a href="{{ route('tenants.create') }}" class="add-first-tenant">إضافة المستأجر الأول</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Card View -->
        <div id="cardView" class="tenants-card-container" style="display: none;">
            @forelse($tenants as $tenant)
            <div class="tenant-card animate__animated animate__fadeInUp" data-status="{{ $tenant->status }}">
                <div class="card-header">
                    <h3>{{ $tenant->first_name }} {{ $tenant->father_name ? $tenant->father_name . ' ' : '' }}{{ $tenant->last_name }}</h3>
                    <span class="status-badge {{ $tenant->status }}">
                        @switch($tenant->status)
                            @case('active') نشط @break
                            @case('suspended') موقوف @break
                            @default منتهي
                        @endswitch
                    </span>
                </div>
                <div class="card-body">
                    <p><strong>الهاتف:</strong> {{ $tenant->phone }}</p>
                    <p><strong>البريد:</strong> {{ $tenant->email ?? '-' }}</p>
                    <p><strong>نوع الهوية:</strong> {{ $idTypes[$tenant->id_type] ?? 'غير معروف' }}</p>
                    <p><strong>الدخل:</strong> {{ $tenant->monthly_income ? number_format($tenant->monthly_income, 2) . ' ريال' : '-' }}</p>
                    @if($tenant->image_path)
                        <div class="tenant-image" onclick="showImageModal('{{ asset('storage/' . $tenant->image_path) }}')">
                            <img src="{{ asset('storage/' . $tenant->image_path) }}" alt="هوية {{ $tenant->first_name }}">
                        </div>
                    @else
                        <span class="no-image">لا توجد صورة</span>
                    @endif
                </div>
                <div class="card-actions">
                    <a href="{{ route('tenants.show', $tenant->id) }}" class="action-link view">عرض</a>
                    <a href="{{ route('tenants.edit', $tenant->id) }}" class="action-link edit">تعديل</a>
                    <form action="{{ route('tenants.destroy', $tenant->id) }}" method="POST" class="delete-form" onsubmit="return confirm('هل أنت متأكد من حذف هذا المستأجر؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-link delete">حذف</button>
                    </form>
                </div>
            </div>
            @empty
            <div class="empty-card animate__animated animate__pulse">
                <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <p>لا يوجد مستأجرين حاليًا</p>
                <a href="{{ route('tenants.create') }}" class="add-first-tenant">إضافة المستأجر الأول</a>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($tenants->hasPages())
            <div class="pagination-wrapper">
                {{ $tenants->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="image-modal">
    <div class="modal-content" onclick="event.stopPropagation()">
        <img id="modalImage" src="" alt="صورة الهوية" class="modal-image">
        <button onclick="closeImageModal()" class="modal-close-btn">×</button>
        <button onclick="zoomImage(1.2)" class="modal-zoom-in">+</button>
        <button onclick="zoomImage(0.8)" class="modal-zoom-out">−</button>
    </div>
</div>

<!-- Enhanced Styles -->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&display=swap');
    @import url('https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css');

    /* Base Styles */
    .tenants-container {
        margin: 0;
        padding: 20px;
        box-sizing: border-box;
        font-family: 'Amiri', serif;
        background: linear-gradient(145deg, #e0e7ff, #fce7f3);
        min-height: 100vh;
        direction: rtl;
    }
    .tenants-wrapper {
        max-width: 1280px;
        margin: 0 auto;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        background: #ffffff;
        padding: 40px;
    }
    .tenants-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .tenants-title {
        font-size: 36px;
        font-weight: 700;
        background: linear-gradient(90deg, #5b21b6, #ec4899, #3b82f6);
        background-size: 200%;
        background-clip: text;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: gradientAnimation 5s ease infinite;
    }
    @keyframes gradientAnimation {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    .header-actions {
        display: flex;
        gap: 16px;
        align-items: center;
        flex-wrap: wrap;
    }
    .search-bar {
        padding: 12px 16px;
        border: 1px solid #d1d5db;
        border-radius: 12px;
        font-size: 14px;
        width: 250px;
        transition: all 0.3s;
    }
    .search-bar:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 8px rgba(99, 102, 241, 0.3);
    }
    .filter-select {
        padding: 12px 16px;
        border: 1px solid #d1d5db;
        border-radius: 12px;
        font-size: 14px;
        background: #ffffff;
        transition: all 0.3s;
    }
    .filter-select:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 8px rgba(99, 102, 241, 0.3);
    }
    .toggle-view-btn {
        background: #e0e7ff;
        border: none;
        padding: 12px;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s;
    }
    .toggle-view-btn:hover {
        background: #c7d2fe;
        transform: translateY(-2px);
    }
    .toggle-icon {
        width: 24px;
        height: 24px;
        color: #4f46e5;
    }
    .add-tenant-btn {
        background: linear-gradient(135deg, #7c3aed, #db2777);
        color: white;
        padding: 14px 28px;
        border-radius: 16px;
        font-size: 16px;
        font-weight: 700;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s;
    }
    .add-tenant-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(124, 58, 237, 0.4);
    }
    .add-tenant-btn span {
        font-size: 24px;
    }
    .success-message {
        background: #d1fae5;
        color: #065f46;
        padding: 16px;
        border-radius: 16px;
        margin-bottom: 24px;
        font-size: 15px;
        border-left: 4px solid #10b981;
    }
    .error-message {
        background: #fee2e2;
        color: #991b1b;
        padding: 16px;
        border-radius: 16px;
        margin-bottom: 24px;
        font-size: 15px;
        border-left: 4px solid #ef4444;
    }
    .tenants-table-container {
        overflow-x: auto;
        border-radius: 16px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    .tenants-table {
        width: 100%;
        border-collapse: collapse;
        background: #ffffff;
        border-radius: 16px;
        overflow: hidden;
    }
    .table-header {
        padding: 20px;
        text-align: right;
        background: linear-gradient(135deg, #f3e7e9, #e0e7ff);
        color: #4f46e5;
        font-weight: 700;
        border-bottom: 2px solid #c7d2fe;
    }
    .table-row {
        border-bottom: 1px solid #e5e7eb;
        transition: all 0.3s;
    }
    .table-row:hover {
        background: #f1f5f9;
        transform: translateY(-2px);
    }
    .table-cell {
        padding: 20px;
        text-align: right;
        vertical-align: middle;
        font-size: 15px;
    }
    .status-badge {
        padding: 8px 16px;
        border-radius: 9999px;
        font-size: 13px;
        font-weight: 600;
        display: inline-block;
        transition: all 0.3s;
    }
    .status-badge.active {
        background: #d1fae5;
        color: #065f46;
    }
    .status-badge.suspended {
        background: #fef3c7;
        color: #92400e;
    }
    .status-badge.terminated {
        background: #fee2e2;
        color: #991b1b;
    }
    .tenant-image {
        cursor: pointer;
        position: relative;
        overflow: hidden;
        border-radius: 12px;
    }
    .tenant-image img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        transition: all 0.3s;
    }
    .tenant-image img:hover {
        transform: scale(1.1);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }
    .no-image {
        color: #9ca3af;
        font-style: italic;
    }
    .actions-cell {
        min-width: 160px;
    }
    .actions-wrapper {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
    }
    .action-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        transition: all 0.3s;
        font-size: 14px;
        text-decoration: none;
    }
    .action-link.view {
        color: #3b82f6;
        background: #dbeafe;
    }
    .action-link.edit {
        color: #10b981;
        background: #d1fae5;
    }
    .action-link.delete {
        color: #ef4444;
        background: #fee2e2;
        border: none;
        cursor: pointer;
    }
    .action-link:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }
    .action-icon {
        width: 20px;
        height: 20px;
    }
    .empty-table, .empty-card {
        padding: 48px;
        text-align: center;
        background: #f9fafb;
        border-radius: 16px;
    }
    .empty-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 20px;
    }
    .empty-icon {
        width: 96px;
        height: 96px;
        color: #d1d5db;
    }
    .add-first-tenant {
        color: #4f46e5;
        text-decoration: none;
        font-weight: 700;
        padding: 10px 20px;
        border-radius: 12px;
        background: #e0e7ff;
        transition: all 0.3s;
    }
    .add-first-tenant:hover {
        background: #c7d2fe;
        transform: translateY(-2px);
    }
    .pagination-wrapper {
        margin-top: 32px;
        display: flex;
        justify-content: center;
    }
    .tenants-card-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 24px;
        padding: 20px 0;
    }
    .tenant-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        padding: 24px;
        transition: all 0.3s;
    }
    .tenant-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    }
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }
    .card-header h3 {
        font-size: 20px;
        font-weight: 700;
        color: #1e293b;
    }
    .card-body p {
        margin: 8px 0;
        font-size: 15px;
        color: #4b5563;
    }
    .card-body p strong {
        color: #1e293b;
    }
    .card-actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        margin-top: 16px;
    }

    /* Image Modal Styles */
    .image-modal {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.8);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        backdrop-filter: blur(8px);
    }
    .modal-content {
        position: relative;
        max-width: 95%;
        max-height: 90vh;
        padding: 24px;
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4);
    }
    .modal-image {
        max-width: 100%;
        max-height: 75vh;
        border-radius: 12px;
        transition: transform 0.3s;
    }
    .modal-close-btn, .modal-zoom-in, .modal-zoom-out {
        position: absolute;
        top: -20px;
        border: none;
        border-radius: 50%;
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s;
    }
    .modal-close-btn {
        right: -20px;
        background: #ef4444;
        color: white;
    }
    .modal-zoom-in {
        right: 40px;
        background: #10b981;
        color: white;
    }
    .modal-zoom-out {
        right: 100px;
        background: #3b82f6;
        color: white;
    }
    .modal-close-btn:hover {
        transform: rotate(90deg);
        background: #dc2626;
    }
    .modal-zoom-in:hover, .modal-zoom-out:hover {
        transform: scale(1.1);
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .tenants-wrapper {
            padding: 20px;
        }
        .tenants-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .header-actions {
            width: 100%;
            justify-content: space-between;
        }
        .search-bar, .filter-select {
            width: 100%;
        }
        .table-header, .table-cell {
            padding: 12px;
            font-size: 14px;
        }
        .tenant-image img {
            width: 60px;
            height: 60px;
        }
        .actions-wrapper {
            gap: 8px;
        }
        .action-link {
            width: 36px;
            height: 36px;
        }
        .action-icon {
            width: 18px;
            height: 18px;
        }
        .tenants-table-container {
            display: none;
        }
        .tenants-card-container {
            display: grid;
        }
    }
</style>

<!-- Enhanced Script -->
<script>
let currentZoom = 1;

function showImageModal(imageUrl) {
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    modalImage.src = imageUrl;
    modal.style.display = 'flex';
    modalImage.style.transform = 'scale(1)';
    currentZoom = 1;
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.style.display = 'none';
    document.getElementById('modalImage').src = '';
}

function zoomImage(factor) {
    const modalImage = document.getElementById('modalImage');
    currentZoom *= factor;
    currentZoom = Math.min(Math.max(currentZoom, 0.5), 3);
    modalImage.style.transform = `scale(${currentZoom})`;
}

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeImageModal();
    }
});

document.getElementById('imageModal').addEventListener('click', function(event) {
    if (event.target === this) {
        closeImageModal();
    }
});

document.getElementById('toggleView').addEventListener('click', function() {
    const tableView = document.getElementById('tableView');
    const cardView = document.getElementById('cardView');
    if (tableView.style.display === 'none') {
        tableView.style.display = 'block';
        cardView.style.display = 'none';
    } else {
        tableView.style.display = 'none';
        cardView.style.display = 'grid';
    }
});

document.getElementById('searchInput').addEventListener('input', function() {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('#tenantsTable tbody tr');
    const cards = document.querySelectorAll('.tenant-card');
    
    rows.forEach(row => {
        const name = row.cells[0].textContent.toLowerCase();
        row.style.display = name.includes(searchValue) ? '' : 'none';
    });
    
    cards.forEach(card => {
        const name = card.querySelector('h3').textContent.toLowerCase();
        card.style.display = name.includes(searchValue) ? '' : 'none';
    });
});

document.getElementById('statusFilter').addEventListener('change', function() {
    const status = this.value;
    const rows = document.querySelectorAll('#tenantsTable tbody tr');
    const cards = document.querySelectorAll('.tenant-card');
    
    rows.forEach(row => {
        const rowStatus = row.getAttribute('data-status');
        row.style.display = status === '' || rowStatus === status ? '' : 'none';
    });
    
    cards.forEach(card => {
        const cardStatus = card.getAttribute('data-status');
        card.style.display = status === '' || cardStatus === status ? '' : 'none';
    });
});
</script>
@endsection