<?php

use App\Http\Controllers\Logistica\MarcasController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'authempleado'])->prefix('marcas')->controller(MarcasController::class)->group(function () {
    Route::get('/list', 'index');
    Route::post('/create', 'store');
    Route::put('/update/{id}', 'update');
    Route::put('/desactivar/{id}', 'desactivar');
    Route::put('/reactivar/{id}', 'reactivar');
    Route::delete('/destroy/{id}', 'destroy')->middleware('permission:eliminar productos');
}); 
