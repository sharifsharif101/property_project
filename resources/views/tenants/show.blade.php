@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen p-4 sm:p-6 lg:p-8" dir="rtl">
    <div class="max-w-7xl mx-auto">

        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">ملف المستأجر</h1>
                    <p class="mt-1 text-sm text-gray-500">عرض جميع التفاصيل المتعلقة بالمستأجر.</p>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <a href="{{ route('tenants.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.707-10.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L9.414 11H13a1 1 0 100-2H9.414l1.293-1.293z" clip-rule="evenodd" />
                        </svg>
                        <span>العودة للقائمة</span>
                    </a>
                    <a href="{{ route('tenants.edit', $tenant->id) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-lg shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                            <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                        </svg>
                        <span>تعديل</span>
                    </a>
                    <form action="{{ route('tenants.destroy', $tenant->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا المستأجر؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-red-700 bg-red-100 border border-transparent rounded-lg shadow-sm hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <span>حذف</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
                <strong class="font-bold">نجاح!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
                <strong class="font-bold">خطأ!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Left Column: Profile Card -->
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <div class="flex flex-col items-center text-center">
                        <div id="imageToOpenModal" class="relative mb-4 cursor-pointer">
                            @if($tenant->image_path)
                                <img src="{{ asset('uploads/' . $tenant->image_path) }}" alt="صورة {{ $tenant->first_name }}" class="w-32 h-32 rounded-full object-cover ring-4 ring-indigo-100">
                            @else
                                <span class="inline-flex items-center justify-center h-32 w-32 rounded-full bg-gray-100 ring-4 ring-gray-200">
                                    <svg class="h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </span>
                            @endif
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">
                            {{ $tenant->first_name }} {{ $tenant->father_name }} {{ $tenant->last_name }}
                        </h2>
                        <p class="text-sm text-gray-500 mt-1">{{ $tenant->tenant_type == 'individual' ? 'مستأجر (فرد)' : 'مستأجر (شركة)' }}</p>
                    </div>
                    <hr class="my-6 border-t border-gray-200">
                    <div class="space-y-4 text-sm">
                        @if($tenant->phone)
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span class="text-gray-700">{{ $tenant->phone }}</span>
                        </div>
                        @endif
                        @if($tenant->alternate_phone)
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span class="text-gray-700">{{ $tenant->alternate_phone }} (بديل)</span>
                        </div>
                        @endif
                        @if($tenant->email)
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <span class="text-gray-700">{{ $tenant->email }}</span>
                        </div>
                        @endif
                        @if($tenant->whatsapp)
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18.5a6.5 6.5 0 006.5-6.5c0-3.59-2.91-6.5-6.5-6.5S5.5 8.41 5.5 12a6.5 6.5 0 006.5 6.5z"></path></svg>
                            <span class="text-gray-700">{{ $tenant->whatsapp }} (واتساب)</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Details -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Personal Information Card -->
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-800 border-b border-gray-200 pb-4 mb-4">المعلومات الشخصية</h3>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                        @php
                            $idTypes = ['national_card' => 'بطاقة وطنية', 'passport' => 'جواز سفر', 'residence' => 'إقامة'];
                        @endphp
                        <div class="col-span-1">
                            <dt class="font-medium text-gray-500">نوع الهوية</dt>
                            <dd class="mt-1 text-gray-900">{{ $idTypes[$tenant->id_type] ?? 'غير محدد' }}</dd>
                        </div>
                        <div class="col-span-1">
                            <dt class="font-medium text-gray-500">رقم الهوية</dt>
                            <dd class="mt-1 text-gray-900">{{ $tenant->id_number ?? '-' }}</dd>
                        </div>
                        <div class="col-span-1">
                            <dt class="font-medium text-gray-500">تاريخ انتهاء الهوية</dt>
                            <dd class="mt-1 text-gray-900">{{ $tenant->id_expiry_date ? \Carbon\Carbon::parse($tenant->id_expiry_date)->format('Y-m-d') : '-' }}</dd>
                        </div>
                        <div class="col-span-1">
                            <dt class="font-medium text-gray-500">التحقق من الهوية</dt>
                            <dd class="mt-1">
                                @if($tenant->id_verified)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">تم التحقق</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">لم يتم التحقق</span>
                                @endif
                            </dd>
                        </div>
                        <div class="col-span-1">
                            <dt class="font-medium text-gray-500">جهة العمل</dt>
                            <dd class="mt-1 text-gray-900">{{ $tenant->employer ?? '-' }}</dd>
                        </div>
                        <div class="col-span-1">
                            <dt class="font-medium text-gray-500">الدخل الشهري</dt>
                            <dd class="mt-1 text-gray-900">{{ $tenant->monthly_income ? number_format($tenant->monthly_income, 2) . ' ريال' : '-' }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Address Card -->
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-800 border-b border-gray-200 pb-4 mb-4">معلومات العنوان</h3>
                    <dl class="grid grid-cols-1 gap-x-6 gap-y-4 text-sm">
                        <div class="col-span-1">
                            <dt class="font-medium text-gray-500">العنوان</dt>
                            <dd class="mt-1 text-gray-900">{{ $tenant->address ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Notes Card -->
                @if($tenant->notes)
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-800 border-b border-gray-200 pb-4 mb-4">ملاحظات</h3>
                    <div class="text-sm text-gray-700 leading-relaxed prose max-w-none">
                        <p>{{ $tenant->notes }}</p>
                    </div>
                </div>
                @endif
            </div>

        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden items-center justify-center" dir="ltr">
    <img class="max-w-full max-h-full rounded-lg shadow-xl" id="modalImage">
    <button id="closeModal" class="absolute top-4 right-4 text-white text-3xl font-bold">&times;</button>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageToOpenModal = document.getElementById('imageToOpenModal');
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        const closeModal = document.getElementById('closeModal');

        if (imageToOpenModal) {
            imageToOpenModal.addEventListener('click', function() {
                const imageUrl = this.querySelector('img')?.src;
                if (imageUrl && modal && modalImage) {
                    modalImage.src = imageUrl;
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                }
            });
        }

        const closeTheModal = () => {
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        };

        if (closeModal) {
            closeModal.addEventListener('click', closeTheModal);
        }

        if (modal) {
            modal.addEventListener('click', function(event) {
                if (event.target === modal) {
                    closeTheModal();
                }
            });
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeTheModal();
            }
        });
    });
</script>
@endpush
@endsection
