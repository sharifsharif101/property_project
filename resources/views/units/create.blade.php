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

    <form action="{{ route('units.store') }}" method="POST" class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg p-8">
        @csrf

        <!-- العقار المرتبط -->
        <div class="mb-6">
            <div class="flex items-center gap-2 mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
                <label for="property_id" class="block text-gray-700 font-semibold">العقار</label>
            </div>
            <select name="property_id" id="property_id"
                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition duration-300 shadow-sm"
                required>
                <option value="">اختر عقاراً</option>
                @foreach($properties as $property)
<option value="{{ $property->id }}" {{ old('property_id') == $property->property_id ? 'selected' : '' }}>
                        {{ $property->name ?? 'عقار #' . $property->id }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- قسم بـ 3 أعمدة -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- رقم الوحدة -->
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    <label for="unit_number" class="block text-gray-700 font-semibold">رقم الوحدة</label>
                </div>
                <input type="text" name="unit_number" id="unit_number" maxlength="20"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition duration-300 shadow-sm"
                    value="{{ old('unit_number') }}" required />
            </div>

            <!-- غرف النوم -->
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0l-2-2m2 2l2-2" />
                    </svg>
                    <label for="bedrooms" class="block text-gray-700 font-semibold">عدد غرف النوم</label>
                </div>
                <input type="number" name="bedrooms" id="bedrooms" min="0" max="255"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition duration-300 shadow-sm"
                    value="{{ old('bedrooms') }}" />
            </div>

            <!-- الحمامات -->
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <label for="bathrooms" class="block text-gray-700 font-semibold">عدد الحمامات</label>
                </div>
                <input type="number" name="bathrooms" id="bathrooms" min="0" max="255"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition duration-300 shadow-sm"
                    value="{{ old('bathrooms') }}" />
            </div>
        </div>

        <!-- قسم بـ 2 عمود -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- المساحة -->
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5" />
                    </svg>
                    <label for="area" class="block text-gray-700 font-semibold">المساحة (م²)</label>
                </div>
                <input type="number" step="0.001" name="area" id="area"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition duration-300 shadow-sm"
                    value="{{ old('area') }}" />
            </div>

            <!-- رقم الطابق -->
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm0 0l-3.182 1.768a12.083 12.083 0 01-.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 006.824-2.998 12.078 12.078 0 01-.665-6.479L12 14z" />
                    </svg>
                    <label for="floor_number" class="block text-gray-700 font-semibold">رقم الطابق</label>
                </div>
                <input type="number" name="floor_number" id="floor_number" min="0" max="65535"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition duration-300 shadow-sm"
                    value="{{ old('floor_number') }}" />
            </div>
        </div>

        <!-- حالة الوحدة -->
        <div class="mb-6">
            <div class="flex items-center gap-2 mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
                <label for="status" class="block text-gray-700 font-semibold">حالة الوحدة</label>
            </div>
            <select name="status" id="status"
                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition duration-300 shadow-sm"
                required>
                <option value="">اختر الحالة</option>
                <option value="vacant" {{ old('status') == 'vacant' ? 'selected' : '' }}>شاغرة</option>
                <option value="rented" {{ old('status') == 'rented' ? 'selected' : '' }}>مؤجرة</option>
                <option value="under_maintenance" {{ old('status') == 'under_maintenance' ? 'selected' : '' }}>تحت الصيانة</option>
                <option value="under_renovation" {{ old('status') == 'under_renovation' ? 'selected' : '' }}>تحت التجديد</option>
            </select>
        </div>

        <!-- زر الحفظ -->
        <div class="text-center">
            <button type="submit" style="color: rgb(255, 255, 255)"
                class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 font-medium rounded-lg text-base px-8 py-3 transition duration-300 shadow-lg">
                حفظ  
            </button>
        </div>
    </form>
</div>
@endsection