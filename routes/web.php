<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PropertyController; // هذا السطر مهم لاستدعاء الكنترولر

Route::get('/', function () {
    return view('welcome');
});

Route::get('/h', function () {
    return view('home');
});

Route::get('/properties/create', [PropertyController::class, 'create'])->name('properties.create');
Route::post('/properties', [PropertyController::class, 'store'])->name('properties.store');
Route::get('/properties/{property}/edit', [PropertyController::class, 'edit'])->name('properties.edit');
Route::put('/properties/{property}', [PropertyController::class, 'update'])->name('properties.update');