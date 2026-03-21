<?php

use App\Http\Controllers\Administracion\AsignacionController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:sanctum', 'authempleado'])->prefix('asignacion')->controller(AsignacionController::class)->group(function () {
    Route::get("listPuser/{id}", "listPermissionsUser");
    Route::get("list-roles", "listRoles");
    Route::post("assign-role", "assignRole");
    Route::post("updateRole", "updateRole")->middleware('permission:roles.editar');
    Route::post("assignPermissionToRole", "assignPermstoRole")->middleware('permission:roles.editar');
    Route::post("assignPermissionsToUser", "assignPermissionsToUser")->middleware('permission:roles.editar');
    Route::post("syncPermissionsToUser", "syncPermissionsToUser");
    Route::post("assignPermissionsByModule", "assignPermissionsByModule")->middleware('permission:roles.editar');
});