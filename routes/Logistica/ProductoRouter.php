<?php

use App\Http\Controllers\Logistica\ProductoController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'authempleado'])->prefix('producto')->controller(ProductoController::class)->group(function () {
    Route::get('/list', 'listProductos');
    Route::get('/productos/{product}/imagenes', 'listImages');
    Route::post('/create', 'storeProductos')->middleware('permission:crear productos');
    Route::put('/update/{id}', 'updateProductos')->middleware('permission:editar productos');
    Route::delete('/destroy/{id}', 'destroyProductos')->middleware('permission:eliminar productos');
    Route::put('/update-is-principal/{id}', 'updateIsPrincipal')->middleware('permission:editar productos');
    Route::post('/update-image/{id}', 'updateImgProductos')->middleware('permission:editar productos');
    Route::delete('/delete-image/{id}', 'deleteImage')->middleware('permission:editar productos');
    Route::put('/desactivar/{id}', 'desactivarProducto')->middleware('permission:editar productos');
    Route::put('/reactivar/{id}', 'reactivarProducto')->middleware('permission:editar productos');
    Route::get('/search/{product}', 'listProductosByNombre');
});
