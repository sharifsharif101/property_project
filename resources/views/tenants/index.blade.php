@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    <h1 class="text-2xl font-semibold mb-4">قائمة المستأجرين</h1>

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
            <tr class="border-b">
                <td class="px-4 py-2 text-right">
                    {{ $tenant->first_name }} {{ $tenant->father_name ? $tenant->father_name . ' ' : '' }}{{ $tenant->last_name }}
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
    الهوية: {{ $idTypes[$tenant->id_type] ?? 'غير معروف' }}
</td>
                <td class="px-4 py-2 text-right">
                    {{ $tenant->monthly_income ? number_format($tenant->monthly_income, 2) . ' ريال' : '-' }}
                </td>
                <td class="px-4 py-2 text-right">
                    @if($tenant->status == 'active')
                        <span class="text-green-600 font-semibold">نشط</span>
                    @elseif($tenant->status == 'suspended')
                        <span class="text-yellow-600 font-semibold">موقوف</span>
                    @else
                        <span class="text-red-600 font-semibold">منتهي</span>
                    @endif
                </td>
<td class="px-4 py-2 text-right">
    @if($tenant->hasMedia('tenant_images'))
        <img src="{{ $tenant->getFirstMediaUrl('tenant_image', 'thumb') }}" alt="هوية" class="w-16 h-16 object-cover rounded">
        
    @else
        <span class="text-gray-400">لا توجد</span>
    @endif
</td>
                <td class="px-4 py-2 text-right">
                    <a href="#" class="text-blue-600 hover:underline">عرض</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-4 py-4 text-center">لا يوجد مستأجرين حاليًا.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $tenants->links() }}
    </div>
</div>
@endsection
