@extends('layouts.app')

@section('content')
<div class="tenant-show-container">
    <div class="tenant-show-wrapper">
        <!-- Header Section -->
        <div class="tenant-show-header">
            <h1 class="tenant-show-title animate__animated animate__fadeInDown">تفاصيل المستأجر</h1>
            <div class="header-actions">
                <a href="{{ route('tenants.index') }}" class="back-btn">
                    <svg class="back-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    العودة للقائمة
                </a>
                <div class="action-buttons">
                    <a href="{{ route('tenants.edit', $tenant->id) }}" class="edit-btn">
                        <svg class="edit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        تعديل
                    </a>
                    <form action="{{ route('tenants.destroy', $tenant->id) }}" method="POST" class="delete-form" onsubmit="return confirm('هل أنت متأكد من حذف هذا المستأجر؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-btn">
                            <svg class="delete-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            حذف
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div id="successMessage" class="success-message animate__animated animate__fadeIn">
                <div class="message-content">
                    <svg class="message-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button class="close-message-btn" onclick="closeMessage('successMessage')">×</button>
                <div class="progress-bar">
                    <div class="progress-fill"></div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div id="errorMessage" class="error-message animate__animated animate__fadeIn">
                <div class="message-content">
                    <svg class="message-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button class="close-message-btn" onclick="closeMessage('errorMessage')">×</button>
                <div class="progress-bar">
                    <div class="progress-fill"></div>
                </div>
            </div>
        @endif

        <!-- Tenant Details -->
        <div class="tenant-details-container">
            <div class="tenant-profile-section">
                <div class="tenant-image-wrapper" onclick="showImageModal('{{ $tenant->image_path ? asset('storage/' . $tenant->image_path) : asset('images/default-avatar.png') }}')">
                    @if($tenant->image_path)
                        <img src="{{ asset('storage/' . $tenant->image_path) }}" 
                             alt="صورة {{ $tenant->first_name }}" 
                             class="tenant-image">
                    @else
                        <div class="no-image-placeholder">
                            <svg class="no-image-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    @endif
                    <div class="image-overlay">
                        <svg class="eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                </div>
                
                <div class="tenant-basic-info">
                    <h2 class="tenant-name">
                        {{ $tenant->first_name }} 
                        {{ $tenant->father_name ? $tenant->father_name . ' ' : '' }}
                        {{ $tenant->last_name }}
                    </h2>
                    <div class="tenant-status {{ $tenant->status }}">
                        @switch($tenant->status)
                            @case('active') نشط @break
                            @case('suspended') موقوف @break
                            @default منتهي
                        @endswitch
                    </div>
                    <div class="tenant-contact">
                        @if($tenant->phone)
                            <div class="contact-item">
                                <svg class="contact-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <span>{{ $tenant->phone }}</span>
                            </div>
                        @endif
                        @if($tenant->alternate_phone)
                            <div class="contact-item">
                                <svg class="contact-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <span>{{ $tenant->alternate_phone }} (بديل)</span>
                            </div>
                        @endif
                        @if($tenant->email)
                            <div class="contact-item">
                                <svg class="contact-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span>{{ $tenant->email }}</span>
                            </div>
                        @endif
                        @if($tenant->whatsapp)
                            <div class="contact-item">
                                <svg class="contact-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18.5a6.5 6.5 0 006.5-6.5c0-3.59-2.91-6.5-6.5-6.5S5.5 8.41 5.5 12a6.5 6.5 0 006.5 6.5z"></path>
                                </svg>
                                <span>{{ $tenant->whatsapp }} (واتساب)</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="tenant-details-sections">
                <!-- Personal Information Section -->
                <div class="details-section">
                    <div class="section-header">
                        <svg class="section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <h3>المعلومات الشخصية</h3>
                    </div>
                    <div class="section-content">
                        <div class="detail-row">
                            <span class="detail-label">نوع الهوية:</span>
                            @php
                                $idTypes = [
                                    'national_card' => 'بطاقة وطنية',
                                    'passport' => 'جواز سفر',
                                    'residence' => 'إقامة',
                                ];
                            @endphp
                            <span class="detail-value">{{ $idTypes[$tenant->id_type] ?? 'غير معروف' }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">رقم الهوية:</span>
                            <span class="detail-value">{{ $tenant->id_number ?? '-' }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">تاريخ انتهاء الهوية:</span>
                            <span class="detail-value">{{ $tenant->id_expiry_date ? \Carbon\Carbon::parse($tenant->id_expiry_date)->format('d/m/Y') : '-' }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">تم التحقق من الهوية:</span>
                            <span class="detail-value">{{ $tenant->id_verified ? 'نعم' : 'لا' }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">جهة العمل:</span>
                            <span class="detail-value">{{ $tenant->employer ?? '-' }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">الدخل الشهري:</span>
                            <span class="detail-value">{{ $tenant->monthly_income ? number_format($tenant->monthly_income, 2) . ' ريال' : '-' }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">نوع المستأجر:</span>
                            <span class="detail-value">{{ $tenant->tenant_type == 'individual' ? 'فرد' : 'شركة' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Address Information Section -->
                <div class="details-section">
                    <div class="section-header">
                        <svg class="section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <h3>العنوان</h3>
                    </div>
                    <div class="section-content">
                        <div class="detail-row">
                            <span class="detail-label">العنوان:</span>
                            <span class="detail-value">{{ $tenant->address ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Notes Section -->
                @if($tenant->notes)
                <div class="details-section">
                    <div class="section-header">
                        <svg class="section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h,6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3>ملاحظات</h3>
                    </div>
                    <div class="section-content">
                        <div class="notes-content">
                            {{ $tenant->notes }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="image-modal">
    <div class="modal-content" onclick="event.stopPropagation()">
        <img id="modalImage" src="/placeholder.svg" alt="صورة الهوية" class="modal-image">
        <button onclick="closeImageModal()" class="modal-close-btn">×</button>
        <button onclick="zoomImage(1.2)" class="modal-zoom-in">+</button>
        <button onclick="zoomImage(0.8)" class="modal-zoom-out">−</button>
    </div>
</div>

<style>
    /* Base Styles */
    .tenant-show-container {
        margin: 0;
        padding: 20px;
        box-sizing: border-box;
        font-family: 'Amiri', serif;
        background: linear-gradient(145deg, #e0e7ff, #fce7f3);
        min-height: 100vh;
        direction: rtl;
    }

    .tenant-show-wrapper {
        max-width: 1280px;
        margin: 0 auto;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        background: #ffffff;
        padding: 40px;
    }

    .tenant-show-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .tenant-show-title {
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

    .back-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        background: #e0e7ff;
        color: #4f46e5;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
    }

    .back-btn:hover {
        background: #c7d2fe;
        transform: translateY(-2px);
    }

    .back-icon {
        width: 20px;
        height: 20px;
    }

    .action-buttons {
        display: flex;
        gap: 12px;
    }

    .edit-btn, .delete-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s;
        cursor: pointer;
        border: none;
        font-family: inherit;
        font-size: inherit;
    }

    .edit-btn {
        background: #d1fae5;
        color: #10b981;
    }

    .edit-btn:hover {
        background: #a7f3d0;
        transform: translateY(-2px);
    }

    .delete-btn {
        background: #fee2e2;
        color: #ef4444;
    }

    .delete-btn:hover {
        background: #fecaca;
        transform: translateY(-2px);
    }

    .edit-icon, .delete-icon {
        width: 18px;
        height: 18px;
    }

    /* Tenant Profile Section */
    .tenant-profile-section {
        display: flex;
        align-items: center;
        gap: 32px;
        padding: 24px;
        background: #f9fafb;
        border-radius: 16px;
        margin-bottom: 32px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .tenant-image-wrapper {
        position: relative;
        width: 150px;
        height: 150px;
        border-radius: 50%;
        overflow: hidden;
        border: 4px solid #e5e7eb;
        cursor: pointer;
        transition: all 0.3s;
        flex-shrink: 0;
    }

    .tenant-image-wrapper:hover {
        border-color: #6366f1;
        transform: scale(1.05);
        box-shadow: 0 8px 25px rgba(99, 102, 241, 0.3);
    }

    .tenant-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .no-image-placeholder {
        width: 100%;
        height: 100%;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .no-image-icon {
        width: 60px;
        height: 60px;
        color: #9ca3af;
    }

    .image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .tenant-image-wrapper:hover .image-overlay {
        opacity: 1;
    }

    .eye-icon {
        width: 32px;
        height: 32px;
        color: white;
    }

    .tenant-basic-info {
        flex: 1;
    }

    .tenant-name {
        font-size: 28px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 8px;
    }

    .tenant-status {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 9999px;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 16px;
    }

    .tenant-status.active {
        background: #d1fae5;
        color: #065f46;
    }

    .tenant-status.suspended {
        background: #fef3c7;
        color: #92400e;
    }

    .tenant-status.terminated {
        background: #fee2e2;
        color: #991b1b;
    }

    .tenant-contact {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .contact-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #4b5563;
    }

    .contact-icon {
        width: 20px;
        height: 20px;
        color: #6b7280;
    }

    /* Details Sections */
    .tenant-details-sections {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 24px;
    }

    .details-section {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        border: 1px solid #e5e7eb;
        transition: all 0.3s;
    }

    .details-section:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px 24px;
        background: linear-gradient(135deg, #f3e7e9, #e0e7ff);
        border-bottom: 1px solid #e5e7eb;
    }

    .section-icon {
        width: 24px;
        height: 24px;
        color: #4f46e5;
    }

    .section-header h3 {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }

    .section-content {
        padding: 20px 24px;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px dashed #e5e7eb;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-weight: 600;
        color: #4b5563;
    }

    .detail-value {
        color: #1e293b;
        text-align: left;
        max-width: 60%;
    }

    .notes-content {
        padding: 12px;
        background: #f9fafb;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        line-height: 1.6;
    }

    /* Image Modal */
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
    @media (max-width: 1024px) {
        .tenant-profile-section {
            flex-direction: column;
            text-align: center;
        }

        .tenant-contact {
            align-items: center;
        }
    }

    @media (max-width: 768px) {
        .tenant-show-wrapper {
            padding: 20px;
        }

        .tenant-show-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .header-actions {
            width: 100%;
            justify-content: space-between;
        }

        .tenant-details-sections {
            grid-template-columns: 1fr;
        }

        .tenant-image-wrapper {
            width: 120px;
            height: 120px;
        }

        .tenant-name {
            font-size: 24px;
        }
    }

    @media (max-width: 480px) {
        .action-buttons {
            width: 100%;
            justify-content: space-between;
        }

        .edit-btn, .delete-btn {
            flex: 1;
            justify-content: center;
        }

        .section-header {
            padding: 12px 16px;
        }

        .section-content {
            padding: 16px;
        }

        .detail-row {
            flex-direction: column;
            gap: 4px;
        }

        .detail-value {
            max-width: 100%;
        }
    }
</style>

<script>
let currentZoom = 1;

// Auto-hide messages after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const successMessage = document.getElementById('successMessage');
    const errorMessage = document.getElementById('errorMessage');
    
    if (successMessage) {
        setTimeout(() => {
            hideMessage('successMessage');
        }, 5000);
    }
    
    if (errorMessage) {
        setTimeout(() => {
            hideMessage('errorMessage');
        }, 5000);
    }
});

function closeMessage(messageId) {
    hideMessage(messageId);
}

function hideMessage(messageId) {
    const message = document.getElementById(messageId);
    if (message) {
        message.classList.add('animate__fadeOut');
        setTimeout(() => {
            message.style.display = 'none';
        }, 500);
    }
}

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
</script>
@endsection