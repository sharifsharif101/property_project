@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen p-4 sm:p-6 lg:p-8" dir="rtl">
    <div class="max-w-4xl mx-auto">

        <!-- Header -->
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-gray-800">تعديل بيانات المستأجر</h1>
            <p class="mt-2 text-sm text-gray-500">قم بتحديث معلومات المستأجر في النظام.</p>
        </div>

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-6" role="alert">
                <p class="font-bold">الرجاء إصلاح الأخطاء التالية:</p>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-6" role="alert">
                <p class="font-bold">{{ session('success') }}</p>
            </div>
        @endif

        <form action="{{ route('tenants.update', $tenant->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <div class="bg-white p-6 sm:p-8 rounded-xl border border-gray-200 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-4 mb-6">المعلومات الشخصية</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <!-- First Name -->
                    <div class="relative">
                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $tenant->first_name) }}" required class="peer block w-full px-3 py-2.5 text-gray-900 bg-transparent border-b-2 border-gray-300 rounded-t-md appearance-none focus:outline-none focus:ring-0 focus:border-indigo-600" placeholder=" " />
                        <label for="first_name" class="absolute text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:right-0 peer-focus:text-indigo-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">الاسم الأول <span class="text-red-500">*</span></label>
                    </div>
                    <!-- Father Name -->
                    <div class="relative">
                        <input type="text" id="father_name" name="father_name" value="{{ old('father_name', $tenant->father_name) }}" class="peer block w-full px-3 py-2.5 text-gray-900 bg-transparent border-b-2 border-gray-300 rounded-t-md appearance-none focus:outline-none focus:ring-0 focus:border-indigo-600" placeholder=" " />
                        <label for="father_name" class="absolute text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:right-0 peer-focus:text-indigo-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">اسم الأب</label>
                    </div>
                    <!-- Last Name -->
                    <div class="relative">
                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $tenant->last_name) }}" required class="peer block w-full px-3 py-2.5 text-gray-900 bg-transparent border-b-2 border-gray-300 rounded-t-md appearance-none focus:outline-none focus:ring-0 focus:border-indigo-600" placeholder=" " />
                        <label for="last_name" class="absolute text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:right-0 peer-focus:text-indigo-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">الاسم الأخير <span class="text-red-500">*</span></label>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 sm:p-8 rounded-xl border border-gray-200 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-4 mb-6">معلومات الاتصال</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Phone -->
                    <div class="relative">
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $tenant->phone) }}" required class="peer block w-full px-3 py-2.5 text-gray-900 bg-transparent border-b-2 border-gray-300 rounded-t-md appearance-none focus:outline-none focus:ring-0 focus:border-indigo-600" placeholder=" " />
                        <label for="phone" class="absolute text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:right-0 peer-focus:text-indigo-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">رقم الهاتف <span class="text-red-500">*</span></label>
                    </div>
                    <!-- Alternate Phone -->
                    <div class="relative">
                        <input type="text" id="alternate_phone" name="alternate_phone" value="{{ old('alternate_phone', $tenant->alternate_phone) }}" class="peer block w-full px-3 py-2.5 text-gray-900 bg-transparent border-b-2 border-gray-300 rounded-t-md appearance-none focus:outline-none focus:ring-0 focus:border-indigo-600" placeholder=" " />
                        <label for="alternate_phone" class="absolute text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:right-0 peer-focus:text-indigo-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">رقم بديل</label>
                    </div>
                    <!-- WhatsApp -->
                    <div class="relative">
                        <input type="text" id="whatsapp" name="whatsapp" value="{{ old('whatsapp', $tenant->whatsapp) }}" class="peer block w-full px-3 py-2.5 text-gray-900 bg-transparent border-b-2 border-gray-300 rounded-t-md appearance-none focus:outline-none focus:ring-0 focus:border-indigo-600" placeholder=" " />
                        <label for="whatsapp" class="absolute text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:right-0 peer-focus:text-indigo-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">رقم الواتساب</label>
                    </div>
                    <!-- Email -->
                    <div class="relative">
                        <input type="email" id="email" name="email" value="{{ old('email', $tenant->email) }}" class="peer block w-full px-3 py-2.5 text-gray-900 bg-transparent border-b-2 border-gray-300 rounded-t-md appearance-none focus:outline-none focus:ring-0 focus:border-indigo-600" placeholder=" " />
                        <label for="email" class="absolute text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:right-0 peer-focus:text-indigo-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">البريد الإلكتروني</label>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 sm:p-8 rounded-xl border border-gray-200 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-4 mb-6">معلومات الهوية</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- ID Type -->
                    <div>
                        <label for="id_type" class="block text-sm font-medium text-gray-700 mb-1">نوع الهوية</label>
                        <select id="id_type" name="id_type" class="block w-full px-3 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="national_card" {{ old('id_type', $tenant->id_type) == 'national_card' ? 'selected' : '' }}>بطاقة وطنية</option>
                            <option value="passport" {{ old('id_type', $tenant->id_type) == 'passport' ? 'selected' : '' }}>جواز سفر</option>
                            <option value="residence" {{ old('id_type', $tenant->id_type) == 'residence' ? 'selected' : '' }}>إقامة</option>
                        </select>
                    </div>
                    <!-- ID Number -->
                    <div class="relative">
                        <input type="text" id="id_number" name="id_number" value="{{ old('id_number', $tenant->id_number) }}" required class="peer block w-full px-3 py-2.5 text-gray-900 bg-transparent border-b-2 border-gray-300 rounded-t-md appearance-none focus:outline-none focus:ring-0 focus:border-indigo-600" placeholder=" " />
                        <label for="id_number" class="absolute text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:right-0 peer-focus:text-indigo-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">رقم الهوية <span class="text-red-500">*</span></label>
                    </div>
                    <!-- ID Expiry Date -->
                    <div>
                        <label for="id_expiry_date" class="block text-sm font-medium text-gray-700 mb-1">تاريخ انتهاء الهوية</label>
                        <input type="date" id="id_expiry_date" name="id_expiry_date" value="{{ old('id_expiry_date', $tenant->id_expiry_date) }}" class="block w-full px-3 py-2 bg-gray-50 border border-gray-300 text-gray-900 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <!-- ID Verified -->
                    <div class="flex items-center pt-6">
                        <input id="id_verified" name="id_verified" type="checkbox" value="1" {{ old('id_verified', $tenant->id_verified) ? 'checked' : '' }} class="h-5 w-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <label for="id_verified" class="mr-2 block text-sm text-gray-900">تم التحقق من الهوية</label>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 sm:p-8 rounded-xl border border-gray-200 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-4 mb-6">السكن والعمل</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Employer -->
                    <div class="relative">
                        <input type="text" id="employer" name="employer" value="{{ old('employer', $tenant->employer) }}" class="peer block w-full px-3 py-2.5 text-gray-900 bg-transparent border-b-2 border-gray-300 rounded-t-md appearance-none focus:outline-none focus:ring-0 focus:border-indigo-600" placeholder=" " />
                        <label for="employer" class="absolute text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:right-0 peer-focus:text-indigo-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">جهة العمل</label>
                    </div>
                    <!-- Monthly Income -->
                    <div class="relative">
                        <input type="number" id="monthly_income" name="monthly_income" step="0.01" value="{{ old('monthly_income', $tenant->monthly_income) }}" class="peer block w-full px-3 py-2.5 text-gray-900 bg-transparent border-b-2 border-gray-300 rounded-t-md appearance-none focus:outline-none focus:ring-0 focus:border-indigo-600" placeholder=" " />
                        <label for="monthly_income" class="absolute text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:right-0 peer-focus:text-indigo-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">الدخل الشهري</label>
                    </div>
                    <!-- Address -->
                    <div class="sm:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">عنوان السكن</label>
                        <textarea id="address" name="address" rows="3" class="block w-full px-3 py-2 bg-gray-50 border border-gray-300 text-gray-900 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ old('address', $tenant->address) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 sm:p-8 rounded-xl border border-gray-200 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-4 mb-6">إعدادات إضافية</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Tenant Type -->
                    <div>
                        <label for="tenant_type" class="block text-sm font-medium text-gray-700 mb-1">نوع المستأجر</label>
                        <select id="tenant_type" name="tenant_type" class="block w-full px-3 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="individual" {{ old('tenant_type', $tenant->tenant_type) == 'individual' ? 'selected' : '' }}>فرد</option>
                            <option value="company" {{ old('tenant_type', $tenant->tenant_type) == 'company' ? 'selected' : '' }}>شركة</option>
                        </select>
                    </div>
                    <!-- Tenant Image -->
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">صورة المستأجر</label>
                        <div class="flex items-center gap-4">
                            <div id="image-preview-container" class="relative {{ $tenant->image_path ? '' : 'hidden' }}">
                                <img id="image-preview" src="{{ $tenant->image_path ? asset('uploads/' . $tenant->image_path) : '' }}" alt="صورة المستأجر" class="h-20 w-20 rounded-full object-cover">
                                <button type="button" id="remove-image" class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full h-6 w-6 flex items-center justify-center text-xs">&times;</button>
                            </div>
                            <label for="tenant_image" class="cursor-pointer bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <span>تغيير الصورة</span>
                                <input id="tenant_image" name="tenant_image" type="file" class="sr-only" accept="image/*">
                            </label>
                        </div>
                    </div>
                    <!-- Notes -->
                    <div class="sm:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">ملاحظات</label>
                        <textarea id="notes" name="notes" rows="4" class="block w-full px-3 py-2 bg-gray-50 border border-gray-300 text-gray-900 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ old('notes', $tenant->notes) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-4 pt-4">
                <a href="{{ route('tenants.show', $tenant->id) }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">إلغاء</a>
                <button type="submit" class="inline-flex items-center justify-center px-6 py-3 text-sm font-semibold text-white bg-indigo-600 rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                    <span>💾 حفظ التعديلات</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('tenant_image');
    const imagePreviewContainer = document.getElementById('image-preview-container');
    const imagePreview = document.getElementById('image-preview');
    const removeImageBtn = document.getElementById('remove-image');
    
    let removeImageHiddenInput = document.querySelector('input[name="remove_image"]');
    if (!removeImageHiddenInput) {
        removeImageHiddenInput = document.createElement('input');
        removeImageHiddenInput.type = 'hidden';
        removeImageHiddenInput.name = 'remove_image';
        document.querySelector('form').appendChild(removeImageHiddenInput);
    }
    removeImageHiddenInput.value = '0';

    imageInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreviewContainer.classList.remove('hidden');
                removeImageHiddenInput.value = '0';
            }
            reader.readAsDataURL(file);
        }
    });

    removeImageBtn.addEventListener('click', function() {
        imageInput.value = ''; // Clear the file input
        imagePreview.src = '';
        imagePreviewContainer.classList.add('hidden');
        removeImageHiddenInput.value = '1';
    });
});
</script>
@endpush
@endsection
