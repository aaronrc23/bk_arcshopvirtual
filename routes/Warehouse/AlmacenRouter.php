<?php

use App\Http\Controllers\Warehouse\AlmacenController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'authempleado'])->prefix('almacenes')->controller(AlmacenController::class)->group(function () {
    Route::get('/', 'listindex');
    Route::get('findByName/{name}', 'findByName');
    Route::get('/find/{id}', 'find');
    Route::post('/', 'store');
    Route::get('/{id}', 'show');
    Route::put('/{id}', 'update');
    Route::put('/{id}/principal', 'updatePrincipal');
    Route::put('/restore/{id}', 'restore');
    Route::delete('/{id}', 'destroy');
});
