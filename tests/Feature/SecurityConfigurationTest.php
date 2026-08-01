<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class SecurityConfigurationTest extends TestCase
{
    public function test_web_and_api_logins_are_rate_limited(): void
    {
        $webLogin = Route::getRoutes()->getByName('login.post');
        $apiLogin = collect(Route::getRoutes()->getRoutes())
            ->first(fn ($route) => $route->uri() === 'api/login');

        $this->assertContains('throttle:5,1', $webLogin->gatherMiddleware());
        $this->assertContains('throttle:5,1', $apiLogin->gatherMiddleware());
    }

    public function test_sensitive_web_routes_require_admin_role(): void
    {
        foreach (['usuarios.index', 'servicios.index', 'productos.index', 'ventas-productos.index', 'recompensas.index', 'estadisticas.index', 'configuracion.index'] as $name) {
            $route = Route::getRoutes()->getByName($name);
            $this->assertNotNull($route, "No existe la ruta {$name}.");
            $this->assertContains('role:admin', $route->gatherMiddleware(), "{$name} no exige rol admin.");
            $this->assertContains('tenant', $route->gatherMiddleware(), "{$name} no valida el tenant.");
        }
    }

    public function test_sanctum_tokens_expire(): void
    {
        $this->assertSame(60, config('sanctum.expiration'));
    }
}
