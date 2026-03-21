<?php

use App\Http\Controllers\Logistica\CategoriaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'authempleado'])->prefix('categoria')->controller(CategoriaController::class)->group(function () {
    Route::get('/list', 'listCategoriasFull');
    Route::get('listCatPadre', 'listCatPadre');
    Route::get('listCatEstrFull', 'listCatEstructuraFull');
    Route::get('listCatOne/{id}', 'listCatOne');
    Route::get('listCatHijas', 'listCatHijas');
    Route::get('catEliminadas', 'catEliminadas');
    Route::post('create', 'create')->middleware('permission:crear categorias');
    Route::post('addItems/{categoryId}', 'addItems')->middleware('permission:editar categorias');
    Route::post('update/{id}', 'update');
    Route::delete('destroy/{id}', 'destroy')->middleware('permission:eliminar categorias');
    Route::post('restore/{id}', 'restore')->middleware('permission:eliminar categorias');
    Route::put('desactivar/{id}', 'desactivar')->middleware('permission:editar categorias');
    Route::put('reactivar/{id}', 'reactivar')->middleware('permission:editar categorias');
});
