<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Administracion\DashboardController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:sanctum', 'authempleado'])->prefix('dashboard')->controller(DashboardController::class)->group(function () {
    Route::get("/", "index");
});
