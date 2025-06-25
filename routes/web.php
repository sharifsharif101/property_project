<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PropertyController; // هذا السطر مهم لاستدعاء الكنترولر

 
 


Route::get('/', [PropertyController::class, 'index'])->name('properties.index');

Route::get('/properties/create', [PropertyController::class, 'create'])->name('properties.create');
Route::post('/properties', [PropertyController::class, 'store'])->name('properties.store');
Route::get('/properties/{property}/edit', [PropertyController::class, 'edit'])->name('properties.edit');
Route::put('/properties/{property}', [PropertyController::class, 'update'])->name('properties.update');

Route::get('/properties/{property_id}', [PropertyController::class, 'show'])->name('properties.show');

Route::delete('/properties/{property}', [PropertyController::class, 'destroy'])->name('properties.destroy');
