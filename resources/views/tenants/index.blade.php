@extends('layouts.app')
@section('content')


   <style>
        /* تحسينات شاملة للجدول */
        .tenants-table-container {
            overflow-x: auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin: 20px;
        }
        
        .tenants-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 0.9em;
            min-width: 1000px;
        }
        
        .tenants-table th {
            background: #2c3e50;
            color: white;
            font-weight: 600;
            padding: 15px 12px;
            text-align: center;
            position: sticky;
            top: 0;
        }
        
        .tenants-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            text-align: center;
            vertical-align: middle;
        }
        
        .tenants-table tr {
            transition: all 0.2s ease;
        }
        
        .tenants-table tr:hover {
            background-color: #f8f9fa;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        
        .table-header {
            font-size: 1em;
            letter-spacing: 0.5px;
        }
        
        .table-row:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .table-cell {
            color: #34495e;
            font-size: 0.95em;
        }
        
        .actions-cell {
            width: 150px;
        }
        
        .actions-wrapper {
            display: flex;
            justify-content: center;
            gap: 8px;
        }
        
        .action-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            transition: all 0.3s ease;
            color: white;
        }
        
        .action-link.view {
            background: #3498db;
        }
        
        .action-link.edit {
            background: #f39c12;
        }
        
        .action-link.delete {
            background: #e74c3c;
        }
        
        .action-link:hover {
            transform: scale(1.1);
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
        }
        
        .action-icon {
            width: 16px;
            height: 16px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 500;
            min-width: 70px;
        }
        
        .status-badge.active {
            background: rgba(46, 204, 113, 0.2);
            color: #27ae60;
            border: 1px solid #27ae60;
        }
        
        .status-badge.suspended {
            background: rgba(231, 76, 60, 0.2);
            color: #c0392b;
            border: 1px solid #c0392b;
        }
        
        .tenant-image-wrapper {
            width: 60px;
            height: 60px;
            margin: 0 auto;
            overflow: hidden;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid #eee;
        }
        
        .tenant-image-wrapper:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .tenant-id-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }
        
        .no-image {
            display: inline-block;
            padding: 5px 10px;
            background: #f1f2f6;
            border-radius: 4px;
            color: #7f8c8d;
            font-size: 0.9em;
        }
        
        /* تحسينات للعناوين */
        .page-title {
            padding: 20px 30px 0;
            color: #2c3e50;
            font-size: 1.8em;
            font-weight: 600;
        }
        
        /* زر إضافة جديد */
        .add-tenant-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #27ae60;
            color: white;
            padding: 10px 20px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 500;
            margin: 0 30px 20px;
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(39, 174, 96, 0.3);
        }
        
        .add-tenant-btn:hover {
            background: #219653;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(39, 174, 96, 0.4);
        }
        
        /* تحسينات متجاوبة */
        @media (max-width: 1200px) {
            .tenants-table-container {
                margin: 10px;
                padding: 15px;
            }
            
            .tenants-table th,
            .tenants-table td {
                padding: 10px 8px;
            }
        }
        
        @media (max-width: 768px) {
            .tenants-table th,
            .tenants-table td {
                font-size: 0.85em;
                padding: 8px 6px;
            }
            
            .action-link {
                width: 28px;
                height: 28px;
            }
            
            .action-icon {
                width: 14px;
                height: 14px;
            }
        }
    </style>

<div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
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
        <div class="tenant-image-wrapper">
            <img src="{{ asset('storage/' . $tenant->image_path) }}" 
                 alt="هوية {{ $tenant->first_name }}"
                 class="tenant-id-image"
                 onclick="showImageModal('{{ asset('storage/' . $tenant->image_path) }}')">
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




 
</div>

 
@endsection