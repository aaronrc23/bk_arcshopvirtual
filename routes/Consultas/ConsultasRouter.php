<?php

use App\Http\Controllers\Consultas\ConsultasController;
use Illuminate\Support\Facades\Route;

Route::get('/list', [ConsultasController::class, 'listInventarioVirtual']);
Route::get('/{id}', [ConsultasController::class, 'show']);

Route::middleware(['auth:sanctum', 'authempleado'])->prefix('consultas')->controller(ConsultasController::class)->group(function () {
    Route::get('listSucursal', 'listSucursal');
    Route::get('listTipComprobante', 'listTipComprobante');
    Route::get('refprod', 'refprod');
});
