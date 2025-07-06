
@extends('layouts.app')
 
@php
    use Carbon\Carbon;
@endphp

@section('content')
<div class="max-w-3xl mx-auto py-12 px-6 bg-white shadow-md rounded-3xl">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-10">تعديل العقد</h2>

    <form action="{{ route('contracts.update', $contract) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- المستأجر (للقراءة فقط) --}}
        <div>
            <label class="block mb-2 font-medium text-gray-700">المستأجر</label>
            <input type="text" value="{{ $contract->tenant->first_name }} {{ $contract->tenant->last_name }}" readonly
                   class="w-full p-3 border border-gray-200 bg-gray-100 rounded-xl text-gray-700">
            <input type="hidden" name="tenant_id" value="{{ $contract->tenant_id }}">
        </div>

        {{-- العقار (للقراءة فقط) --}}
        <div>
            <label class="block mb-2 font-medium text-gray-700">العقار</label>
            <input type="text" value="{{ $contract->property->name }}" readonly
                   class="w-full p-3 border border-gray-200 bg-gray-100 rounded-xl text-gray-700">
            <input type="hidden" name="property_id" value="{{ $contract->property_id }}">
        </div>

        {{-- الوحدة (للقراءة فقط) --}}
        <div>
            <label class="block mb-2 font-medium text-gray-700">الوحدة</label>
            <input type="text" value="{{ $contract->unit->unit_number }}" readonly
                   class="w-full p-3 border border-gray-200 bg-gray-100 rounded-xl text-gray-700">
            <input type="hidden" name="unit_id" value="{{ $contract->unit_id }}">
        </div>

        {{-- التواريخ --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="start_date" class="block mb-2 font-medium text-gray-700">تاريخ البداية</label>
                <input type="date" id="start_date" name="start_date"
                       value="{{ $contract->start_date ? Carbon::parse($contract->start_date)->format('Y-m-d') : '' }}"
                       class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:outline-none">
                @error('start_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="end_date" class="block mb-2 font-medium text-gray-700">تاريخ النهاية</label>
                <input type="date" id="end_date" name="end_date"
                       value="{{ $contract->end_date ? Carbon::parse($contract->end_date)->format('Y-m-d') : '' }}"
                       class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:outline-none">
                @error('end_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- الحالة --}}
        <div>
            <label for="status" class="block mb-2 font-medium text-gray-700">الحالة</label>
            <select name="status" id="status" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:outline-none">
                @php
                    $statuses = [
                        'active' => 'نشط',
                        'terminated' => 'منتهي',
                        'cancelled' => 'ملغي',
                        'draft' => 'مسودة',
                    ];
                @endphp
                @foreach($statuses as $key => $label)
                    <option value="{{ $key }}" {{ $contract->status == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('status')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- رقم المرجع --}}
        <div>
            <label for="reference_number" class="block mb-2 font-medium text-gray-700">رقم المرجع</label>
            <input type="text" name="reference_number" id="reference_number"
                   value="{{ $contract->reference_number }}"
                   class="w-full p-3 border border-gray-300 rounded-xl bg-gray-100" readonly>
        </div>

        {{-- قيمة الإيجار --}}
        <div>
            <label for="rent_amount" class="block mb-2 font-medium text-gray-700">قيمة الإيجار</label>
            <input type="number" name="rent_amount" id="rent_amount"
                   value="{{ $contract->rent_amount }}"
                   class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:outline-none"
                   step="0.01" min="0.01">
            @error('rent_amount')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- نوع الإيجار --}}
        <div>
            <label for="rent_type" class="block mb-2 font-medium text-gray-700">نوع الإيجار</label>
            <select name="rent_type" id="rent_type" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:outline-none">
                @php
                    $rentTypes = ['daily' => 'يومي', 'weekly' => 'أسبوعي', 'monthly' => 'شهري', 'yearly' => 'سنوي'];
                @endphp
                @foreach($rentTypes as $key => $label)
                    <option value="{{ $key }}" {{ $contract->rent_type == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('rent_type')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- التأمين --}}
        <div>
            <label for="security_deposit" class="block mb-2 font-medium text-gray-700">التأمين</label>
            <input type="number" name="security_deposit" id="security_deposit"
                   value="{{ $contract->security_deposit ?? '' }}"
                   class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:outline-none"
                   step="0.01" min="0">
            @error('security_deposit')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- سبب الإنهاء --}}
        <div>
            <label for="termination_reason" class="block mb-2 font-medium text-gray-700">سبب الإنهاء</label>
            <select name="termination_reason" id="termination_reason" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:outline-none">
                <option value="" {{ is_null($contract->termination_reason) ? 'selected' : '' }}>لا يوجد</option>
                @php
                    $terminationReasons = [
                        'late_payment' => 'التأخر في الدفع',
                        'property_damage' => 'إتلاف العقار',
                        'tenant_request' => 'طلب المستأجر',
                        'landlord_request' => 'طلب المالك',
                        'contract_expiry' => 'انتهاء العقد',
                        'other' => 'أخرى',
                    ];
                @endphp
                @foreach($terminationReasons as $key => $label)
                    <option value="{{ $key }}" {{ $contract->termination_reason == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        {{-- ملاحظات الإنهاء --}}
        <div>
            <label for="termination_notes" class="block mb-2 font-medium text-gray-700">ملاحظات الإنهاء</label>
            <textarea id="termination_notes" name="termination_notes"
                      class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:outline-none"
                      rows="4">{{ $contract->termination_notes ?? '' }}</textarea>
            @error('termination_notes')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- زر الحفظ --}}
        <div class="flex justify-center gap-4 pt-6">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-xl hover:bg-blue-700 transition duration-200 shadow-md">
                حفظ التغييرات
            </button>
            <a href="{{ route('contracts.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-xl hover:bg-gray-600 transition duration-200 shadow-md">
                رجوع
            </a>
        </div>
    </form>
</div>
@endsection