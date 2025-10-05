@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
   

    <!-- Success Message -->
    @if (session('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-90"
            class="flex items-center bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded-lg shadow-md mb-6" role="alert">
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div>
                <strong class="font-bold">نجاح!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Error Messages -->
    @if ($errors->any())
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-90"
            class="flex items-start bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded-lg shadow-md mb-6" role="alert">
            <svg class="w-6 h-6 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div>
                <strong class="font-bold">خطأ!</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Edit Form Card -->
    <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="p-8 md:p-12">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-2">تعديل بيانات العقار</h2>
            <p class="text-center text-gray-500 mb-10">قم بتحديث المعلومات أدناه للعقار المحدد.</p>

            <form action="{{ route('properties.update', $property->property_id) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                <!-- اسم العقار -->
                <div class="relative">
                    <input type="text" name="name" id="name"
                        class="block w-full px-4 py-3 text-gray-800 bg-gray-50 border-2 border-gray-200 rounded-lg appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                        value="{{ old('name', $property->name) }}" required placeholder=" " />
                    <label for="name" class="absolute text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-gray-50 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">
                        اسم العقار
                    </label>
                </div>

                <!-- العنوان -->
                <div class="relative">
                    <input type="text" name="address" id="address"
                        class="block w-full px-4 py-3 text-gray-800 bg-gray-50 border-2 border-gray-200 rounded-lg appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                        value="{{ old('address', $property->address) }}" required placeholder=" " />
                    <label for="address" class="absolute text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-gray-50 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">
                        العنوان
                    </label>
                </div>

                <!-- النوع -->
                <div class="relative">
                    <select name="type" id="type"
                        class="block w-full px-4 py-3 text-gray-800 bg-gray-50 border-2 border-gray-200 rounded-lg appearance-none focus:outline-none focus:ring-0 focus:border-blue-600"
                        required>
                        <option value="" disabled {{ old('type', $property->type) == '' ? 'selected' : '' }}>اختر النوع</option>
                        <option value="big_house" {{ old('type', $property->type) == 'big_house' ? 'selected' : '' }}>بيت كبير</option>
                        <option value="building" {{ old('type', $property->type) == 'building' ? 'selected' : '' }}>عمارة</option>
                        <option value="villa" {{ old('type', $property->type) == 'villa' ? 'selected' : '' }}>فلة</option>
                    </select>
                    <label for="type" class="absolute text-gray-500 text-sm -top-3 right-3 bg-white px-1">نوع العقار</label>
                </div>

                <!-- الحالة -->
                <div class="relative">
                    <select name="status" id="status"
                        class="block w-full px-4 py-3 text-gray-800 bg-gray-50 border-2 border-gray-200 rounded-lg appearance-none focus:outline-none focus:ring-0 focus:border-blue-600"
                        required>
                        <option value="" disabled {{ old('status', $property->status) == '' ? 'selected' : '' }}>اختر الحالة</option>
                        <option value="available" {{ old('status', $property->status) == 'available' ? 'selected' : '' }}>متاح</option>
                        <option value="rented" {{ old('status', $property->status) == 'rented' ? 'selected' : '' }}>مؤجر</option>
                        <option value="under_maintenance" {{ old('status', $property->status) == 'under_maintenance' ? 'selected' : '' }}>تحت الصيانة</option>
                    </select>
                    <label for="status" class="absolute text-gray-500 text-sm -top-3 right-3 bg-white px-1">الحالة</label>
                </div>

                <!-- زر الحفظ -->
                <div class="text-center pt-6">
                    <button type="submit"
                        class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 ease-in-out focus:outline-none focus:ring-4 focus:ring-blue-300">
                        تحديث العقار
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
