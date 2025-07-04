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
                    <h1 class="form-title">إضافة مستأجر جديد</h1>
                    <p class="form-subtitle">املأ النموذج لإضافة مستأجر جديد إلى النظام</p>
                </div>

                @if(session('success'))
                    <div class="success-message">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('tenants.store') }}" method="POST" enctype="multipart/form-data" class="tenant-form-content">
                    @csrf

                    <!-- Personal Information Section -->
                    <div class="form-section">
                        <h3 class="section-title">👤 المعلومات الشخصية</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">الاسم الأول <span class="required">*</span></label>
                                <input type="text" name="first_name" class="form-input" placeholder="أدخل الاسم الأول" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">اسم الأب</label>
                                <input type="text" name="father_name" class="form-input" placeholder="أدخل اسم الأب">
                            </div>
                            <div class="form-group">
                                <label class="form-label">الاسم الأخير <span class="required">*</span></label>
                                <input type="text" name="last_name" class="form-input" placeholder="أدخل الاسم الأخير" required>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information Section -->
                    <div class="form-section">
                        <h3 class="section-title">📞 معلومات الاتصال</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">رقم الهاتف <span class="required">*</span></label>
                                <input type="text" name="phone" class="form-input" placeholder="أدخل رقم الهاتف" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">رقم بديل</label>
                                <input type="text" name="alternate_phone" class="form-input" placeholder="أدخل رقم بديل">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">رقم الواتساب</label>
                                <input type="text" name="whatsapp" class="form-input" placeholder="أدخل رقم الواتساب">
                            </div>
                            <div class="form-group">
                                <label class="form-label">البريد الإلكتروني</label>
                                <input type="email" name="email" class="form-input" placeholder="أدخل البريد الإلكتروني">
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
                                    <option value="national_card">بطاقة وطنية</option>
                                    <option value="passport">جواز سفر</option>
                                    <option value="residence">إقامة</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">رقم الهوية <span class="required">*</span></label>
                                <input type="text" name="id_number" class="form-input" placeholder="أدخل رقم الهوية" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">تاريخ انتهاء الهوية</label>
                                <input type="date" name="id_expiry_date" class="form-input">
                            </div>
                            <div class="form-group checkbox-container">
                                <input type="checkbox" name="id_verified" value="1" id="id_verified" class="form-checkbox">
                                <label for="id_verified" class="checkbox-label">تم التحقق من الهوية</label>
                            </div>
                        </div>
                    </div>

                    <!-- Housing and Work Section -->
                    <div class="form-section">
                        <h3 class="section-title">🏠 السكن والعمل</h3>
                        <div class="form-group">
                            <label class="form-label">عنوان السكن</label>
                            <textarea name="address" rows="2" class="form-input" placeholder="أدخل عنوان السكن"></textarea>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">جهة العمل</label>
                                <input type="text" name="employer" class="form-input" placeholder="أدخل جهة العمل">
                            </div>
                            <div class="form-group">
                                <label class="form-label">الدخل الشهري</label>
                                <input type="number" name="monthly_income" step="0.01" class="form-input" placeholder="أدخل الدخل الشهري">
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
                                    <option value="individual">فرد</option>
                                    <option value="company">شركة</option>
                                </select>
                            </div>
                   
                        </div>
                        <div class="form-group">
                            <label class="form-label">صورة المستأجر</label>
                            <div class="file-upload">
                                <label class="file-upload-label">
                                    <span class="file-upload-text">اختر ملف</span>
                                    <input type="file" name="tenant_image" id="tenant_image" class="file-upload-input" accept="image/*">
                                    
                                </label>
                            </div>
                            <div id="image-preview-container" class="image-preview-container" style="display: none;">
                                <img id="image-preview" class="image-preview" src="#" alt="صورة المستأجر">
                                <button type="button" id="remove-image" class="remove-image-btn">×</button>
                            </div>
                        </div>
                    </div>

                    <!-- Notes Section -->
                    <div class="form-section">
                        <div class="form-group">
                            <label class="form-label">ملاحظات إضافية</label>
                            <textarea name="notes" rows="3" class="form-input" placeholder="أدخل أي ملاحظات إضافية"></textarea>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn">💾 حفظ المستأجر</button>
                </form>
            </div>
        </div>
    </div>
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
</style>

<style>
    /* الإضافات الجديدة للصورة */
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
    }

    .remove-image-btn:hover {
        background: #dc2626;
        transform: scale(1.1);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('tenant_image');
        const imagePreviewContainer = document.getElementById('image-preview-container');
        const imagePreview = document.getElementById('image-preview');
        const removeImageBtn = document.getElementById('remove-image');

        // عند اختيار صورة
        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreviewContainer.style.display = 'block';
                }
                
                reader.readAsDataURL(this.files[0]);
            }
        });

        // عند النقر على زر إزالة الصورة
        removeImageBtn.addEventListener('click', function() {
            imageInput.value = '';
            imagePreview.src = '#';
            imagePreviewContainer.style.display = 'none';
        });
    });
</script>
@endsection