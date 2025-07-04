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

<div class="auth-container">
    <div class="form-wrapper">
        <div class="form-container">
            <!-- Tenant Form -->
            <div class="form-side tenant-form">
                <div class="form-header">
                    <h1 class="form-title">تعديل بيانات المستأجر</h1>
                    <p class="form-subtitle">قم بتعديل بيانات المستأجر في النظام</p>
                </div>

                @if(session('success'))
                    <div class="success-message">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('tenants.update', $tenant->id) }}" method="POST" enctype="multipart/form-data" class="tenant-form-content">
                    @csrf
                    @method('PUT')

                    <!-- Personal Information Section -->
                    <div class="form-section">
                        <h3 class="section-title">👤 المعلومات الشخصية</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">الاسم الأول <span class="required">*</span></label>
                                <input type="text" name="first_name" class="form-input" placeholder="أدخل الاسم الأول" value="{{ old('first_name', $tenant->first_name) }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">اسم الأب</label>
                                <input type="text" name="father_name" class="form-input" placeholder="أدخل اسم الأب" value="{{ old('father_name', $tenant->father_name) }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">الاسم الأخير <span class="required">*</span></label>
                                <input type="text" name="last_name" class="form-input" placeholder="أدخل الاسم الأخير" value="{{ old('last_name', $tenant->last_name) }}" required>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information Section -->
                    <div class="form-section">
                        <h3 class="section-title">📞 معلومات الاتصال</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">رقم الهاتف <span class="required">*</span></label>
                                <input type="text" name="phone" class="form-input" placeholder="أدخل رقم الهاتف" value="{{ old('phone', $tenant->phone) }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">رقم بديل</label>
                                <input type="text" name="alternate_phone" class="form-input" placeholder="أدخل رقم بديل" value="{{ old('alternate_phone', $tenant->alternate_phone) }}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">رقم الواتساب</label>
                                <input type="text" name="whatsapp" class="form-input" placeholder="أدخل رقم الواتساب" value="{{ old('whatsapp', $tenant->whatsapp) }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">البريد الإلكتروني</label>
                                <input type="email" name="email" class="form-input" placeholder="أدخل البريد الإلكتروني" value="{{ old('email', $tenant->email) }}">
                            </div>
                        </div>
                    </div>

                    <!-- ID Information Section -->
                    <div class="form-section">
                        <h3 class="section-title">🪪 معلومات الهوية</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">نوع الهوية</label>
                                <select name="id_type" class="form-input">
                                    <option value="national_card" {{ old('id_type', $tenant->id_type) == 'national_card' ? 'selected' : '' }}>بطاقة وطنية</option>
                                    <option value="passport" {{ old('id_type', $tenant->id_type) == 'passport' ? 'selected' : '' }}>جواز سفر</option>
                                    <option value="residence" {{ old('id_type', $tenant->id_type) == 'residence' ? 'selected' : '' }}>إقامة</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">رقم الهوية <span class="required">*</span></label>
                                <input type="text" name="id_number" class="form-input" placeholder="أدخل رقم الهوية" value="{{ old('id_number', $tenant->id_number) }}" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">تاريخ انتهاء الهوية</label>
                                <input type="date" name="id_expiry_date" class="form-input" value="{{ old('id_expiry_date', $tenant->id_expiry_date) }}">
                            </div>
                            <div class="form-group checkbox-container">
                                <input type="checkbox" name="id_verified" value="1" id="id_verified" class="form-checkbox" {{ old('id_verified', $tenant->id_verified) ? 'checked' : '' }}>
                                <label for="id_verified" class="checkbox-label">تم التحقق من الهوية</label>
                            </div>
                        </div>
                    </div>

                    <!-- Housing and Work Section -->
                    <div class="form-section">
                        <h3 class="section-title">🏠 السكن والعمل</h3>
                        <div class="form-group">
                            <label class="form-label">عنوان السكن</label>
                            <textarea name="address" rows="2" class="form-input" placeholder="أدخل عنوان السكن">{{ old('address', $tenant->address) }}</textarea>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">جهة العمل</label>
                                <input type="text" name="employer" class="form-input" placeholder="أدخل جهة العمل" value="{{ old('employer', $tenant->employer) }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">الدخل الشهري</label>
                                <input type="number" name="monthly_income" step="0.01" class="form-input" placeholder="أدخل الدخل الشهري" value="{{ old('monthly_income', $tenant->monthly_income) }}">
                            </div>
                        </div>
                    </div>

                    <!-- Settings Section -->
                    <div class="form-section">
                        <h3 class="section-title">⚙️ الإعدادات</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">نوع المستأجر</label>
                                <select name="tenant_type" class="form-input">
                                    <option value="individual" {{ old('tenant_type', $tenant->tenant_type) == 'individual' ? 'selected' : '' }}>فرد</option>
                                    <option value="company" {{ old('tenant_type', $tenant->tenant_type) == 'company' ? 'selected' : '' }}>شركة</option>
                                </select>
                            </div>
                       
                        </div>

                        {{-- Image Upload and Preview Section --}}
                        <div class="form-group">
                            <label class="form-label">صورة المستأجر</label>
                            <div class="file-upload">
                                <label class="file-upload-label">
                                    <span class="file-upload-text">اختر ملف</span>
                                    <input type="file" name="tenant_image" id="tenant_image" class="file-upload-input" accept="image/*">
                                </label>
                            </div>
                            <div id="image-preview-container" class="image-preview-container"
                                style="display: {{ $tenant->image_path ? 'block' : 'none' }};">
                                <img id="image-preview" class="image-preview"
                                    src="{{ $tenant->image_path ? asset('storage/' . $tenant->image_path) : '' }}"
                                    alt="صورة المستأجر"
                                    data-existing-image="{{ $tenant->image_path ? asset('storage/' . $tenant->image_path) : '' }}">
                                
                                {{-- Updated overlay without hardcoded onclick --}}
                                <div class="tenant-image-overlay" id="image-overlay">
                                    <i class="fas fa-eye"></i>
                                </div>
                                
                                <button type="button" id="remove-image" class="remove-image-btn">×</button>
                            </div>
                        </div>
                        {{-- End Image Upload and Preview Section --}}
                    </div>

                    <!-- Notes Section -->
                    <div class="form-section">
                        <div class="form-group">
                            <label class="form-label">ملاحظات إضافية</label>
                            <textarea name="notes" rows="3" class="form-input" placeholder="أدخل أي ملاحظات إضافية">{{ old('notes', $tenant->notes) }}</textarea>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn">💾 حفظ التعديلات</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="imageModal" class="modal">
    <span class="close-modal-btn" id="close-modal">&times;</span>
    <img class="modal-content" id="img01">
</div>

<style>
    /* Base Styles */
    .auth-container {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        background: linear-gradient(145deg, #e0e7ff, #fce7f3);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        direction: rtl;
    }
    .auth-container *,
    .auth-container *::before,
    .auth-container *::after {
        box-sizing: border-box;
    }
    .form-wrapper {
        position: relative;
        width: 900px;
        height: auto;
        min-height: 680px;
        perspective: 1000px;
    }
    .form-container {
        position: relative;
        width: 100%;
        height: auto;
        min-height: 680px;
        transform-style: preserve-3d;
    }
    .form-side {
        position: relative;
        width: 100%;
        height: auto;
        min-height: 680px;
        backface-visibility: hidden;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 24px;
        padding: 32px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
    }
    .tenant-form {
        animation: fadeInUp 0.6s ease-out;
    }
    .form-header {
        text-align: center;
        margin-bottom: 24px;
    }
    .form-title {
        font-size: 28px;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 8px 0;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        background-clip: text;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .form-subtitle {
        color: #64748b;
        font-size: 14px;
        margin: 0;
    }
    .success-message {
        background-color: #d1fae5;
        color: #065f46;
        padding: 12px 16px;
        border-radius: 12px;
        margin-bottom: 20px;
        font-size: 14px;
        text-align: center;
    }
    .tenant-form-content {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    .form-section {
        background: rgba(241, 245, 249, 0.5);
        padding: 20px;
        border-radius: 16px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }
    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: #4f46e5;
        margin-top: 0;
        margin-bottom: 16px;
        padding-bottom: 8px;
        border-bottom: 2px solid #e2e8f0;
    }
    .form-row {
        display: flex;
        gap: 16px;
        margin-bottom: 16px;
    }
    .form-row:last-child {
        margin-bottom: 0;
    }
    .form-group {
        flex: 1;
        margin-bottom: 0;
    }
    .form-label {
        display: block;
        color: #334155;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 8px;
    }
    .required {
        color: #ef4444;
    }
    .form-input {
        width: 100%;
        padding: 14px 18px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 16px;
        background: rgba(255, 255, 255, 0.8);
        transition: all 0.3s ease;
        outline: none;
    }
    .form-input:focus {
        border-color: #6366f1;
        background: rgba(255, 255, 255, 1);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(99, 102, 241, 0.15);
    }
    .form-input::placeholder {
        color: #a0aec0;
    }
    textarea.form-input {
        min-height: 80px;
        resize: vertical;
    }
    select.form-input {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2364758b' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: left 15px center;
        background-size: 16px 12px;
        padding-right: 15px;
    }
    .checkbox-container {
        display: flex;
        align-items: center;
        margin-top: 24px;
    }
    .form-checkbox {
        width: 18px;
        height: 18px;
        accent-color: #6366f1;
        margin-left: 8px;
    }
    .checkbox-label {
        color: #334155;
        font-size: 14px;
        font-weight: 500;
    }
    .file-upload {
        position: relative;
    }
    .file-upload-label {
        display: inline-flex;
        align-items: center;
        cursor: pointer;
    }
    .file-upload-text {
        padding: 12px 18px;
        background-color: #e0e7ff;
        color: #4f46e5;
        border-radius: 12px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .file-upload-label:hover .file-upload-text {
        background-color: #c7d2fe;
    }
    .file-upload-input {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        border: 0;
    }
    .submit-btn {
        width: 100%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border: none;
        padding: 16px;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        margin-top: 20px;
    }
    .submit-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }
    .submit-btn:hover::before {
        left: 100%;
    }
    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 35px rgba(99, 102, 241, 0.3);
    }
    .submit-btn:active {
        transform: translateY(0);
    }
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .form-wrapper {
            width: 100%;
            padding: 15px;
        }
        .form-row {
            flex-direction: column;
            gap: 12px;
        }
        .form-side {
            padding: 20px;
        }
    }
    /* Image Preview Styles */
    .image-preview-container {
        position: relative;
        margin-top: 15px;
        width: 150px;
        height: 150px;
        border-radius: 12px;
        overflow: hidden;
        border: 2px dashed #e2e8f0;
    }
    .image-preview {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .remove-image-btn {
        position: absolute;
        top: 5px;
        left: 5px;
        background: #ef4444;
        color: white;
        border: none;
        width: 25px;
        height: 25px;
        border-radius: 50%;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
        z-index: 10;
    }
    .remove-image-btn:hover {
        background: #dc2626;
        transform: scale(1.1);
    }
    .tenant-image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border-radius: 12px;
        z-index: 5;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .image-preview-container:hover .tenant-image-overlay {
        opacity: 1;
    }
    .tenant-image-overlay i {
        color: white;
        font-size: 24px;
        filter: drop-shadow(0 0 5px rgba(0,0,0,0.5));
    }
    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        padding-top: 60px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0,0,0);
        background-color: rgba(0,0,0,0.9);
    }
    .modal-content {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        animation-name: zoom;
        animation-duration: 0.6s;
    }
    .close-modal-btn {
        position: absolute;
        top: 15px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
        cursor: pointer;
    }
    .close-modal-btn:hover,
    .close-modal-btn:focus {
        color: #bbb;
        text-decoration: none;
        cursor: pointer;
    }
    @keyframes zoom {
        from {transform:scale(0)}
        to {transform:scale(1)}
    }
    @media only screen and (max-width: 700px){
        .modal-content {
            width: 100%;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('tenant_image');
    const imagePreviewContainer = document.getElementById('image-preview-container');
    const imagePreview = document.getElementById('image-preview');
    const removeImageBtn = document.getElementById('remove-image');
    const imageOverlay = document.getElementById('image-overlay');
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('img01');
    const closeModalBtn = document.getElementById('close-modal');

    // Create hidden input for image removal
    let removeImageHiddenInput = document.createElement('input');
    removeImageHiddenInput.type = 'hidden';
    removeImageHiddenInput.name = 'remove_image';
    removeImageHiddenInput.value = '0';
    document.querySelector('form').appendChild(removeImageHiddenInput);

    // Get the existing image URL from the data attribute
    const existingImageUrl = imagePreview.getAttribute('data-existing-image');

    // Initialize with existing image
    if (existingImageUrl && existingImageUrl.trim() !== '') {
        imagePreview.src = existingImageUrl;
        imagePreviewContainer.style.display = 'block';
    } else {
        imagePreviewContainer.style.display = 'none';
    }

    // Handle new image selection
    imageInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreviewContainer.style.display = 'block';
            };
            reader.readAsDataURL(this.files[0]);
            removeImageHiddenInput.value = '0';
        } else {
            // If user clears input
            if (existingImageUrl && existingImageUrl.trim() !== '') {
                // Revert to existing image
                imagePreview.src = existingImageUrl;
                imagePreviewContainer.style.display = 'block';
                removeImageHiddenInput.value = '0';
            } else {
                // No existing image and no new file selected
                imagePreviewContainer.style.display = 'none';
                removeImageHiddenInput.value = '0';
            }
        }
    });

    // Handle image removal
    removeImageBtn.addEventListener('click', function() {
        imageInput.value = '';
        imagePreview.src = '';
        imagePreviewContainer.style.display = 'none';
        removeImageHiddenInput.value = '1';
    });

    // Handle image overlay click - show modal with current image
    imageOverlay.addEventListener('click', function() {
        const currentImageSrc = imagePreview.src;
        if (currentImageSrc && currentImageSrc.trim() !== '') {
            modal.style.display = 'block';
            modalImg.src = currentImageSrc;
        }
    });

    // Handle modal close button
    closeModalBtn.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    // Close modal when clicking outside the image
    modal.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && modal.style.display === 'block') {
            modal.style.display = 'none';
        }
    });
});
</script>

@endsection
