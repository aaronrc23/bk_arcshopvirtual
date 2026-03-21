<?php

use App\Http\Controllers\Warehouse\Almacen2Controller;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'authempleado'])->prefix('almacenes')->controller(Almacen2Controller::class)->group(function () {
    Route::get('/', 'findAll');
    Route::get('findByName/{name}', 'findByName');
    Route::get('/find/{id}', 'find');
    Route::post('/', 'create');
    Route::get('/{id}', 'buscarAlmacenes');
    Route::put('/{id}', 'update');
    Route::put('/{id}/principal', 'updatePrincipal');
    Route::put('/restore/{id}', 'restore');
    Route::delete('/{id}', 'delete');
});
