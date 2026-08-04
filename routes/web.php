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
Route::get('/recompensas/consultar', [LandingController::class, 'consultarRecompensas'])->middleware('throttle:5,1')->name('landing.recompensas.consultar');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'mostrarLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'iniciarSesion'])->middleware('throttle:5,1')->name('login.post');
});

// Conserva los accesos de la PWA anterior, pero los lleva a la interfaz web completa.
Route::redirect('/pwa/login', '/login')->name('pwa.login');
Route::redirect('/pwa/dashboard', '/dashboard')->name('pwa.dashboard');
Route::redirect('/pwa/clientes', '/clientes')->name('pwa.clientes');
Route::redirect('/pwa/agenda', '/agenda')->name('pwa.agenda');
Route::redirect('/pwa/productos', '/productos')->name('pwa.productos');
Route::redirect('/pwa/usuarios', '/usuarios')->name('pwa.usuarios');
Route::redirect('/pwa/citas', '/citas')->name('pwa.citas');
Route::redirect('/pwa/ventas', '/ventas-productos')->name('pwa.ventas');
Route::redirect('/pwa/recompensas', '/recompensas')->name('pwa.recompensas');
Route::redirect('/pwa/estadisticas', '/estadisticas')->name('pwa.estadisticas');

Route::middleware(['auth', 'idle', 'tenant'])->group(function () {
    Route::post('/session/activity', fn () => response()->noContent())->name('session.activity');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:admin')->group(function () {
        Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
        Route::get('/usuarios/create', [UsuarioController::class, 'create'])->name('usuarios.create');
        Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
        Route::get('/usuarios/{usuario}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit');
        Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.update');
        Route::put('/usuarios/{usuario}/estado', [UsuarioController::class, 'cambiarEstado'])->name('usuarios.estado');
    });

    Route::get('/clientes-inactivos', [ClienteController::class, 'inactivos'])->name('clientes.inactivos');
    Route::resource('clientes', ClienteController::class);
    Route::patch('/servicios/{servicio}/landing', [ServicioController::class, 'cambiarVisibilidadLanding'])->middleware('role:admin')->name('servicios.landing');
    Route::resource('servicios', ServicioController::class)->except(['show'])->middleware('role:admin');
    Route::get('/citas/disponibilidad', [CitaController::class, 'disponibilidad'])->name('citas.disponibilidad');
    Route::put('/citas/{cita}/cancelar', [CitaController::class, 'cancelar'])->name('citas.cancelar');
    Route::put('/citas/{cita}/completar', [CitaController::class, 'completar'])->name('citas.completar');
    Route::resource('citas', CitaController::class)->except(['show', 'destroy']);
    Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda.index');
    Route::middleware('role:admin')->group(function () {
        Route::resource('productos', ProductoController::class)->except(['show']);
        Route::resource('ventas-productos', VentaProductoController::class)->only(['index', 'create', 'store', 'show']);
        Route::get('/recompensas-canjear', [RecompensaController::class, 'formCanjear'])->name('recompensas.formCanjear');
        Route::post('/recompensas-canjear', [RecompensaController::class, 'canjear'])->name('recompensas.canjear');
        Route::resource('recompensas', RecompensaController::class)->except(['show']);
        Route::get('/estadisticas', [EstadisticaController::class, 'index'])->name('estadisticas.index');
        Route::get('/estadisticas/pdf', [EstadisticaController::class, 'download'])->name('estadisticas.pdf');
        Route::get('/estadisticas/excel', [EstadisticaController::class, 'downloadExcel'])->name('estadisticas.excel');
        Route::get('/configuracion', [ConfiguracionController::class, 'index'])->name('configuracion.index');
        Route::get('/configuracion/preguntas-frecuentes', [ConfiguracionController::class, 'preguntasFrecuentes'])->name('configuracion.preguntas.index');
        Route::put('/configuracion/barberia', [ConfiguracionController::class, 'actualizarBarberia'])->name('configuracion.barberia');
        Route::put('/configuracion/horarios', [ConfiguracionController::class, 'actualizarHorarios'])->name('configuracion.horarios');
        Route::put('/configuracion/preguntas-frecuentes', [ConfiguracionController::class, 'actualizarPreguntasFrecuentes'])->name('configuracion.preguntas');
        Route::put('/configuracion/usuario', [ConfiguracionController::class, 'actualizarUsuario'])->name('configuracion.usuario');
        Route::put('/configuracion/password', [ConfiguracionController::class, 'actualizarPassword'])->name('configuracion.password');
    });
    Route::post('/logout', [AuthController::class, 'cerrarSesion'])->name('logout');

});
