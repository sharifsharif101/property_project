@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
  <div class="bg-white rounded-lg shadow-md border border-gray-200 p-8">
    <h3 class="text-[36px] font-semibold text-gray-800 mb-10 text-right leading-snug">
      تفاصيل العقار: <span class="text-blue-600 text-[30px]">{{ $property->name }}</span>
    </h3>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-10 gap-x-14 text-right">

      <div class="flex flex-col space-y-1">
        <span class="text-gray-500 font-semibold text-[20px] uppercase tracking-wide">رقم العقار</span>
        <span class="text-gray-800 text-[24px] font-medium">{{ $property->property_id }}</span>
      </div>

      <div class="flex flex-col space-y-1">
        <span class="text-gray-500 font-semibold text-[20px] uppercase tracking-wide">اسم العقار</span>
        <span class="text-gray-800 text-[24px] font-medium">{{ $property->name }}</span>
      </div>

      <div class="flex flex-col space-y-1">
        <span class="text-gray-500 font-semibold text-[20px] uppercase tracking-wide">العنوان</span>
        <span class="text-gray-800 text-[24px] font-medium">{{ $property->address }}</span>
      </div>

      <div class="flex flex-col space-y-1">
        <span class="text-gray-500 font-semibold text-[20px] uppercase tracking-wide">النوع</span>
        <span class="text-gray-800 text-[24px] font-medium">
          @switch($property->type)
            @case('big_house') بيت كبير @break
            @case('building') عمارة @break
            @case('villa') فيلا @break
            @default -
          @endswitch
        </span>
      </div>

      <div class="flex flex-col space-y-1">
        <span class="text-gray-500 font-semibold text-[20px] uppercase tracking-wide">الحالة</span>
        <span class="text-gray-800 text-[24px] font-medium">
          @switch($property->status)
            @case('available') متاح @break
            @case('rented') مؤجر @break
            @case('under_maintenance') تحت الصيانة @break
            @default -
          @endswitch
        </span>
      </div>

      <div class="flex flex-col space-y-1">
        <span class="text-gray-500 font-semibold text-[20px] uppercase tracking-wide">تاريخ الإضافة</span>
        <span class="text-gray-800 text-[24px] font-medium">{{ $property->created_at->format('Y-m-d') }}</span>
      </div>

    </div>

    <div class="mt-12 text-right">
      <a href="{{ route('properties.index') }}" 
         class="inline-block bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-3 px-8 rounded-lg shadow transition text-[20px]">
        العودة
      </a>
    </div>
  </div>
</div>
@endsection
