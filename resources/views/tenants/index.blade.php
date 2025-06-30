@extends('layouts.app')
@section('content')
@if ($errors->any())
    <div class="error-message">
        <ul class="list-disc list-inside text-red-600 text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="tenants-container">
    <div class="tenants-wrapper">
        <!-- Header Section -->
        <div class="tenants-header">
            <h1 class="tenants-title">قائمة المستأجرين</h1>
            <a href="{{ route('tenants.create') }}" class="add-tenant-btn">
                <span>+</span> إضافة مستأجر جديد
            </a>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="error-message">
                {{ session('error') }}
            </div>
        @endif

        <!-- Table Section -->
        <div class="tenants-table-container">
            <table id="example2" class="tenants-table table table-bordered table-hover table-striped">
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
                    <tr class="table-row">
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
                                        alt="هوية {{ $tenant->first_name }}">
                                </div>
                            @else
                                <span class="no-image">لا توجد</span>
                            @endif
                        </td>
                        <td class="table-cell actions-cell">
                            <div class="actions-wrapper">
                                <a href="{{ route('tenants.show', $tenant->id) }}" class="action-link view">
                                    <svg class="action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <a href="{{ route('tenants.edit', $tenant->id) }}" class="action-link edit">
                                    <svg class="action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
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
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="empty-table">
                            <div class="empty-content">
                                <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
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
        <button onclick="closeImageModal()" class="modal-close-btn">
            &times;
        </button>
    </div>
</div>

<!-- Enhanced Styles -->
<style>
    /* Base Styles */
    .tenants-container {
        margin: 0;
        padding: 20px;
        box-sizing: border-box;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        background: linear-gradient(135deg, #f3e7e9, #e3eeff);
        min-height: 100vh;
        direction: rtl;
    }
    .tenants-wrapper {
        max-width: 1200px;
        margin: 0 auto;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        background: #ffffff;
        padding: 30px;
    }
    .tenants-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }
    .tenants-title {
        font-size: 32px;
        font-weight: 800;
        color: #1e293b;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        background-clip: text;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .add-tenant-btn {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border: none;
        padding: 14px 28px;
        border-radius: 14px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .add-tenant-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 35px rgba(99, 102, 241, 0.3);
    }
    .add-tenant-btn span {
        font-size: 22px;
    }
    .success-message {
        background-color: #d1fae5;
        color: #065f46;
        padding: 12px 16px;
        border-radius: 12px;
        margin-bottom: 20px;
        font-size: 14px;
    }
    .error-message {
        background-color: #f8d7da;
        color: #721c24;
        padding: 12px 16px;
        border-radius: 12px;
        margin-bottom: 20px;
        font-size: 14px;
    }
    .tenants-table-container {
        overflow-x: auto;
    }
    .tenants-table {
        width: 100%;
        border-collapse: collapse;
        background: #ffffff;
        border-radius: 12px;
        overflow: hidden;
    }
    .table-header {
        padding: 18px;
        text-align: right;
        background-color: #f8fafc;
        color: #4f46e5;
        font-weight: 600;
        border-bottom: 2px solid #e2e8f0;
    }
    .table-row {
        border-bottom: 1px solid #e2e8f0;
        transition: background-color 0.2s;
    }
    .table-row:hover {
        background-color: #f8fafc;
    }
    .table-cell {
        padding: 18px;
        text-align: right;
        vertical-align: middle;
    }
    .status-badge {
        padding: 6px 12px;
        border-radius: 9999px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }
    .status-badge.active {
        background-color: #d1fae5;
        color: #065f46;
    }
    .status-badge.suspended {
        background-color: #fef3c7;
        color: #92400e;
    }
    .status-badge.terminated {
        background-color: #fee2e2;
        color: #991b1b;
    }
    .tenant-image {
        cursor: pointer;
    }
    .tenant-image img {
        width: 64px;
        height: 64px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #e2e8f0;
        transition: transform 0.3s;
    }
    .tenant-image img:hover {
        transform: scale(1.05);
    }
    .no-image {
        color: #94a3b8;
        font-style: italic;
    }
    .actions-cell {
        min-width: 150px;
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
        width: 36px;
        height: 36px;
        border-radius: 50%;
        transition: all 0.3s;
    }
    .action-link.view {
        color: #3b82f6;
        background-color: #dbeafe;
    }
    .action-link.edit {
        color: #10b981;
        background-color: #d1fae5;
    }
    .action-link.delete {
        color: #ef4444;
        background-color: #fee2e2;
        border: none;
        cursor: pointer;
    }
    .action-link:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .action-icon {
        width: 18px;
        height: 18px;
    }
    .empty-table {
        padding: 40px;
        text-align: center;
    }
    .empty-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 16px;
    }
    .empty-icon {
        width: 80px;
        height: 80px;
        color: #cbd5e1;
    }
    .add-first-tenant {
        color: #4f46e5;
        text-decoration: none;
        font-weight: 600;
        padding: 8px 16px;
        border-radius: 8px;
        background-color: #e0e7ff;
        transition: all 0.3s;
    }
    .add-first-tenant:hover {
        background-color: #c7d2fe;
    }
    .pagination-wrapper {
        margin-top: 24px;
        display: flex;
        justify-content: center;
    }

    /* Image Modal Styles */
    .image-modal {
        position: fixed;
        inset: 0;
        background-color: rgba(0, 0, 0, 0.7);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        backdrop-filter: blur(5px);
    }
    .modal-content {
        position: relative;
        max-width: 90%;
        max-height: 90vh;
        padding: 16px;
    }
    .modal-image {
        max-width: 100%;
        max-height: 80vh;
        border-radius: 12px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
    }
    .modal-close-btn {
        position: absolute;
        top: -15px;
        right: -15px;
        color: white;
        background-color: #ef4444;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s;
    }
    .modal-close-btn:hover {
        transform: rotate(90deg);
        background-color: #dc2626;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .tenants-header {
            flex-direction: column;
            gap: 16px;
            align-items: flex-start;
        }
        .table-header {
            padding: 12px 8px;
            font-size: 14px;
        }
        .table-cell {
            padding: 12px 8px;
            font-size: 14px;
        }
        .actions-wrapper {
            gap: 8px;
        }
        .action-link {
            width: 30px;
            height: 30px;
        }
        .action-icon {
            width: 16px;
            height: 16px;
        }
    }
</style>

<!-- Enhanced Script -->
<script>
function showImageModal(imageUrl) {
    document.getElementById('modalImage').src = imageUrl;
    document.getElementById('imageModal').style.display = 'flex';
}
function closeImageModal() {
    document.getElementById('imageModal').style.display = 'none';
    document.getElementById('modalImage').src = '';
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
</script>
@endsection