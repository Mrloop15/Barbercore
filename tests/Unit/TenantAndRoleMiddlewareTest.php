<?php

namespace Tests\Unit;

use App\Http\Middleware\EnsureTenantAssigned;
use App\Http\Middleware\RequireRole;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class TenantAndRoleMiddlewareTest extends TestCase
{
    public function test_user_without_tenant_is_rejected(): void
    {
        $request = Request::create('/dashboard');
        $request->setUserResolver(fn () => (object) ['id_barberia' => null, 'rol' => 'admin']);

        $this->expectException(HttpException::class);
        (new EnsureTenantAssigned())->handle($request, fn () => response('ok'));
    }

    public function test_non_admin_user_is_rejected_from_admin_route(): void
    {
        $request = Request::create('/configuracion');
        $request->setUserResolver(fn () => (object) ['id_barberia' => 1, 'rol' => 'barbero']);

        $this->expectException(HttpException::class);
        (new RequireRole())->handle($request, fn () => response('ok'), 'admin');
    }

    public function test_admin_with_tenant_is_allowed(): void
    {
        $request = Request::create('/configuracion');
        $request->setUserResolver(fn () => (object) ['id_barberia' => 1, 'rol' => 'admin']);

        $response = (new EnsureTenantAssigned())->handle(
            $request,
            fn ($request) => (new RequireRole())->handle($request, fn () => response('ok'), 'admin')
        );

        $this->assertSame(200, $response->getStatusCode());
    }
}
