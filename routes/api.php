<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Controllers\Api\ClienteApiController;
use App\Http\Controllers\Api\ServicioApiController;
use App\Http\Controllers\Api\ProductoApiController;
use App\Http\Controllers\Api\CitaApiController;
use App\Http\Controllers\Api\AgendaApiController;
use App\Http\Controllers\Api\VentaProductoApiController;
use App\Http\Controllers\Api\RecompensaApiController;
use App\Http\Controllers\Api\EstadisticaApiController;
use App\Http\Controllers\Api\UsuarioApiController;

Route::post('/login', [AuthApiController::class, 'login']);

Route::get('/status', function () {
    return response()->json([
        'ok' => true,
        'message' => 'API BarberCore funcionando correctamente.',
        'version' => '1.0.0',
        'timestamp' => now(),
    ]);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthApiController::class, 'me']);
    Route::post('/logout', [AuthApiController::class, 'logout']);

    Route::get('/dashboard/resumen', [DashboardApiController::class, 'resumen']);

    Route::name('api.')->group(function () {
    Route::get('/clientes/inactivos', [ClienteApiController::class, 'inactivos'])->name('clientes.inactivos');
    Route::apiResource('/clientes', ClienteApiController::class);
    Route::get('/usuarios', [UsuarioApiController::class, 'index']);
    Route::post('/usuarios', [UsuarioApiController::class, 'store']);
    Route::get('/usuarios/{id}', [UsuarioApiController::class, 'show']);
    Route::put('/usuarios/{id}', [UsuarioApiController::class, 'update']);
    Route::patch('/usuarios/{id}/estado', [UsuarioApiController::class, 'cambiarEstado']);
});

    Route::get('/servicios', [ServicioApiController::class, 'index']);
    Route::post('/servicios', [ServicioApiController::class, 'store']);
    Route::get('/servicios/{id}', [ServicioApiController::class, 'show']);
    Route::put('/servicios/{id}', [ServicioApiController::class, 'update']);
    Route::delete('/servicios/{id}', [ServicioApiController::class, 'destroy']);

    Route::get('/productos/bajo-stock', [ProductoApiController::class, 'bajoStock']);
    Route::get('/productos', [ProductoApiController::class, 'index']);
    Route::post('/productos', [ProductoApiController::class, 'store']);
    Route::get('/productos/{id}', [ProductoApiController::class, 'show']);
    Route::put('/productos/{id}', [ProductoApiController::class, 'update']);
    Route::delete('/productos/{id}', [ProductoApiController::class, 'destroy']);
    Route::patch('/productos/{id}/stock', [ProductoApiController::class, 'actualizarStock']);

    Route::get('/ventas-productos', [VentaProductoApiController::class, 'index']);
    Route::post('/ventas-productos', [VentaProductoApiController::class, 'store']);
    Route::get('/ventas-productos/{id}', [VentaProductoApiController::class, 'show']);

    Route::get('/recompensas', [RecompensaApiController::class, 'index']);
    Route::post('/recompensas', [RecompensaApiController::class, 'store']);
    Route::post('/recompensas/canjear', [RecompensaApiController::class, 'canjear']);
    Route::get('/recompensas/{id}', [RecompensaApiController::class, 'show']);
    Route::put('/recompensas/{id}', [RecompensaApiController::class, 'update']);
    Route::delete('/recompensas/{id}', [RecompensaApiController::class, 'destroy']);

    Route::get('/estadisticas/ingresos', [EstadisticaApiController::class, 'ingresos']);
    Route::get('/estadisticas/servicios', [EstadisticaApiController::class, 'servicios']);
    Route::get('/estadisticas/clientes', [EstadisticaApiController::class, 'clientes']);
    Route::get('/estadisticas/productos', [EstadisticaApiController::class, 'productos']);

    Route::get('/citas', [CitaApiController::class, 'index']);
    Route::post('/citas', [CitaApiController::class, 'store']);
    Route::put('/citas/{id}/cancelar', [CitaApiController::class, 'cancelar']);
    Route::put('/citas/{id}/completar', [CitaApiController::class, 'completar']);
    Route::get('/agenda/dia', [AgendaApiController::class, 'dia']);
    Route::get('/agenda/semana', [AgendaApiController::class, 'semana']);
    Route::get('/agenda/mes', [AgendaApiController::class, 'mes']);
});