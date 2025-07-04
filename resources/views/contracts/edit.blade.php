@extends('layouts.app')

@section('content')

{{-- ✅ عرض أخطاء الفاليدشن --}}
@if ($errors->any())
    <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
        <strong>حدثت بعض الأخطاء:</strong>
        <ul class="mt-2 list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-6">تعديل العقد</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('contracts.update', $contract->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        {{-- المستأجر --}}
        <div>
            <label>المستأجر</label>
            <select name="tenant_id" class="w-full border p-2 rounded" required>
                <option value="">اختر مستأجر</option>
                @foreach($tenants as $tenant)
                    <option value="{{ $tenant->id }}" 
                        {{ old('tenant_id', $contract->tenant_id) == $tenant->id ? 'selected' : '' }}>
                        {{ $tenant->first_name }} {{ $tenant->last_name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- العقار --}}
        <div>
            <label>العقار</label>
            <select name="property_id" id="property-select" class="w-full border p-2 rounded" required>
                <option value="">اختر العقار</option>
                @foreach($properties as $property)
                    <option value="{{ $property->property_id }}"
                        {{ old('property_id', $contract->property_id) == $property->property_id ? 'selected' : '' }}>
                        {{ $property->name ?? 'عقار #' . $property->property_id }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- الوحدة --}}
        <div>
            <label>الوحدة</label>
            <select name="unit_id" id="unit-select" class="w-full border p-2 rounded" required>
                <option value="">اختر وحدة</option>
                {{-- سيتم ملؤها بالوحدات المناسبة بواسطة جافاسكريبت --}}
            </select>
        </div>

        {{-- التواريخ --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label>تاريخ البداية</label>
                <input type="date" name="start_date" class="w-full border p-2 rounded" 
                    value="{{ old('start_date', $contract->start_date->format('Y-m-d')) }}" required>
            </div>
            <div>
                <label>تاريخ النهاية</label>
                <input type="date" name="end_date" class="w-full border p-2 rounded" 
                    value="{{ old('end_date', $contract->end_date ? $contract->end_date->format('Y-m-d') : '') }}">
            </div>
        </div>

        {{-- الإيجار --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label>قيمة الإيجار</label>
                <input type="number" name="rent_amount" step="0.01" class="w-full border p-2 rounded" 
                    value="{{ old('rent_amount', $contract->rent_amount) }}" required>
            </div>
            <div>
                <label>نوع الإيجار</label>
                <select name="rent_type" class="w-full border p-2 rounded" required>
                    <option value="daily" {{ old('rent_type', $contract->rent_type) == 'daily' ? 'selected' : '' }}>يومي</option>
                    <option value="weekly" {{ old('rent_type', $contract->rent_type) == 'weekly' ? 'selected' : '' }}>أسبوعي</option>
                    <option value="monthly" {{ old('rent_type', $contract->rent_type) == 'monthly' ? 'selected' : '' }}>شهري</option>
                    <option value="yearly" {{ old('rent_type', $contract->rent_type) == 'yearly' ? 'selected' : '' }}>سنوي</option>
                </select>
            </div>
        </div>

        {{-- الضمان --}}
        <div>
            <label>الضمان</label>
            <input type="number" name="security_deposit" step="0.01" class="w-full border p-2 rounded"
                value="{{ old('security_deposit', $contract->security_deposit) }}">
        </div>

        {{-- رقم المرجع --}}
        <div>
            <label>رقم المرجع</label>
            <input type="text" value="{{ old('reference_number', $contract->reference_number) }}" disabled
                class="w-full border p-2 rounded bg-gray-100 cursor-not-allowed" />
            <input type="hidden" name="reference_number" value="{{ old('reference_number', $contract->reference_number) }}" />
        </div>

        {{-- الحالة --}}
        <div>
            <label>الحالة</label>
            <select name="status" class="w-full border p-2 rounded" required>
                <option value="draft" {{ old('status', $contract->status) == 'draft' ? 'selected' : '' }}>مسودة</option>
                <option value="active" {{ old('status', $contract->status) == 'active' ? 'selected' : '' }}>نشط</option>
                <option value="terminated" {{ old('status', $contract->status) == 'terminated' ? 'selected' : '' }}>منتهي</option>
                <option value="cancelled" {{ old('status', $contract->status) == 'cancelled' ? 'selected' : '' }}>ملغي</option>
            </select>
        </div>

        {{-- سبب الإنهاء --}}
        <div>
            <label>سبب الإنهاء</label>
            <select name="termination_reason" class="w-full border p-2 rounded">
                <option value="" {{ old('termination_reason', $contract->termination_reason) == '' ? 'selected' : '' }}>-- إن وجد --</option>
                <option value="late_payment" {{ old('termination_reason', $contract->termination_reason) == 'late_payment' ? 'selected' : '' }}>تأخر في الدفع</option>
                <option value="property_damage" {{ old('termination_reason', $contract->termination_reason) == 'property_damage' ? 'selected' : '' }}>تلف في العقار</option>
                <option value="tenant_request" {{ old('termination_reason', $contract->termination_reason) == 'tenant_request' ? 'selected' : '' }}>طلب المستأجر</option>
                <option value="landlord_request" {{ old('termination_reason', $contract->termination_reason) == 'landlord_request' ? 'selected' : '' }}>طلب المالك</option>
                <option value="contract_expiry" {{ old('termination_reason', $contract->termination_reason) == 'contract_expiry' ? 'selected' : '' }}>انتهاء العقد</option>
                <option value="other" {{ old('termination_reason', $contract->termination_reason) == 'other' ? 'selected' : '' }}>أخرى</option>
            </select>
        </div>

        {{-- ملاحظات الإنهاء --}}
        <div>
            <label>ملاحظات الإنهاء</label>
            <textarea name="termination_notes" rows="3" class="w-full border p-2 rounded">{{ old('termination_notes', $contract->termination_notes) }}</textarea>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">تحديث العقد</button>
    </form>
</div>

{{-- 🧠 سكربت جلب الوحدات ديناميكياً مع اختيار الوحدة الحالية --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const propertySelect = document.getElementById('property-select');
    const unitSelect = document.getElementById('unit-select');

    // دالة لتحميل الوحدات حسب العقار
    function loadUnits(propertyId, selectedUnitId = null) {
        unitSelect.innerHTML = '<option value="">جاري التحميل...</option>';

        if (propertyId) {
            fetch(`/properties/${propertyId}/units`)
                .then(response => response.json())
                .then(units => {
                    if (units.length === 0) {
                        unitSelect.innerHTML = '<option value="">لا توجد وحدات متاحة لهذا العقار</option>';
                        return;
                    }

                    unitSelect.innerHTML = '<option value="">اختر وحدة</option>';
                    units.forEach(unit => {
                        const option = document.createElement('option');
                        option.value = unit.id;
                        option.textContent = `وحدة رقم ${unit.unit_number}`;

                        if (selectedUnitId && selectedUnitId == unit.id) {
                            option.selected = true;
                        }

                        unitSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    unitSelect.innerHTML = '<option value="">فشل تحميل الوحدات</option>';
                    console.error('خطأ أثناء تحميل الوحدات:', error);
                });
        } else {
            unitSelect.innerHTML = '<option value="">اختر وحدة</option>';
        }
    }

    // تحميل الوحدات عند تغيير العقار
    propertySelect.addEventListener('change', function () {
        loadUnits(this.value);
    });

    // تحميل الوحدات مع اختيار الوحدة الحالية عند تحميل الصفحة
    loadUnits(propertySelect.value, {{ old('unit_id', $contract->unit_id ?? 'null') }});
});
</script>

@endsection
