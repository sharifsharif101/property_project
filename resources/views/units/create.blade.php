@extends('layouts.app')

@section('title', 'إنشاء وحدة جديدة')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">إنشاء وحدة جديدة</h2>

    <!-- رسائل النجاح والأخطاء -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 text-center">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 text-center">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
 <form action="{{ route('units.store') }}" method="POST" class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg p-8" x-data="unitsForm()">
    @csrf

    <!-- اختيار العقار -->
    <div class="mb-6">
        <label for="property_id" class="block mb-2 font-semibold text-gray-700">العقار</label>
        <select name="property_id" id="property_id" required
            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 shadow-sm">
            <option value="">اختر عقاراً</option>
            @foreach($properties as $property)
                <option value="{{ $property->property_id }}" {{ old('property_id') == $property->property_id ? 'selected' : '' }}>
                    {{ $property->name ?? 'عقار #' . $property->property_id }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- وحدات -->
    <template x-for="(unit, index) in units" :key="index" class="mb-6 p-4 border rounded-lg shadow-sm">
        <div>
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-lg">وحدة رقم <span x-text="index + 1"></span></h3>
         <button type="button" @click="removeUnit(index)" 
    x-show="units.length > 1"
    style="background-color: #ADD8E6; color: rgb(48, 43, 43); padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-weight: 600;">
    حذف الوحدة
</button>
            </div>  

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block mb-1 font-semibold text-gray-700">رقم الوحدة</label>
                    <input type="text" :name="`units[${index}][unit_number]`" required maxlength="20"
                        class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100" />
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-gray-700">عدد غرف النوم</label>
                    <input type="number" :name="`units[${index}][bedrooms]`" min="0" max="255" required
                        class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100" />
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-gray-700">عدد الحمامات</label>
                    <input type="number" :name="`units[${index}][bathrooms]`" min="0" max="255" required
                        class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100" />
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-gray-700">المساحة (م²)</label>
                    <input type="number" :name="`units[${index}][area]`" step="0.001" required
                        class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100" />
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-gray-700">رقم الطابق</label>
                    <input type="number" :name="`units[${index}][floor_number]`" min="0" max="65535" required
                        class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100" />
                </div>

                <!-- داخل حقل حالة الوحدة -->
<div>
    <label class="block mb-1 font-semibold text-gray-700">حالة الوحدة</label>
    <select :name="`units[${index}][status]`" required
        class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
        <option value="">اختر الحالة</option>
        <option value="available">متاحة</option>
        <option value="reserved">محجوزة</option>
        <option value="rented">مؤجرة</option>
        <option value="under_maintenance">قيد الصيانة</option>
        <option value="disabled">معطلة</option>
    </select>
</div>

            </div>
        </div>
    </template>

    <div class="mb-6">
 <button type="button" @click="addUnit()" 
  class="px-6 py-3 bg-red-600 text-black rounded-lg hover:bg-red-700 transition">
  إضافة وحدة جديدة
</button>
    </div>

    <div class="text-center">
        <button type="submit"
            class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 font-medium rounded-lg text-base px-8 py-3 transition shadow-lg">
            حفظ
        </button>
    </div>
</form>

<script>
function unitsForm() {
    return {
        units: [
            { unit_number: '', bedrooms: '', bathrooms: '', area: '', floor_number: '', status: '' }
        ],
        addUnit() {
            this.units.push({ unit_number: '', bedrooms: '', bathrooms: '', area: '', floor_number: '', status: '' });
        },
        removeUnit(index) {
            if (this.units.length > 1) {
                this.units.splice(index, 1);
            }
        }
    }
}
</script>

</div>
@endsection