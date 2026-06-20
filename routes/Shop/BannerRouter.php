<?php

use App\Http\Controllers\Shop\BannerController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'authempleado'])->prefix('banners')->controller(BannerController::class)->group(function () {
    Route::get('/list', 'index');
    Route::post('/create', 'store');
    Route::put('/update/{id}', 'update');
    Route::put('/desactivar/{id}', 'desactivar');
    Route::put('/reactivar/{id}', 'reactivar');
    Route::delete('/destroy/{id}', 'destroy');
    Route::post('/restore/{id}', 'restore');
});

// Ruta pública sin autenticación para el frontend de la tienda
Route::get('/banners/active', [BannerController::class, 'active']);
