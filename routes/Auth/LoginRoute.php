<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('prueba');
Route::post('/email', [AuthController::class, 'enviaremail']);
