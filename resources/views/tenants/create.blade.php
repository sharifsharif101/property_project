@extends('layouts.app')

@section('title', 'إضافة مستأجر جديد')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 max-w-6xl">

    {{-- Page Header --}}
    <header class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight mb-2">إضافة مستأجر جديد</h1>
                <p class="text-lg text-gray-600">املأ النموذج لإضافة مستأجر جديد إلى النظام</p>
            </div>
            <div class="flex items-center">
                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>
    </header>

    {{-- Session Messages --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 p-4 rounded-xl mb-6 shadow-sm flex items-start gap-3">
            <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-xl mb-6 shadow-sm flex items-start gap-3">
            <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            <div>
                <p class="font-bold mb-1">حدثت أخطاء في النموذج</p>
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- Main Form --}}
    <form action="{{ route('tenants.store') }}" method="POST" enctype="multipart/form-data" 
          class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
        @csrf
        
        <div class="p-6 sm:p-8 space-y-8">
            
            {{-- Personal Information --}}
            <div class="space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-gray-100">
                    <div class="w-2 h-8 bg-indigo-600 rounded-full"></div>
                    <h3 class="text-xl font-semibold text-gray-800">المعلومات الشخصية</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">
                            الاسم الأول <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   id="first_name" 
                                   name="first_name" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white shadow-sm focus:shadow-md"
                                   value="{{ old('first_name') }}" 
                                   required>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="father_name" class="block text-sm font-medium text-gray-700 mb-1">
                            اسم الأب
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   id="father_name" 
                                   name="father_name" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white shadow-sm focus:shadow-md"
                                   value="{{ old('father_name') }}">
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">
                            الاسم الأخير <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   id="last_name" 
                                   name="last_name" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white shadow-sm focus:shadow-md"
                                   value="{{ old('last_name') }}" 
                                   required>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Contact Information --}}
            <div class="space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-gray-100">
                    <div class="w-2 h-8 bg-indigo-600 rounded-full"></div>
                    <h3 class="text-xl font-semibold text-gray-800">معلومات الاتصال</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                            رقم الهاتف <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   id="phone" 
                                   name="phone" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white shadow-sm focus:shadow-md"
                                   value="{{ old('phone') }}" 
                                   required>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="alternate_phone" class="block text-sm font-medium text-gray-700 mb-1">
                            رقم بديل
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   id="alternate_phone" 
                                   name="alternate_phone" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white shadow-sm focus:shadow-md"
                                   value="{{ old('alternate_phone') }}">
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="whatsapp" class="block text-sm font-medium text-gray-700 mb-1">
                            رقم الواتساب
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   id="whatsapp" 
                                   name="whatsapp" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white shadow-sm focus:shadow-md"
                                   value="{{ old('whatsapp') }}">
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            البريد الإلكتروني
                        </label>
                        <div class="relative">
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white shadow-sm focus:shadow-md"
                                   value="{{ old('email') }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- ID Information --}}
            <div class="space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-gray-100">
                    <div class="w-2 h-8 bg-indigo-600 rounded-full"></div>
                    <h3 class="text-xl font-semibold text-gray-800">معلومات الهوية</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="id_type" class="block text-sm font-medium text-gray-700 mb-1">
                            نوع الهوية
                        </label>
                        <div class="relative">
                            <select id="id_type" 
                                    name="id_type" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white shadow-sm focus:shadow-md appearance-none">
                                <option value="national_card" {{ old('id_type') == 'national_card' ? 'selected' : '' }}>بطاقة وطنية</option>
                                <option value="passport" {{ old('id_type') == 'passport' ? 'selected' : '' }}>جواز سفر</option>
                                <option value="residence" {{ old('id_type') == 'residence' ? 'selected' : '' }}>إقامة</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-3 text-gray-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="id_number" class="block text-sm font-medium text-gray-700 mb-1">
                            رقم الهوية <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   id="id_number" 
                                   name="id_number" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white shadow-sm focus:shadow-md"
                                   value="{{ old('id_number') }}" 
                                   required>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="id_expiry_date" class="block text-sm font-medium text-gray-700 mb-1">
                            تاريخ انتهاء الهوية
                        </label>
                        <div class="relative">
                            <input type="date" 
                                   id="id_expiry_date" 
                                   name="id_expiry_date" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white shadow-sm focus:shadow-md"
                                   value="{{ old('id_expiry_date') }}">
                        </div>
                    </div>
                    
                    <div class="flex items-center pt-6">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="id_verified" 
                                   name="id_verified" 
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                   value="1" 
                                   {{ old('id_verified') == '1' ? 'checked' : '' }}>
                            <label for="id_verified" class="ml-3 block text-sm text-gray-700">
                                تم التحقق من الهوية
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Housing and Work --}}
            <div class="space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-gray-100">
                    <div class="w-2 h-8 bg-indigo-600 rounded-full"></div>
                    <h3 class="text-xl font-semibold text-gray-800">السكن والعمل</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2 md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
                            عنوان السكن
                        </label>
                        <div class="relative">
                            <textarea id="address" 
                                      name="address" 
                                      rows="3" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white shadow-sm focus:shadow-md resize-vertical"
                                      >{{ old('address') }}</textarea>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="employer" class="block text-sm font-medium text-gray-700 mb-1">
                            جهة العمل
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   id="employer" 
                                   name="employer" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white shadow-sm focus:shadow-md"
                                   value="{{ old('employer') }}">
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="monthly_income" class="block text-sm font-medium text-gray-700 mb-1">
                            الدخل الشهري
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   id="monthly_income" 
                                   step="0.01" 
                                   name="monthly_income" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white shadow-sm focus:shadow-md"
                                   value="{{ old('monthly_income') }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Settings & Image --}}
            <div class="space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-gray-100">
                    <div class="w-2 h-8 bg-indigo-600 rounded-full"></div>
                    <h3 class="text-xl font-semibold text-gray-800">الإعدادات والصورة</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="tenant_type" class="block text-sm font-medium text-gray-700 mb-1">
                            نوع المستأجر
                        </label>
                        <div class="relative">
                            <select id="tenant_type" 
                                    name="tenant_type" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white shadow-sm focus:shadow-md appearance-none">
                                <option value="individual" {{ old('tenant_type', 'individual') == 'individual' ? 'selected' : '' }}>فرد</option>
                                <option value="company" {{ old('tenant_type') == 'company' ? 'selected' : '' }}>شركة</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-3 text-gray-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            صورة المستأجر
                        </label>
                        <div class="flex items-center gap-4">
                            <label for="tenant_image" 
                                   class="inline-flex items-center gap-2 bg-indigo-600 text-white font-medium py-3 px-6 rounded-lg hover:bg-indigo-700 transition-all duration-200 shadow-sm hover:shadow-md cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <span>اختر صورة</span>
                            </label>
                            <input type="file" 
                                   name="tenant_image" 
                                   id="tenant_image" 
                                   class="hidden" 
                                   accept="image/*">
                            
                            <div id="image-preview-container" 
                                 class="relative w-20 h-20 border-2 border-dashed border-gray-300 rounded-lg overflow-hidden hidden">
                                <img id="image-preview" 
                                     class="w-full h-full object-cover" 
                                     src="#" 
                                     alt="Preview">
                                <button type="button" 
                                        id="remove-image" 
                                        class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-600 transition-colors shadow-sm">
                                    ×
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            <div class="space-y-2">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                    ملاحظات إضافية
                </label>
                <div class="relative">
                    <textarea id="notes" 
                              name="notes" 
                              rows="4" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white shadow-sm focus:shadow-md resize-vertical"
                              >{{ old('notes') }}</textarea>
                </div>
            </div>

        </div>

        {{-- Form Footer --}}
        <div class="bg-gray-50 px-6 sm:px-8 py-6 border-t border-gray-200">
            <div class="flex justify-end gap-4">
                <a href="{{ route('tenants.index') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span>إلغاء</span>
                </a>
                
                <button type="submit" 
                        class="inline-flex items-center gap-2 bg-indigo-600 text-white font-medium py-3 px-8 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>حفظ المستأجر</span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
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
                    previewContainer.classList.remove('hidden');
                };
                reader.readAsDataURL(this.files[0]);
            }
        });

        removeBtn.addEventListener('click', () => {
            imageInput.value = ''; // Clear the file input
            preview.src = '#';
            previewContainer.classList.add('hidden');
        });
    });
</script>
@endpush