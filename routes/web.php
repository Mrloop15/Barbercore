<?php

use App\Http\Controllers\Web\ServicioController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\ClienteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'mostrarLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'iniciarSesion'])->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/clientes-inactivos', [ClienteController::class, 'inactivos'])->name('clientes.inactivos');
    Route::resource('clientes', ClienteController::class);
    Route::resource('servicios', ServicioController::class)->except(['show']);
    Route::post('/logout', [AuthController::class, 'cerrarSesion'])->name('logout');
});