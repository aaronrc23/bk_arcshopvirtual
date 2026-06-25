<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/clear-cache', function () {
    // Limpiar cache de config
    Artisan::call('config:clear');

    // Limpiar cache de rutas
    Artisan::call('route:clear');

    // Limpiar cache de vistas
    Artisan::call('view:clear');

    // Limpiar cache de aplicación
    Artisan::call('cache:clear');

    Artisan::call('optimize:clear');

    return "Cache limpiada correctamente ✅";
});


Route::get('/rutas/list', function () {
    Artisan::call('route:list');

    return '<pre>' . Artisan::output() . '</pre>';
});


Route::get('/migrate', function () {
    // Ejemplo rápido de protección
    if (request()->query('key') !== env('APP_KEY')) {
        abort(403);
    }

    Artisan::call('migrate', ['--force' => true]);
    return "Migraciones ejecutadas";
});


Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return "Storage link creado correctamente ✅";
});

require __DIR__ . "/Auth/LoginRoute.php";
require __DIR__ . "/Administracion/EmpleadoRoute.php";
require __DIR__ . "/Administracion/DashboardRouter.php";
require __DIR__ . "/Logistica/CategoriaRouter.php";
require __DIR__ . "/Logistica/MarcasRouter.php";
require __DIR__ . "/Warehouse/MovimientoRouter.php";
require __DIR__ . "/Administracion/AsignacionRolesRouter.php";
require __DIR__ . "/Logistica/ProductoRouter.php";
require __DIR__ . "/Shop/BannerRouter.php";
require __DIR__ . "/Administracion/EmpresaRouter.php";
require __DIR__ . "/Shop/FooterRouter.php";
require __DIR__ . "/Consultas/ConsultasRouter.php";
require __DIR__ . "/Warehouse/AlmacenRouter.php";
require __DIR__ . "/Warehouse/InventarioRouter.php";
require __DIR__ . "/Warehouse/MovimientoRouter.php";



// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
