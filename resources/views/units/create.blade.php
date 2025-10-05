@extends('layouts.app')

@section('title', 'إنشاء وحدة جديدة')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Page Header --}}
    <header class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">إنشاء وحدات جديدة</h1>
        <p class="mt-2 text-md text-gray-600">أضف وحدات سكنية أو تجارية جديدة لأحد العقارات المسجلة لديك.</p>
    </header>

    {{-- Session Messages --}}
    @if(session('success'))
        <div class="bg-green-50 border-r-4 border-green-500 text-green-800 p-4 rounded-lg mb-6 shadow-sm" role="alert">
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <div>
                    <p class="font-bold">نجاح</p>
                    <p>{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif
    @if ($errors->any())
        <div class="bg-red-50 border-r-4 border-red-500 text-red-800 p-4 rounded-lg mb-6 shadow-sm" role="alert">
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                <div>
                    <p class="font-bold">حدث خطأ</p>
                    <ul class="list-disc list-inside mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- Main Form --}}
    <form action="{{ route('units.store') }}" method="POST" 
          class="bg-white rounded-2xl shadow-lg overflow-hidden" 
          x-data="unitsForm()" @submit="saving = true">
        @csrf
        
        <div class="p-6 sm:p-8 space-y-8">
            
            <!-- Step 1: Property Selection -->
            <div class="border-b border-gray-200 pb-8">
                <h3 class="text-xl font-bold text-gray-800 mb-2">الخطوة 1: اختيار العقار</h3>
                <p class="text-sm text-gray-500 mb-6">اختر العقار الذي ستنتمي إليه هذه الوحدات.</p>
                <div class="relative">
                    <label for="property_id" class="absolute -top-2.5 left-3 inline-block bg-white px-1 text-sm font-medium text-gray-600">العقار</label>
                    <select name="property_id" id="property_id" required
                            class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        <option value="" disabled selected>-- اختر عقاراً --</option>
                        @foreach($properties as $property)
                            <option value="{{ $property->property_id }}" {{ old('property_id') == $property->property_id ? 'selected' : '' }}>
                                {{ $property->name ?? 'عقار #' . $property->property_id }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Step 2: Units Details -->
            <div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">الخطوة 2: إضافة الوحدات</h3>
                <p class="text-sm text-gray-500 mb-6">يمكنك إضافة وحدة واحدة أو أكثر في المرة الواحدة.</p>
                
                <div class="space-y-6">
                    <template x-for="(unit, index) in units" :key="index">
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 space-y-6 transition-all duration-300 ease-in-out">
                            <div class="flex justify-between items-center border-b border-gray-200 pb-4 mb-2">
                                <h4 class="font-bold text-lg text-gray-700">وحدة رقم <span x-text="index + 1"></span></h4>
                                <button type="button" @click="removeUnit(index)" x-show="units.length > 1"
                                        class="flex items-center gap-1.5 bg-red-100 text-red-700 hover:bg-red-200 font-semibold text-xs px-3 py-1.5 rounded-full transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                    <span>حذف</span>
                                </button>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-8">
                                {{-- Material Inspired Input --}}
                                <div class="relative">
                                    <label :for="'unit_number_'+index" class="absolute -top-2.5 left-3 inline-block bg-gray-50 px-1 text-sm font-medium text-gray-600">رقم الوحدة</label>
                                    <input type="text" :id="'unit_number_'+index" :name="`units[${index}][unit_number]`" required maxlength="20" class="w-full bg-transparent border border-gray-300 rounded-lg px-4 py-3 text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" />
                                </div>
                                <div class="relative">
                                    <label :for="'bedrooms_'+index" class="absolute -top-2.5 left-3 inline-block bg-gray-50 px-1 text-sm font-medium text-gray-600">غرف النوم</label>
                                    <input type="number" :id="'bedrooms_'+index" :name="`units[${index}][bedrooms]`" min="0" max="255" required class="w-full bg-transparent border border-gray-300 rounded-lg px-4 py-3 text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" />
                                </div>
                                <div class="relative">
                                    <label :for="'bathrooms_'+index" class="absolute -top-2.5 left-3 inline-block bg-gray-50 px-1 text-sm font-medium text-gray-600">الحمامات</label>
                                    <input type="number" :id="'bathrooms_'+index" :name="`units[${index}][bathrooms]`" min="0" max="255" required class="w-full bg-transparent border border-gray-300 rounded-lg px-4 py-3 text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" />
                                </div>
                                <div class="relative">
                                    <label :for="'area_'+index" class="absolute -top-2.5 left-3 inline-block bg-gray-50 px-1 text-sm font-medium text-gray-600">المساحة (م²)</label>
                                    <input type="number" :id="'area_'+index" :name="`units[${index}][area]`" step="0.01" required class="w-full bg-transparent border border-gray-300 rounded-lg px-4 py-3 text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" />
                                </div>
                                <div class="relative">
                                    <label :for="'floor_number_'+index" class="absolute -top-2.5 left-3 inline-block bg-gray-50 px-1 text-sm font-medium text-gray-600">رقم الطابق</label>
                                    <input type="number" :id="'floor_number_'+index" :name="`units[${index}][floor_number]`" min="0" max="255" required class="w-full bg-transparent border border-gray-300 rounded-lg px-4 py-3 text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" />
                                </div>
                                <div class="relative">
                                    <label :for="'status_'+index" class="absolute -top-2.5 left-3 inline-block bg-gray-50 px-1 text-sm font-medium text-gray-600">حالة الوحدة</label>
                                    <select :id="'status_'+index" :name="`units[${index}][status]`" required class="w-full bg-transparent border border-gray-300 rounded-lg px-4 py-3 text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                        <option value="" disabled selected>-- اختر الحالة --</option>
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
                <button type="button" @click="addUnit()" 
                        class="inline-flex items-center gap-2 bg-indigo-100 text-indigo-800 font-semibold py-2.5 px-5 rounded-lg hover:bg-indigo-200 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                    <span>إضافة وحدة أخرى</span>
                </button>
            </div>
        </div>

        <!-- Form Footer -->
        <div class="bg-gray-50 px-6 sm:px-8 py-4 border-t border-gray-200 flex justify-end">
            <button type="submit" :disabled="saving"
                    class="inline-flex items-center justify-center gap-2 bg-indigo-600 text-white font-bold py-3 px-8 rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 disabled:bg-indigo-400 disabled:cursor-not-allowed">
                <span x-show="!saving">حفظ الوحدات</span>
                <span x-show="saving">جاري الحفظ...</span>
                <svg x-show="saving" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function unitsForm() {
    return {
        units: [
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
        saving: false,
        addUnit() {
            this.units.push({ unit_number: '', bedrooms: '', bathrooms: '', area: '', floor_number: '', status: '' });
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
