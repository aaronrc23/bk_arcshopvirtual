<?php

use App\Http\Controllers\Administracion\EmpleadoController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/panel/login', [AuthController::class, 'loginEmpleado']);


Route::middleware(['auth:sanctum'])->prefix('empleado')->controller(EmpleadoController::class)->group(function () {
    Route::get('index', 'index');
});



Route::middleware(['auth:sanctum', 'permission:editar roles'])->prefix('empleado')->controller(EmpleadoController::class)->group(function () {

    Route::post("register", "register");
    Route::put("update/{empleado}", "update");
    Route::delete("destroy/{empleadoId}", "destroy");
    Route::put("restore/{empleadoId}", "restore");
});
