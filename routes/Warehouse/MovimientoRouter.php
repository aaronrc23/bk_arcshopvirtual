<?php

use App\Http\Controllers\Warehouse\MovimientodeInventario;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'authempleado'])->prefix('movimiento')->controller(MovimientodeInventario::class)->group(function () {
    Route::get('/', 'index');
});
