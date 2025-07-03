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
    <h2 class="text-2xl font-bold mb-6">إنشاء عقد جديد</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('contracts.store') }}" method="POST" class="space-y-4">
        @csrf

        {{-- المستأجر --}}
        <div>
            <label>المستأجر</label>
            <select name="tenant_id" class="w-full border p-2 rounded">
                <option value="">اختر مستأجر</option>
                @foreach($tenants as $tenant)
                    <option value="{{ $tenant->id }}">{{ $tenant->first_name }} {{ $tenant->last_name }}</option>
                @endforeach
            </select>
        </div>

        {{-- العقار --}}
        <div>
            <label>العقار</label>
            <select name="property_id" id="property-select" class="w-full border p-2 rounded">
                <option value="">اختر العقار</option>
                @foreach($properties as $property)
                    <option value="{{ $property->property_id }}">{{ $property->name ?? 'عقار #' . $property->property_id }}</option>
                @endforeach
            </select>
        </div>

        {{-- الوحدة --}}
        <div>
            <label>الوحدة</label>
            <select name="unit_id" id="unit-select" class="w-full border p-2 rounded">
                <option value="">اختر وحدة</option>
            </select>
        </div>

        {{-- التواريخ --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label>تاريخ البداية</label>
                <input type="date" name="start_date" class="w-full border p-2 rounded">
            </div>
            <div>
                <label>تاريخ النهاية</label>
                <input type="date" name="end_date" class="w-full border p-2 rounded">
            </div>
        </div>

        {{-- الإيجار --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label>قيمة الإيجار</label>
                <input type="number" name="rent_amount" step="0.01" class="w-full border p-2 rounded">
            </div>
            <div>
                <label>نوع الإيجار</label>
                <select name="rent_type" class="w-full border p-2 rounded">
                    <option value="daily">يومي</option>
                    <option value="weekly">أسبوعي</option>
                    <option value="monthly" selected>شهري</option>
                    <option value="yearly">سنوي</option>
                </select>
            </div>
        </div>

        {{-- الضمان --}}
        <div>
            <label>الضمان</label>
            <input type="number" name="security_deposit" step="0.01" class="w-full border p-2 rounded">
        </div>

        {{-- رقم المرجع --}}
        <div>
            <label>رقم المرجع</label>
            <input type="text" value="{{ old('reference_number', $referenceNumber ?? 'سيتم توليده تلقائيًا') }}" disabled
                class="w-full border p-2 rounded bg-gray-100 cursor-not-allowed" />
            <input type="hidden" name="reference_number" value="{{ old('reference_number', $referenceNumber ?? '') }}" />
        </div>

        {{-- الحالة --}}
        <div>
            <label>الحالة</label>
            <select name="status" class="w-full border p-2 rounded">
                <option value="draft">مسودة</option>
                <option value="active">نشط</option>
                <option value="terminated">منتهي</option>
                <option value="cancelled">ملغي</option>
            </select>
        </div>

        {{-- سبب الإنهاء --}}
        <div>
            <label>سبب الإنهاء</label>
            <select name="termination_reason" class="w-full border p-2 rounded">
                <option value="">-- إن وجد --</option>
                <option value="late_payment">تأخر في الدفع</option>
                <option value="property_damage">تلف في العقار</option>
                <option value="tenant_request">طلب المستأجر</option>
                <option value="landlord_request">طلب المالك</option>
                <option value="contract_expiry">انتهاء العقد</option>
                <option value="other">أخرى</option>
            </select>
        </div>

        {{-- ملاحظات الإنهاء --}}
        <div>
            <label>ملاحظات الإنهاء</label>
            <textarea name="termination_notes" rows="3" class="w-full border p-2 rounded"></textarea>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">حفظ العقد</button>
    </form>
</div>

{{-- 🧠 سكربت جلب الوحدات ديناميكياً --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const propertySelect = document.getElementById('property-select');
    const unitSelect = document.getElementById('unit-select');

    propertySelect.addEventListener('change', function () {
        const propertyId = this.value;

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
    });
});
</script>

@endsection
