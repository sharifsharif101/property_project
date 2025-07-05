<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Contract;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/locked-units', function () {
    // إرجاع الوحدات المرتبطة بعقود نشطة (تاريخ النهاية بعد الآن)
    $lockedUnitIds = Contract::where('end_date', '>=', now())
        ->pluck('unit_id'); // تأكد أن لديك عمود unit_id في جدول العقود

    return response()->json($lockedUnitIds);
});