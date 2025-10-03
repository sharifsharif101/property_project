@extends('layouts.app') {{-- استخدام ال layout نفسه --}}

@section('title', 'لوحة التحكم')

@section('header')
    <h1 class="text-2xl font-bold text-gray-800">لوحة التحكم</h1>
@endsection

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white shadow rounded-lg p-5 flex items-center justify-between">
        <div>
            <p class="text-gray-500">عدد المستخدمين</p>
            <p class="text-2xl font-bold text-gray-800">1,245</p>
        </div>
        <i class="bi bi-people-fill text-4xl text-blue-500"></i>
    </div>
    <div class="bg-white shadow rounded-lg p-5 flex items-center justify-between">
        <div>
            <p class="text-gray-500">المبيعات اليوم</p>
            <p class="text-2xl font-bold text-gray-800">3,567 ر.س</p>
        </div>
        <i class="bi bi-currency-dollar text-4xl text-green-500"></i>
    </div>
    <div class="bg-white shadow rounded-lg p-5 flex items-center justify-between">
        <div>
            <p class="text-gray-500">الطلبات الجديدة</p>
            <p class="text-2xl font-bold text-gray-800">87</p>
        </div>
        <i class="bi bi-bag-fill text-4xl text-yellow-500"></i>
    </div>
    <div class="bg-white shadow rounded-lg p-5 flex items-center justify-between">
        <div>
            <p class="text-gray-500">المهام المفتوحة</p>
            <p class="text-2xl font-bold text-gray-800">14</p>
        </div>
        <i class="bi bi-list-task text-4xl text-red-500"></i>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4">آخر الطلبات</h2>
        <ul class="divide-y divide-gray-200">
            <li class="py-2 flex justify-between items-center">
                <span>طلب #1023</span>
                <span class="text-green-600 font-semibold">مكتمل</span>
            </li>
            <li class="py-2 flex justify-between items-center">
                <span>طلب #1024</span>
                <span class="text-yellow-600 font-semibold">قيد التنفيذ</span>
            </li>
            <li class="py-2 flex justify-between items-center">
                <span>طلب #1025</span>
                <span class="text-red-600 font-semibold">ملغي</span>
            </li>
        </ul>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4">التقارير الشهرية</h2>
        <p class="text-gray-600">يمكنك عرض الرسوم البيانية والمبيعات الشهرية هنا.</p>
        <div class="mt-4 h-48 bg-gray-100 flex items-center justify-center text-gray-400 rounded-lg">
            <!-- Placeholder للرسوم البيانية -->
            <i class="bi bi-bar-chart-line fs-1"></i>
        </div>
    </div>
</div>

<div class="mt-6">
    <h2 class="text-xl font-semibold mb-4">الإجراءات السريعة</h2>
    <div class="flex flex-wrap gap-4">
        <a href="#" class="px-4 py-2 bg-blue-500 text-white rounded shadow flex items-center gap-2">
            <i class="bi bi-plus-circle"></i> إضافة مستخدم
        </a>
        <a href="#" class="px-4 py-2 bg-green-500 text-white rounded shadow flex items-center gap-2">
            <i class="bi bi-bag-plus-fill"></i> إضافة طلب
        </a>
        <a href="#" class="px-4 py-2 bg-yellow-500 text-white rounded shadow flex items-center gap-2">
            <i class="bi bi-file-earmark-text-fill"></i> إنشاء تقرير
        </a>
        <a href="#" class="px-4 py-2 bg-red-500 text-white rounded shadow flex items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill"></i> متابعة التنبيهات
        </a>
    </div>
</div>
@endsection
