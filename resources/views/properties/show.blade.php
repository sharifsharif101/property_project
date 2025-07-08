@extends('layouts.app')

@section('content')
{{ Breadcrumbs::render('properties.show', $property) }}

<div class="container mx-auto px-4 py-8 max-w-4xl">
  <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
    <h3 class="text-4xl font-semibold text-gray-800 mb-2 text-right">{{ $property->name }}</h3>
    <p class="text-gray-600 text-lg mb-8 text-right">
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

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-8 text-right">
      <!-- Property ID -->
      <div class="flex flex-col space-y-1">
        <div class="flex items-center space-x-2 space-x-reverse">
          <i class="fas fa-hashtag text-gray-500 text-xl"></i>
          <span class="text-gray-500 font-semibold text-lg uppercase tracking-wide">رقم العقار</span>
        </div>
        <span class="text-gray-800 text-2xl font-medium">{{ $property->property_id }}</span>
      </div>

      <!-- Name -->
      <div class="flex flex-col space-y-1">
        <div class="flex items-center space-x-2 space-x-reverse">
          <i class="fas fa-tag text-gray-500 text-xl"></i>
          <span class="text-gray-500 font-semibold text-lg uppercase tracking-wide">اسم العقار</span>
        </div>
        <span class="text-gray-800 text-2xl font-medium">{{ $property->name }}</span>
      </div>

      <!-- Address -->
      <div class="flex flex-col space-y-1">
        <div class="flex items-center space-x-2 space-x-reverse">
          <i class="fas fa-map-marker-alt text-gray-500 text-xl"></i>
          <span class="text-gray-500 font-semibold text-lg uppercase tracking-wide">العنوان</span>
        </div>
        <span class="text-gray-800 text-2xl font-medium">{{ $property->address }}</span>
      </div>

      <!-- Type -->
      <div class="flex flex-col space-y-1">
        <div class="flex items-center space-x-2 space-x-reverse">
          <i class="fas fa-building text-gray-500 text-xl"></i>
          <span class="text-gray-500 font-semibold text-lg uppercase tracking-wide">النوع</span>
        </div>
        <span class="text-gray-800 text-2xl font-medium">
          @switch($property->type)
            @case('big_house') بيت كبير @break
            @case('building') عمارة @break
            @case('villa') فيلا @break
            @default -
          @endswitch
        </span>
      </div>

      <!-- Status -->
      <div class="flex flex-col space-y-1">
        <div class="flex items-center space-x-2 space-x-reverse">
          <i class="fas fa-info-circle text-gray-500 text-xl"></i>
          <span class="text-gray-500 font-semibold text-lg uppercase tracking-wide">الحالة</span>
        </div>
        <span class="text-gray-800 text-2xl font-medium">
          @switch($property->status)
            @case('available') متاح @break
            @case('rented') مؤجر @break
            @case('under_maintenance') تحت الصيانة @break
            @default -
          @endswitch
        </span>
      </div>

      <!-- Creation Date -->
      <div class="flex flex-col space-y-1">
        <div class="flex items-center space-x-2 space-x-reverse">
          <i class="fas fa-calendar-alt text-gray-500 text-xl"></i>
          <span class="text-gray-500 font-semibold text-lg uppercase tracking-wide">تاريخ الإضافة</span>
        </div>
        <span class="text-gray-800 text-2xl font-medium">{{ $property->created_at->format('Y-m-d') }}</span>
      </div>
    </div>

    <div class="mt-8 text-right">
      <a href="{{ route('properties.index') }}" 
         class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg shadow transition text-xl">
        العودة
      </a>
    </div>
  </div>
</div>
@endsection