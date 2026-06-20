<?php

use App\Http\Controllers\Shop\FooterController;
use Illuminate\Support\Facades\Route;

// Ruta pública sin autenticación para el frontend de la tienda
Route::get('/footer', [FooterController::class, 'index']);

// Rutas protegidas para el panel admin
Route::middleware(['auth:sanctum', 'authempleado'])->prefix('footer')->controller(FooterController::class)->group(function () {
    Route::put('/update/{id}', 'update');
    Route::put('/update-links/{id}', 'updateLinks');
});
