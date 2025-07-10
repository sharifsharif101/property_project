@extends('layouts.app')

@section('title', 'تعديل الوحدة')

@section('content')
    <div class="space-y-6">
        <!-- Page Header (kept for context) -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <p class="text-sm text-gray-500">لوحة التحكم / الوحدات</p>
                <h1 class="text-2xl font-bold text-gray-800">تعديل الوحدة #{{ $unit->unit_number }}</h1>
            </div>
        </div>

        <!-- The new centered form card, inspired by your example -->
        <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
            
            <div class="text-xl py-4 px-6 bg-gray-800 text-white text-center font-bold uppercase tracking-wider">
                تعديل بيانات الوحدة
            </div>

            <form action="{{ route('units.update', $unit->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="py-6 px-8">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                        <!-- Unit Number -->
                        <div class="sm:col-span-1">
                            <label for="unit_number" class="block text-gray-700 font-semibold mb-2">رقم الوحدة</label>
                            <input type="text" name="unit_number" id="unit_number" value="{{ old('unit_number', $unit->unit_number) }}" required
                                   class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('unit_number') border-red-500 @enderror">
                            @error('unit_number')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <!-- Floor Number -->
                        <div class="sm:col-span-1">
                            <label for="floor_number" class="block text-gray-700 font-semibold mb-2">رقم الطابق</label>
                            <input type="number" name="floor_number" id="floor_number" value="{{ old('floor_number', $unit->floor_number) }}" required
                                   class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('floor_number') border-red-500 @enderror">
                            @error('floor_number')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <!-- Property -->
                        <div class="sm:col-span-2">
                            <label for="property_id" class="block text-gray-700 font-semibold mb-2">العقار التابع له</label>
                            <select name="property_id" id="property_id" required
                                    class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('property_id') border-red-500 @enderror">
                                <option value="">-- اختر العقار --</option>
                                @foreach($properties as $property)
                                    <option value="{{ $property->property_id }}" @selected(old('property_id', $unit->property_id) == $property->property_id)>{{ $property->name }}</option>
                                @endforeach
                            </select>
                            @error('property_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <hr class="sm:col-span-2 my-2">

                        <!-- Bedrooms -->
                        <div class="sm:col-span-1">
                            <label for="bedrooms" class="block text-gray-700 font-semibold mb-2">غرف النوم</label>
                            <input type="number" name="bedrooms" id="bedrooms" value="{{ old('bedrooms', $unit->bedrooms) }}" required min="0"
                                   class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('bedrooms') border-red-500 @enderror">
                            @error('bedrooms')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <!-- Bathrooms -->
                        <div class="sm:col-span-1">
                            <label for="bathrooms" class="block text-gray-700 font-semibold mb-2">الحمامات</label>
                            <input type="number" name="bathrooms" id="bathrooms" value="{{ old('bathrooms', $unit->bathrooms) }}" required min="0"
                                   class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('bathrooms') border-red-500 @enderror">
                            @error('bathrooms')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        
                        <!-- Area -->
                        <div class="sm:col-span-1">
                            <label for="area" class="block text-gray-700 font-semibold mb-2">المساحة (م²)</label>
                            <input type="number" step="0.01" name="area" id="area" value="{{ old('area', $unit->area) }}" required min="0"
                                   class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('area') border-red-500 @enderror">
                            @error('area')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <!-- Status -->
                        <div class="sm:col-span-1">
                            <label for="status" class="block text-gray-700 font-semibold mb-2">حالة الوحدة</label>
                            <select name="status" id="status" required
                                    class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('status') border-red-500 @enderror">
                                <option value="available" @selected(old('status', $unit->status) == 'available')>متاحة</option>
                                <option value="reserved" @selected(old('status', $unit->status) == 'reserved')>محجوزة</option>
                                <option value="rented" @selected(old('status', $unit->status) == 'rented')>مؤجرة</option>
                                <option value="under_maintenance" @selected(old('status', $unit->status) == 'under_maintenance')>تحت الصيانة</option>
                                <option value="disabled" @selected(old('status', 'available') == 'disabled')>معطلة</option>
                            </select>
                            @error('status')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <!-- Notes -->
                        <div class="sm:col-span-2">
                            <label for="notes" class="block text-gray-700 font-semibold mb-2">ملاحظات</label>
                            <textarea name="notes" id="notes" rows="4" class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('notes') border-red-500 @enderror">{{ old('notes', $unit->notes) }}</textarea>
                            @error('notes')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end gap-x-4 pt-6 mt-6 border-t border-gray-200">
                        <a href="{{ route('units.index') }}" class="text-gray-600 font-semibold hover:text-gray-800">
                            إلغاء
                        </a>
                        <button type="submit" class="bg-gray-800 text-white font-bold py-2 px-6 rounded-lg hover:bg-gray-700 focus:outline-none focus:shadow-outline">
                            حفظ التعديلات
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection