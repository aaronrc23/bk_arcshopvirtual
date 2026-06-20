<?php

use App\Http\Controllers\Administracion\EmpresaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'authempleado'])->prefix('empresa')->controller(EmpresaController::class)->group(function () {
    Route::get('/show', 'index');
    Route::put('/update/{id}', 'update');
});
