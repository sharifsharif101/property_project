@extends('layouts.app')

@section('title', 'تفاصيل الوحدة')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">تفاصيل الوحدة #{{ $unit->id }}</h1>
    
    <div class="bg-white shadow-md rounded-lg p-6">
        <p><strong>العقار:</strong> {{ $unit->property->name ?? 'غير معروف' }}</p>
        <p><strong>رقم الوحدة:</strong> {{ $unit->unit_number }}</p>
        <p><strong>غرف النوم:</strong> {{ $unit->bedrooms }}</p>
        <p><strong>الحمامات:</strong> {{ $unit->bathrooms }}</p>
        <p><strong>المساحة:</strong> {{ $unit->area }} م²</p>
        <p><strong>الطابق:</strong> {{ $unit->floor_number }}</p>
        <p><strong>الحالة:</strong> 
            @php
                $statusLabels = [
                    'available' => 'متاحة',
                    'reserved' => 'محجوزة',
                    'rented' => 'مؤجرة',
                    'under_maintenance' => 'تحت الصيانة',
                    'disabled' => 'معطلة',
                ];
            @endphp

            <span class="px-3 py-1 rounded-full text-white text-sm font-semibold
                @switch($unit->status)
                    @case('available') bg-green-500 @break
                    @case('reserved') bg-yellow-400 text-black @break
                    @case('rented') bg-blue-500 @break
                    @case('under_maintenance') bg-red-500 @break
                    @case('disabled') bg-gray-500 @break
                    @default bg-gray-300 text-black
                @endswitch
            ">
                {{ $statusLabels[$unit->status] ?? 'غير معروفة' }}
            </span>
        </p>
    </div>

    <a href="{{ route('units.index') }}" class="inline-block mt-6 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
        العودة للقائمة
    </a>
</div>
@endsection
