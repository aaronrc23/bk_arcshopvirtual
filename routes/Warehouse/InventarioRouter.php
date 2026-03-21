<?php

use App\Http\Controllers\Warehouse\InventarioController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'authempleado'])->prefix('inventario')->controller(InventarioController::class)->group(function () {
    Route::get('/findAll', 'index');
    Route::get('/find/{id}', 'show');
    Route::post('/create', 'store');
    Route::post('/movimiento', 'movimiento');
    Route::delete('/{id}', 'delete');
});
