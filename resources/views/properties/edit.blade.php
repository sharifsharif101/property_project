@extends('layouts.app')

@section('content')
{{ Breadcrumbs::render('properties.edit', $property) }}
    @if (session('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 2000)" x-show="show" x-transition
            class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">نجاح!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition
            class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">خطأ!</strong>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="max-w-2xl mx-auto mt-10">
        <div class="bg-white shadow-md rounded-2xl p-6 mt-10">
            <h2 class="text-2xl font-bold mb-6 text-center text-gray-700">تعديل بيانات العقار</h2>

            <form action="{{ route('properties.update', $property->property_id) }}" method="POST" class="space-y-4 mt-10">
                @csrf
                @method('PUT')

                <!-- اسم العقار -->
                <div>
                    <label for="name" class="block text-gray-700 font-medium mb-1">اسم العقار</label>
                    <input type="text" name="name" id="name"
                        class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        value="{{ old('name', $property->name) }}" required>
                </div>

                <!-- العنوان -->
                <div>
                    <label for="address" class="block text-gray-700 font-medium mb-1">العنوان</label>
                    <input type="text" name="address" id="address"
                        class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        value="{{ old('address', $property->address) }}" required>
                </div>

                <!-- النوع -->
                <div>
                    <label for="type" class="block text-gray-700 font-medium mb-1">نوع العقار</label>
                    <select name="type" id="type"
                        class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                        <option value="">اختر النوع</option>
                        <option value="big_house" {{ old('type', $property->type) == 'big_house' ? 'selected' : '' }}>بيت كبير</option>
                        <option value="building" {{ old('type', $property->type) == 'building' ? 'selected' : '' }}>عمارة</option>
                        <option value="villa" {{ old('type', $property->type) == 'villa' ? 'selected' : '' }}>فلة</option>
                    </select>
                </div>

                <!-- الحالة -->
                <div>
                    <label for="status" class="block text-gray-700 font-medium mb-1">الحالة</label>
                    <select name="status" id="status"
                        class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                        <option value="">اختر الحالة</option>
                        <option value="available" {{ old('status', $property->status) == 'available' ? 'selected' : '' }}>متاح</option>
                        <option value="rented" {{ old('status', $property->status) == 'rented' ? 'selected' : '' }}>مؤجر</option>
                        <option value="under_maintenance" {{ old('status', $property->status) == 'under_maintenance' ? 'selected' : '' }}>تحت الصيانة</option>
                    </select>
                </div>

                <!-- زر الحفظ -->
                <div class="text-center pt-4">
                    <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded-xl transition duration-300">
                        تحديث العقار
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
