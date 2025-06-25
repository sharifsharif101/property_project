@extends('layouts.app') 

@section('title', 'الرئيسية')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center bg-gradient-to-br from-blue-50 via-white to-blue-100 py-12">
    <div class="text-center space-y-6">
        <h1 class="text-4xl sm:text-5xl font-bold text-blue-800">مرحباً بك</h1>
        <h2 class="text-3xl sm:text-4xl font-semibold text-gray-700">برنامج إدارة العقارات</h2>
 
        <a href="{{ route('properties.index') }}"
           class="mt-6 inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg shadow transition">
            تصفح العقارات
        </a>
    </div>
</div>
@endsection

