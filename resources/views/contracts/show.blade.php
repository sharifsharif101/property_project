@extends('layouts.app')

@section('content')
@php
    // الترجمات اليدوية بدون ملف lang
    $rentTypeTranslations = [
        'daily' => 'يومي',
        'weekly' => 'أسبوعي',
        'monthly' => 'شهري',
        'yearly' => 'سنوي',
    ];

    $statusTranslations = [
        'draft' => 'مسودة',
        'active' => 'نشط',
        'terminated' => 'منتهي',
        'cancelled' => 'ملغي',
    ];

    $terminationReasons = [
        'late_payment' => 'تأخر في الدفع',
        'property_damage' => 'تلف الممتلكات',
        'tenant_request' => 'بناء على طلب المستأجر',
        'landlord_request' => 'بناء على طلب المؤجر',
        'contract_expiry' => 'انتهاء العقد',
        'other' => 'أخرى',
    ];

    $statusColor = match($contract->status) {
        'active' => 'text-green-600',
        'draft' => 'text-gray-500',
        'terminated' => 'text-red-600',
        'cancelled' => 'text-yellow-600',
        default => 'text-gray-800'
    };
@endphp

<div class="max-w-6xl mx-auto mt-12 p-8 bg-white rounded-2xl shadow-2xl">
    <h1 class="text-4xl font-extrabold text-gray-800 mb-10 flex items-center gap-3">
        📄 تفاصيل العقد
 
    </h1>
<div class="mb-10">
    <span class="inline-block bg-blue-100 text-blue-800 text-2xl font-bold px-6 py-3 rounded-xl shadow-sm tracking-wide">
        {{ $contract->reference_number }}
    </span>
</div>
    {{-- تفاصيل العقد --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-800 text-[17px]">
  <a href="{{ route('tenants.show', $contract->tenant->id) }}"
   class="block bg-gray-50 p-4 rounded-lg shadow-sm hover:bg-gray-100 transition-colors duration-200">
    <div class="text-sm text-gray-500 mb-1">المستأجر</div>
    <div class="font-semibold">
        {{ $contract->tenant->full_name ?? $contract->tenant->first_name . ' ' . $contract->tenant->last_name }}
    </div>
</a>

    <a href="{{ route('properties.show', $contract->property->property_id) }}"
   class="block bg-gray-50 p-4 rounded-lg shadow-sm hover:bg-gray-100 transition-colors duration-200">
    <div class="text-sm text-gray-500 mb-1">العقار</div>
    <div class="font-semibold">
        {{ $contract->property->name ?? '—' }}
    </div>
</a>

        <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
            <div class="text-sm text-gray-500 mb-1">الوحدة</div>
            <div class="font-semibold">{{ $contract->unit->unit_number ?? '—' }}</div>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
            <div class="text-sm text-gray-500 mb-1">نوع الإيجار</div>
            <div class="font-semibold">
                {{ $rentTypeTranslations[$contract->rent_type] ?? $contract->rent_type }}
            </div>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
            <div class="text-sm text-gray-500 mb-1">تاريخ البداية</div>
            <div class="font-semibold">{{ \Carbon\Carbon::parse($contract->start_date)->format('Y-m-d') }}</div>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
            <div class="text-sm text-gray-500 mb-1">تاريخ الانتهاء</div>
            <div class="font-semibold">{{ \Carbon\Carbon::parse($contract->end_date)->format('Y-m-d') }}</div>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
            <div class="text-sm text-gray-500 mb-1">مبلغ الإيجار</div>
            <div class="font-semibold text-green-600">
                {{ number_format($contract->rent_amount, 2) }} ريال
            </div>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
            <div class="text-sm text-gray-500 mb-1">قيمة التأمين</div>
            <div class="font-semibold">{{ number_format($contract->security_deposit, 2) }} ريال</div>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg shadow-sm col-span-1 md:col-span-2">
            <div class="text-sm text-gray-500 mb-1">الحالة</div>
            <div class="font-bold {{ $statusColor }}">
                {{ $statusTranslations[$contract->status] ?? $contract->status }}
            </div>
        </div>
    </div>

    {{-- سبب الإنهاء --}}
    @if(in_array($contract->status, ['terminated', 'cancelled']))
        <div class="mt-10 bg-red-50 border border-red-200 p-6 rounded-lg text-red-800">
            <h2 class="text-xl font-semibold mb-3">📌 سبب الإنهاء</h2>
            <p><strong>السبب:</strong> {{ $terminationReasons[$contract->termination_reason] ?? 'غير معروف' }}</p>

            @if($contract->termination_notes)
                <p class="mt-2"><strong>ملاحظات:</strong> {{ $contract->termination_notes }}</p>
            @endif
        </div>
    @endif

    {{-- أزرار التحكم --}}
    <div class="mt-10 flex gap-4">
        <a href="{{ route('contracts.index') }}"
           class="inline-flex items-center px-5 py-2.5 bg-gray-800 hover:bg-gray-900 text-white rounded-lg text-lg shadow-md">
            ⬅️ الرجوع إلى القائمة
        </a>

        <a href="{{ route('contracts.edit', $contract->id) }}"
           class="inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-lg shadow-md">
            ✏️ تعديل
        </a>

        <form action="{{ route('contracts.destroy', $contract->id) }}" method="POST"
              onsubmit="return confirm('هل أنت متأكد أنك تريد حذف هذا العقد؟');">
            @csrf
            @method('DELETE')
    <button type="submit"
        class="inline-flex items-center px-5 py-2.5 bg-red-600 hover:bg-red-700 text-black rounded-lg text-lg shadow-md">
    🗑 حذف
</button>
        </form>
    </div>
</div>
@endsection
