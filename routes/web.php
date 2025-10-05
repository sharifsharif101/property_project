<?php

use Illuminate\Support\Facades\Route;

// --- استدعاء الـ Controllers ---
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\RentInstallmentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/dashboard2', function () {
    return view('dashboard2.index');
})->name('dashboard2.index');


// --- 1. المسار الرئيسي (صفحة الهبوط) ---
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard2.index')
        : redirect()->route('login.create');
});

// --- 2. المسارات المحمية داخل التطبيق ---
Route::middleware(['auth'])->group(function () {

    // مسار لوحة التحكم
    Route::get('/dashboard', [RentInstallmentController::class, 'index'])->name('dashboard');

    // --- إدارة العقود (تم تعديل صلاحياتها) ---
    // أي شخص يملك صلاحية عرض العقود يمكنه رؤية القائمة
    Route::get('/contracts', [ContractController::class, 'index'])->middleware('permission:view contracts')->name('contracts.index');
    // صلاحيات محددة لكل فعل
    Route::get('/contracts/create', [ContractController::class, 'create'])->middleware('permission:create contracts')->name('contracts.create');
    Route::post('/contracts', [ContractController::class, 'store'])->middleware('permission:create contracts')->name('contracts.store');
    Route::get('/contracts/{contract}', [ContractController::class, 'show'])->middleware('permission:view contracts')->name('contracts.show');
    Route::get('/contracts/{contract}/edit', [ContractController::class, 'edit'])->middleware('permission:edit contracts')->name('contracts.edit');
    Route::put('/contracts/{contract}', [ContractController::class, 'update'])->middleware('permission:edit contracts')->name('contracts.update');
    Route::delete('/contracts/{contract}', [ContractController::class, 'destroy'])->middleware('permission:delete contracts')->name('contracts.destroy');
    Route::get('/contracts/{contract}/file/download', [ContractController::class, 'downloadFile'])->middleware('permission:view contracts')->name('contract_files.download');


    // --- إدارة المدفوعات والأقساط (تم تعديل صلاحياتها) ---
    // لاحظ أننا لم نعد بحاجة لمجموعة middleware للأدوار هنا
    Route::get('/installments', [RentInstallmentController::class, 'index'])->middleware('permission:view payments')->name('installments.index');
    Route::get('/installments/accordion', [RentInstallmentController::class, 'accordionView'])->middleware('permission:view payments')->name('installments.accordion');
    Route::get('/payments', [PaymentController::class, 'index'])->middleware('permission:view payments')->name('payments.index');
    Route::get('/payments/create', [PaymentController::class, 'create'])->middleware('permission:create payments')->name('payments.create');
    Route::post('/payments', [PaymentController::class, 'store'])->middleware('permission:create payments')->name('payments.store');
    Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->middleware('permission:delete payments')->name('payments.destroy');
    // يمكنك إضافة accordion للمدفوعات بنفس الطريقة إذا احتجت


    // --- مسارات الإدارة (لا تزال محمية بالأدوار لأنها صلاحيات واسعة) ---
    Route::middleware(['role:Super Admin|Property Manager'])->group(function () {
        Route::resource('properties', PropertyController::class);
        Route::get('/properties/{property}/units', [PropertyController::class, 'getUnits'])->name('properties.getUnits');
        Route::resource('units', UnitController::class);
        Route::patch('/units/{unit}/update-field', [UnitController::class, 'updateField'])->name('units.updateField');
        Route::post('/units/bulk-delete', [UnitController::class, 'bulkDelete'])->name('units.bulkDelete');
        Route::resource('tenants', TenantController::class);
        Route::get('/payments/accordion', [PaymentController::class, 'accordionView'])->name('payments.accordion');

    });

});
 
// --- 3. مسارات المصادقة ---
Route::middleware('guest')->group(function () {
    Route::get('register', [AuthenticatedSessionController::class, 'createRegistrationForm'])->name('register.create');
    Route::post('register', [AuthenticatedSessionController::class, 'register'])->name('register.store');
    Route::get('login', [AuthenticatedSessionController::class, 'createLoginForm'])->name('login.create');
    Route::post('login', [AuthenticatedSessionController::class, 'login'])->name('login.store');
});

Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');
