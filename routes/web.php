<?php

use App\Http\Controllers\Web\LandingController;
use App\Http\Controllers\Web\UsuarioController;
use App\Http\Controllers\Web\ConfiguracionController;
use App\Http\Controllers\Web\VentaProductoController;
use App\Http\Controllers\Web\EstadisticaController;
use App\Http\Controllers\Web\RecompensaController;
use App\Http\Controllers\Web\ProductoController;
use App\Http\Controllers\Web\AgendaController;
use App\Http\Controllers\Web\CitaController;
use App\Http\Controllers\Web\ServicioController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\ClienteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'mostrarLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'iniciarSesion'])->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/create', [UsuarioController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios/{usuario}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.update');
    Route::put('/usuarios/{usuario}/estado', [UsuarioController::class, 'cambiarEstado'])->name('usuarios.estado');

    Route::get('/clientes-inactivos', [ClienteController::class, 'inactivos'])->name('clientes.inactivos');
    Route::resource('clientes', ClienteController::class);
    Route::resource('servicios', ServicioController::class)->except(['show']);
    Route::put('/citas/{cita}/cancelar', [CitaController::class, 'cancelar'])->name('citas.cancelar');
    Route::put('/citas/{cita}/completar', [CitaController::class, 'completar'])->name('citas.completar');
    Route::resource('citas', CitaController::class)->except(['show', 'destroy']);
    Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda.index');
    Route::resource('productos', ProductoController::class)->except(['show']);
    Route::resource('ventas-productos', VentaProductoController::class)->only(['index', 'create', 'store', 'show']);
    Route::get('/recompensas-canjear', [RecompensaController::class, 'formCanjear'])->name('recompensas.formCanjear');
    Route::post('/recompensas-canjear', [RecompensaController::class, 'canjear'])->name('recompensas.canjear');
    Route::resource('recompensas', RecompensaController::class)->except(['show']);
    Route::get('/estadisticas', [EstadisticaController::class, 'index'])->name('estadisticas.index');
    Route::get('/configuracion', [ConfiguracionController::class, 'index'])->name('configuracion.index');
    Route::put('/configuracion/barberia', [ConfiguracionController::class, 'actualizarBarberia'])->name('configuracion.barberia');
    Route::put('/configuracion/usuario', [ConfiguracionController::class, 'actualizarUsuario'])->name('configuracion.usuario');
    Route::put('/configuracion/password', [ConfiguracionController::class, 'actualizarPassword'])->name('configuracion.password');
    Route::post('/logout', [AuthController::class, 'cerrarSesion'])->name('logout');

    Route::view('/pwa/login', 'pwa.login')->name('pwa.login');
    Route::view('/pwa/dashboard', 'pwa.dashboard')->name('pwa.dashboard');
    Route::view('/pwa/clientes', 'pwa.clientes')->name('pwa.clientes');
    Route::view('/pwa/agenda', 'pwa.agenda')->name('pwa.agenda');
    Route::view('/pwa/productos', 'pwa.productos')->name('pwa.productos');
    Route::view('/pwa/usuarios', 'pwa.usuarios')->name('pwa.usuarios');
    Route::view('/pwa/citas', 'pwa.citas')->name('pwa.citas');
    Route::view('/pwa/ventas', 'pwa.ventas')->name('pwa.ventas');
    Route::view('/pwa/recompensas', 'pwa.recompensas')->name('pwa.recompensas');
    Route::view('/pwa/estadisticas', 'pwa.estadisticas')->name('pwa.estadisticas');
});