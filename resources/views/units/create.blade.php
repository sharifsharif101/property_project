@extends('layouts.app')

@section('title', 'إنشاء وحدة جديدة')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-800">إنشاء وحدات جديدة</h1>
        <p class="text-sm text-gray-500 mt-1">أضف وحدات سكنية أو تجارية إلى أحد العقارات الموجودة.</p>
    </div>

    {{-- Session Messages --}}
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md" role="alert">
            <p class="font-bold">نجاح</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif
    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md" role="alert">
            <p class="font-bold">خطأ</p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li class="list-disc ml-4">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Main Form with Loading State --}}
    <form action="{{ route('units.store') }}" method="POST" 
          class="bg-white shadow-lg rounded-xl overflow-hidden" 
          x-data="unitsForm()" @submit="saving = true">
        @csrf
        
        <div class="p-8 space-y-8">
            <!-- Step 1: Property Selection -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-1">الخطوة 1: اختيار العقار</h3>
                <p class="text-sm text-gray-500 mb-4">اختر العقار الذي ستنتمي إليه هذه الوحدات.</p>
                <select name="property_id" id="property_id" required
                        class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- اختر عقاراً --</option>
                    @foreach($properties as $property)
                        <option value="{{ $property->property_id }}" {{ old('property_id') == $property->property_id ? 'selected' : '' }}>
                            {{ $property->name ?? 'عقار #' . $property->property_id }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Step 2: Units Details -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-1">الخطوة 2: إضافة الوحدات</h3>
                <p class="text-sm text-gray-500 mb-4">يمكنك إضافة وحدة واحدة أو أكثر في المرة الواحدة.</p>
                
                <div class="space-y-6">
                    <template x-for="(unit, index) in units" :key="index">
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 space-y-4 transition-all">
                            <div class="flex justify-between items-center border-b border-gray-200 pb-3 mb-4">
                                <h4 class="font-bold text-gray-700">وحدة رقم <span x-text="index + 1"></span></h4>
                                <button type="button" @click="removeUnit(index)" x-show="units.length > 1"
                                        class="inline-flex items-center gap-1.5 bg-red-100 text-red-700 hover:bg-red-200 font-semibold text-xs px-3 py-1 rounded-full transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                    <span>حذف</span>
                                </button>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                                {{-- Input fields remain the same --}}
                                <div>
                                    <label :for="'unit_number_'+index" class="block mb-1 text-sm font-medium text-gray-700">رقم الوحدة</label>
                                    <input type="text" :id="'unit_number_'+index" :name="`units[${index}][unit_number]`" required maxlength="20" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                                <div>
                                    <label :for="'bedrooms_'+index" class="block mb-1 text-sm font-medium text-gray-700">عدد غرف النوم</label>
                                    <input type="number" :id="'bedrooms_'+index" :name="`units[${index}][bedrooms]`" min="0" max="255" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                                <div>
                                    <label :for="'bathrooms_'+index" class="block mb-1 text-sm font-medium text-gray-700">عدد الحمامات</label>
                                    <input type="number" :id="'bathrooms_'+index" :name="`units[${index}][bathrooms]`" min="0" max="255" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                                <div>
                                    <label :for="'area_'+index" class="block mb-1 text-sm font-medium text-gray-700">المساحة (م²)</label>
                                    <input type="number" :id="'area_'+index" :name="`units[${index}][area]`" step="0.01" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                                <div>
                                    <label :for="'floor_number_'+index" class="block mb-1 text-sm font-medium text-gray-700">رقم الطابق</label>
                                    <input type="number" :id="'floor_number_'+index" :name="`units[${index}][floor_number]`" min="0" max="255" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                                <div>
                                    <label :for="'status_'+index" class="block mb-1 text-sm font-medium text-gray-700">حالة الوحدة</label>
                                    <select :id="'status_'+index" :name="`units[${index}][status]`" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">-- اختر الحالة --</option>
                                        <option value="available">متاحة</option>
                                        <option value="rented">مؤجرة</option>
                                        <option value="under_maintenance">قيد الصيانة</option>
                                        <option value="reserved">محجوزة</option>
                                        <option value="disabled">غير متاحة</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Add Unit Button -->
            <div>
                <button type="button" @click="addUnit()" class="inline-flex items-center gap-2 bg-blue-100 text-blue-800 font-semibold py-2 px-4 rounded-lg hover:bg-blue-200 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                    <span>إضافة وحدة أخرى</span>
                </button>
            </div>
        </div>

        <!-- Form Footer -->
        <div class="bg-gray-50 px-8 py-4 border-t border-gray-200 flex justify-end">
            <button type="submit" :disabled="saving"
                    class="inline-flex items-center justify-center gap-2 bg-blue-600 text-white font-bold py-3 px-10 rounded-lg shadow-md hover:bg-blue-700 transition-colors disabled:bg-blue-400 disabled:cursor-not-allowed">
                <span x-show="!saving">حفظ</span>
                <span x-show="saving">جاري الحفظ...</span>
                <svg x-show="saving" class="animate-spin -mr-1 ml-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </button>
        </div>
    </form>
</div>
@endsection

{{-- ✅ THE FIX IS HERE: Move the script to the 'scripts' stack --}}
@push('scripts')
<script>
function unitsForm() {
    return {
        units: [
            // Populate with old data if validation fails
            @if(is_array(old('units')))
                @foreach(old('units') as $unit)
                    {
                        unit_number: '{{ $unit['unit_number'] ?? '' }}',
                        bedrooms: '{{ $unit['bedrooms'] ?? '' }}',
                        bathrooms: '{{ $unit['bathrooms'] ?? '' }}',
                        area: '{{ $unit['area'] ?? '' }}',
                        floor_number: '{{ $unit['floor_number'] ?? '' }}',
                        status: '{{ $unit['status'] ?? '' }}'
                    },
                @endforeach
            @else
                { unit_number: '', bedrooms: '', bathrooms: '', area: '', floor_number: '', status: '' }
            @endif
        ],
        saving: false, // For the loading state
        addUnit() {
            this.units.push({ unit_number: '', bedrooms: '', bathrooms: '', area: '', floor_number: '', status: '' });
            // Scroll to the new unit for better UX on long forms
            this.$nextTick(() => {
                const lastUnit = document.querySelector('.space-y-6 > div:last-child');
                if (lastUnit) {
                    lastUnit.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        },
        removeUnit(index) {
            if (this.units.length > 1) {
                this.units.splice(index, 1);
            }
        }
    }
}
</script>
@endpush