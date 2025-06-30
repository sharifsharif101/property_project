@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-semibold">قائمة المستأجرين</h1>
        <a href="{{ route('tenants.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            إضافة مستأجر جديد
        </a>
    </div>

    <!-- إضافة رسائل النجاح -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border rounded shadow">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-right">الاسم</th>
                    <th class="px-4 py-2 text-right">الهاتف</th>
                    <th class="px-4 py-2 text-right">البريد</th>
                    <th class="px-4 py-2 text-right">نوع الهوية</th>
                    <th class="px-4 py-2 text-right">الدخل</th>
                    <th class="px-4 py-2 text-right">الحالة</th>
                    <th class="px-4 py-2 text-right">صورة الهوية</th>
                    <th class="px-4 py-2 text-right">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tenants as $tenant)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-2 text-right">
                        {{ $tenant->first_name }} 
                        {{ $tenant->father_name ? $tenant->father_name . ' ' : '' }}
                        {{ $tenant->last_name }}
                    </td>
                    <td class="px-4 py-2 text-right">{{ $tenant->phone }}</td>
                    <td class="px-4 py-2 text-right">{{ $tenant->email ?? '-' }}</td>
                    
                    @php
                        $idTypes = [
                            'national_card' => 'بطاقة وطنية',
                            'passport' => 'جواز سفر',
                            'residence' => 'إقامة',
                        ];
                    @endphp
                    <td class="px-4 py-2 text-right">
                        {{ $idTypes[$tenant->id_type] ?? 'غير معروف' }}
                    </td>
                    
                    <td class="px-4 py-2 text-right">
                        {{ $tenant->monthly_income ? number_format($tenant->monthly_income, 2) . ' ريال' : '-' }}
                    </td>
                    
                    <td class="px-4 py-2 text-right">
                        @if($tenant->status == 'active')
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-semibold">نشط</span>
                        @elseif($tenant->status == 'suspended')
                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs font-semibold">موقوف</span>
                        @else
                            <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-semibold">منتهي</span>
                        @endif
                    </td>
                    
                    <td class="px-4 py-2 text-right">
                        @if($tenant->hasMedia('tenant_images'))
                            <img src="{{ $tenant->getFirstMediaUrl('tenant_images', 'thumb') }}" 
                                 alt="هوية المستأجر" 
                                 class="w-16 h-16 object-cover rounded border cursor-pointer hover:opacity-75"
                                 onclick="showImageModal('{{ $tenant->getFirstMediaUrl('tenant_images') }}')">
                        @else
                            <span class="text-gray-400 text-sm">لا توجد صورة</span>
                        @endif
                    </td>
                    
                    <td class="px-4 py-2 text-right">
                        <div class="flex space-x-2 space-x-reverse">
                            <a href="" 
                               class="text-blue-600 hover:text-blue-800 text-sm">عرض</a>
                            <a  
                               class="text-green-600 hover:text-green-800 text-sm">تعديل</a>
                            <form  
                                  method="POST" 
                                  class="inline"
                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا المستأجر؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm">حذف</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            لا يوجد مستأجرين حاليًا
                            <a href="{{ route('tenants.create') }}" class="text-blue-600 hover:underline mt-2">إضافة المستأجر الأول</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($tenants->hasPages())
        <div class="mt-6">
            {{ $tenants->links() }}
        </div>
    @endif
</div>

<!-- Modal لعرض الصورة بحجم كامل -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50" onclick="closeImageModal()">
    <div class="max-w-3xl max-h-3xl p-4">
        <img id="modalImage" src="" alt="صورة الهوية" class="max-w-full max-h-full rounded">
        <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white bg-red-500 rounded-full w-8 h-8 flex items-center justify-center">×</button>
    </div>
</div>

<script>
function showImageModal(imageUrl) {
    document.getElementById('modalImage').src = imageUrl;
    document.getElementById('imageModal').classList.remove('hidden');
    document.getElementById('imageModal').classList.add('flex');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.getElementById('imageModal').classList.remove('flex');
}
</script>
@endsection