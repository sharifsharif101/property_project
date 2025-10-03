@extends('layouts.app')

@section('title', $property->name)

@section('content')
 
<div class="space-y-6">
    {{-- Property Header --}}
    <div class="text-right">
        <h1 class="text-2xl font-bold text-gray-800">{{ $property->name }}</h1>
        <p class="text-sm text-gray-500 mt-1">
            @switch($property->type)
                @case('big_house') بيت كبير @break
                @case('building') عمارة @break
                @case('villa') فيلا @break
                @default -
            @endswitch
            -
            @switch($property->status)
                @case('available') متاح @break
                @case('rented') مؤجر @break
                @case('under_maintenance') تحت الصيانة @break
                @default -
            @endswitch
        </p>
    </div>

    {{-- Property Details Card --}}
    <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
        <div class="p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Property ID -->
                <div class="flex flex-col space-y-1 p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-2 space-x-reverse">
                        <i class="fas fa-hashtag text-blue-500 text-xl"></i>
                        <span class="text-gray-500 font-semibold text-sm uppercase tracking-wide">رقم العقار</span>
                    </div>
                    <span class="text-gray-800 text-xl font-medium">{{ $property->property_id }}</span>
                </div>

                <!-- Name -->
                <div class="flex flex-col space-y-1 p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-2 space-x-reverse">
                        <i class="fas fa-tag text-blue-500 text-xl"></i>
                        <span class="text-gray-500 font-semibold text-sm uppercase tracking-wide">اسم العقار</span>
                    </div>
                    <span class="text-gray-800 text-xl font-medium">{{ $property->name }}</span>
                </div>

                <!-- Address -->
                <div class="flex flex-col space-y-1 p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-2 space-x-reverse">
                        <i class="fas fa-map-marker-alt text-blue-500 text-xl"></i>
                        <span class="text-gray-500 font-semibold text-sm uppercase tracking-wide">العنوان</span>
                    </div>
                    <span class="text-gray-800 text-xl font-medium">{{ $property->address }}</span>
                </div>

                <!-- Type -->
                <div class="flex flex-col space-y-1 p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-2 space-x-reverse">
                        <i class="fas fa-building text-blue-500 text-xl"></i>
                        <span class="text-gray-500 font-semibold text-sm uppercase tracking-wide">النوع</span>
                    </div>
                    <span class="text-gray-800 text-xl font-medium">
                        @switch($property->type)
                            @case('big_house') بيت كبير @break
                            @case('building') عمارة @break
                            @case('villa') فيلا @break
                            @default -
                        @endswitch
                    </span>
                </div>

                <!-- Status -->
                <div class="flex flex-col space-y-1 p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-2 space-x-reverse">
                        <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                        <span class="text-gray-500 font-semibold text-sm uppercase tracking-wide">الحالة</span>
                    </div>
                    <span class="text-gray-800 text-xl font-medium">
                        @switch($property->status)
                            @case('available') متاح @break
                            @case('rented') مؤجر @break
                            @case('under_maintenance') تحت الصيانة @break
                            @default -
                        @endswitch
                    </span>
                </div>

                <!-- Creation Date -->
                <div class="flex flex-col space-y-1 p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-2 space-x-reverse">
                        <i class="fas fa-calendar-alt text-blue-500 text-xl"></i>
                        <span class="text-gray-500 font-semibold text-sm uppercase tracking-wide">تاريخ الإضافة</span>
                    </div>
                    <span class="text-gray-800 text-xl font-medium">{{ $property->created_at->format('Y-m-d') }}</span>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end space-x-3 space-x-reverse">
            <a href="{{ route('properties.index') }}" 
               class="inline-flex items-center justify-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-6 rounded-lg transition">
                <i class="fas fa-arrow-right"></i>
                <span>العودة إلى القائمة</span>
            </a>
            <a href="{{ route('properties.edit', $property->property_id) }}" 
               class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition">
                <i class="fas fa-edit"></i>
                <span>تعديل</span>
            </a>
        </div>
    </div>
</div>
@endsection