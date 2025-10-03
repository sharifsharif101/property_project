 @extends('layouts.app')

@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            direction: rtl;
        }

        .google-form-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .google-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
            padding: 24px;
        }

        .form-title {
            font-size: 24px;
            font-weight: 500;
            color: #202124;
            margin-bottom: 8px;
        }

        .form-subtitle {
            color: #5f6368;
            font-size: 14px;
            margin-bottom: 24px;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-error {
            background-color: #fce8e6;
            color: #c5221f;
            border: 1px solid #f28b82;
        }

        .alert-success {
            background-color: #e6f4ea;
            color: #137333;
            border: 1px solid #81c995;
        }

        .form-section {
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid #f1f3f4;
        }

        .form-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .section-title {
            font-size: 16px;
            font-weight: 500;
            color: #202124;
            margin: 16px 0 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-row {
            display: flex;
            gap: 16px;
            margin-bottom: 16px;
        }

        @media (max-width: 600px) {
            .form-row {
                flex-direction: column;
                gap: 12px;
            }
        }

        .form-group {
            flex: 1;
        }

        .form-label {
            display: block;
            font-size: 14px;
            color: #5f6368;
            margin-bottom: 6px;
            font-weight: 500;
        }

        .required {
            color: #d93025;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            font-size: 16px;
            border: 1px solid #dadce0;
            border-radius: 4px;
            background: white;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: #1a73e8;
            box-shadow: 0 0 0 1px #1a73e8;
        }

        textarea.form-control {
            min-height: 80px;
            resize: vertical;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 8px;
        }

        .form-checkbox {
            width: 18px;
            height: 18px;
            accent-color: #1a73e8;
        }

        .file-upload-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            background: #f1f3f4;
            color: #5f6368;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .file-upload-label:hover {
            background: #e8eaed;
        }

        .file-upload-input {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
        }

        .image-preview-container {
            margin-top: 12px;
            width: 120px;
            height: 120px;
            border: 1px solid #dadce0;
            border-radius: 4px;
            overflow: hidden;
            display: none;
        }

        .image-preview {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .remove-image-btn {
            position: absolute;
            top: 4px;
            left: 4px;
            width: 20px;
            height: 20px;
            background: #d93025;
            color: white;
            border: none;
            border-radius: 50%;
            font-size: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn {
            padding: 10px 16px;
            font-size: 14px;
            font-weight: 500;
            border-radius: 4px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background 0.2s, box-shadow 0.2s;
        }

        .btn-primary {
            background: #1a73e8;
            color: white;
            border: none;
            width: 100%;
            padding: 12px;
            font-size: 16px;
            margin-top: 16px;
        }

        .btn-primary:hover {
            background: #174ea6;
        }

        .btn-primary:active {
            background: #0d47a1;
        }
    </style>
@endpush

@section('content')
<div class="google-form-container">
    <div class="google-card">
        <h1 class="form-title">إضافة مستأجر جديد</h1>
        <p class="form-subtitle">املأ النموذج لإضافة مستأجر جديد إلى النظام</p>

        @if ($errors->any())
            <div class="alert alert-error">
                <ul style="list-style: none; padding: 0; margin: 0;">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('tenants.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Personal Information -->
            <div class="form-section">
                <h3 class="section-title">👤 المعلومات الشخصية</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">الاسم الأول <span class="required">*</span></label>
                        <input type="text" name="first_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">اسم الأب</label>
                        <input type="text" name="father_name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">الاسم الأخير <span class="required">*</span></label>
                        <input type="text" name="last_name" class="form-control" required>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="form-section">
                <h3 class="section-title">📞 معلومات الاتصال</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">رقم الهاتف <span class="required">*</span></label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">رقم بديل</label>
                        <input type="text" name="alternate_phone" class="form-control">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">رقم الواتساب</label>
                        <input type="text" name="whatsapp" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">البريد الإلكتروني</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                </div>
            </div>

            <!-- ID Information -->
            <div class="form-section">
                <h3 class="section-title">🪪 معلومات الهوية</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">نوع الهوية</label>
                        <select name="id_type" class="form-control">
                            <option value="national_card">بطاقة وطنية</option>
                            <option value="passport">جواز سفر</option>
                            <option value="residence">إقامة</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">رقم الهوية <span class="required">*</span></label>
                        <input type="text" name="id_number" class="form-control" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">تاريخ انتهاء الهوية</label>
                        <input type="date" name="id_expiry_date" class="form-control">
                    </div>
                    <div class="form-group">
                        <div class="checkbox-container">
                            <input type="checkbox" name="id_verified" id="id_verified" class="form-checkbox" value="1">
                            <label for="id_verified" class="form-label">تم التحقق من الهوية</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Housing and Work -->
            <div class="form-section">
                <h3 class="section-title">🏠 السكن والعمل</h3>
                <div class="form-group">
                    <label class="form-label">عنوان السكن</label>
                    <textarea name="address" class="form-control"></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">جهة العمل</label>
                        <input type="text" name="employer" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">الدخل الشهري</label>
                        <input type="number" step="0.01" name="monthly_income" class="form-control">
                    </div>
                </div>
            </div>

            <!-- Settings -->
            <div class="form-section">
                <h3 class="section-title">⚙️ الإعدادات</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">نوع المستأجر</label>
                        <select name="tenant_type" class="form-control">
                            <option value="individual">فرد</option>
                            <option value="company">شركة</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">صورة المستأجر</label>
                    <label class="file-upload-label">
                        اختر ملف
                        <input type="file" name="tenant_image" id="tenant_image" class="file-upload-input" accept="image/*">
                    </label>
                    <div id="image-preview-container" class="image-preview-container">
                        <img id="image-preview" class="image-preview" src="#">
                        <button type="button" id="remove-image" class="remove-image-btn">×</button>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="form-section">
                <div class="form-group">
                    <label class="form-label">ملاحظات إضافية</label>
                    <textarea name="notes" class="form-control"></textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> حفظ المستأجر
            </button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('tenant_image');
        const previewContainer = document.getElementById('image-preview-container');
        const preview = document.getElementById('image-preview');
        const removeBtn = document.getElementById('remove-image');

        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.src = e.target.result;
                    previewContainer.style.display = 'block';
                };
                reader.readAsDataURL(this.files[0]);
            }
        });

        removeBtn.addEventListener('click', () => {
            imageInput.value = '';
            previewContainer.style.display = 'none';
        });
    });
</script>
@endsection