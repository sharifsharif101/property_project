<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PropertyController;  
use App\Http\Controllers\UnitController;

Route::get('/home', function () {
    return view('home');  
})->name('home');

 

// عرض كل العقارات (الصفحة الرئيسية)
Route::get('/', [PropertyController::class, 'index'])->name('properties.index');

// عرض نموذج إنشاء عقار جديد
Route::get('/properties/create', [PropertyController::class, 'create'])->name('properties.create');

// تخزين عقار جديد
Route::post('/properties', [PropertyController::class, 'store'])->name('properties.store');

// عرض تفاصيل عقار معيّن
Route::get('/properties/{property_id}', [PropertyController::class, 'show'])->name('properties.show');

// عرض نموذج تعديل عقار موجود
Route::get('/properties/{property}/edit', [PropertyController::class, 'edit'])->name('properties.edit');

// تحديث بيانات عقار موجود
Route::put('/properties/{property}', [PropertyController::class, 'update'])->name('properties.update');

// حذف عقار
Route::delete('/properties/{property}', [PropertyController::class, 'destroy'])->name('properties.destroy');

/////////////////////////////////////// units ..................

Route::get('/units/create', [UnitController::class, 'create'])->name('units.create');
Route::post('/units', [UnitController::class, 'store'])->name('units.store');
Route::get('/units', [UnitController::class, 'index'])->name('units.index');