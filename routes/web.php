<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PropertyController;  
use App\Http\Controllers\UnitController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\ContractFileController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RentInstallmentController; // تأكد من استيراد الكنترولر


Route::get('/home', function () {
    return view('home');  
})->name('home');

Route::get('/test', function () {
    return view('tenants.test');  
});


 

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
Route::patch('/units/{unit}/update-field', [UnitController::class, 'updateField'])->name('units.updateField');
Route::post('/units/bulk-delete', [UnitController::class, 'bulkDelete'])->name('units.bulkDelete');
Route::get('units/{unit}', [UnitController::class, 'show'])->name('units.show');
Route::get('units/{unit}/edit', [UnitController::class, 'edit'])->name('units.edit');
Route::put('units/{unit}', [UnitController::class, 'update'])->name('units.update');




/////////////////////////////////////// Tenant ..................

 Route::get('/tenants/create', [TenantController::class, 'create'])->name('tenants.create');
Route::post('/tenants', [TenantController::class, 'store'])->name('tenants.store');

Route::get('/tenants', [TenantController::class, 'index'])->name('tenants.index');

Route::get('/tenants/create', [TenantController::class, 'create'])->name('tenants.create');
Route::post('/tenants', [TenantController::class, 'store'])->name('tenants.store');
Route::get('/tenants/{tenant}', [TenantController::class, 'show'])->name('tenants.show');   // عرض مستأجر معين
Route::get('/tenants/{tenant}/edit', [TenantController::class, 'edit'])->name('tenants.edit'); // نموذج تعديل
Route::put('/tenants/{tenant}', [TenantController::class, 'update'])->name('tenants.update');  // تحديث بيانات
Route::delete('/tenants/{tenant}', [TenantController::class, 'destroy'])->name('tenants.destroy'); // حذف

/////////////////////////////////////// contracts ..................

 

// Index - List all contracts
Route::get('/contracts', [ContractController::class, 'index'])->name('contracts.index');

// Create - Show form to create a new contract
Route::get('/contracts/create', [ContractController::class, 'create'])->name('contracts.create');

// Store - Save the new contract
Route::post('/contracts', [ContractController::class, 'store'])->name('contracts.store');

// Show - Display a specific contract
Route::get('/contracts/{contract}', [ContractController::class, 'show'])->name('contracts.show');

// Edit - Show form to edit a contract
Route::get('/contracts/{contract}/edit', [ContractController::class, 'edit'])->name('contracts.edit');

// Update - Save the edited contract
Route::put('/contracts/{contract}', [ContractController::class, 'update'])->name('contracts.update');

// Destroy - Delete a contract
Route::delete('/contracts/{contract}', [ContractController::class, 'destroy'])->name('contracts.destroy');

Route::get('/contracts/{contract}/file/download', [ContractController::class, 'downloadFile'])  ->name('contract_files.download');
//اختيار عقار معين يؤدي الى جلب كل الواحدات المرتبطه به 
Route::get('/properties/{property}/units', [App\Http\Controllers\PropertyController::class, 'getUnits']);


/////////////////////////////////////// payments ..................

Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');

Route::get('/installments', [RentInstallmentController::class, 'index'])->name('installments.index');

Route::get('/installments/list', [RentInstallmentController::class, 'accordionView'])->name('installments.accordion');