<?php

namespace Tests\Feature;

use App\Models\Servicio;
use App\Models\Usuario;
use Carbon\CarbonImmutable;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AppointmentAvailabilityTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->unsignedBigInteger('id_barberia');
            $table->string('nombre');
            $table->string('correo');
            $table->string('password');
            $table->string('rol');
            $table->boolean('activo')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('servicios', function (Blueprint $table) {
            $table->id('id_servicio');
            $table->unsignedBigInteger('id_barberia');
            $table->string('nombre');
            $table->decimal('precio', 10, 2);
            $table->unsignedInteger('duracion_minutos');
            $table->boolean('activo')->default(true);
            $table->boolean('mostrar_landing')->default(false);
            $table->timestamps();
        });

        Schema::create('horarios_atencion', function (Blueprint $table) {
            $table->id('id_horario');
            $table->unsignedBigInteger('id_barberia');
            $table->unsignedTinyInteger('dia_semana');
            $table->boolean('abierto')->default(false);
            $table->time('hora_apertura')->nullable();
            $table->time('hora_cierre')->nullable();
            $table->timestamps();
        });

        Schema::create('citas', function (Blueprint $table) {
            $table->id('id_cita');
            $table->unsignedBigInteger('id_barberia');
            $table->unsignedBigInteger('id_cliente')->nullable();
            $table->unsignedBigInteger('id_servicio');
            $table->unsignedBigInteger('id_barbero');
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->decimal('precio', 10, 2);
            $table->string('estado');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        CarbonImmutable::setTestNow();

        Schema::dropIfExists('citas');
        Schema::dropIfExists('horarios_atencion');
        Schema::dropIfExists('servicios');
        Schema::dropIfExists('usuarios');

        parent::tearDown();
    }

    public function test_it_returns_available_intervals_for_an_immutable_business_date(): void
    {
        CarbonImmutable::setTestNow(CarbonImmutable::parse('2026-08-13 21:20:00', 'America/Mexico_City'));

        $user = Usuario::query()->create([
            'id_barberia' => 1,
            'nombre' => 'Barbero de prueba',
            'correo' => 'barbero@example.test',
            'password' => bcrypt('password'),
            'rol' => 'admin',
            'activo' => true,
        ]);

        $service = Servicio::query()->create([
            'id_barberia' => 1,
            'nombre' => 'Servicio de prueba',
            'precio' => 100,
            'duracion_minutos' => 30,
            'activo' => true,
        ]);

        DB::table('horarios_atencion')->insert([
            'id_barberia' => 1,
            'dia_semana' => 3,
            'abierto' => true,
            'hora_apertura' => '09:00:00',
            'hora_cierre' => '23:00:00',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($user)->getJson(route('citas.disponibilidad', [
            'fecha' => '2026-08-13',
            'id_barbero' => $user->id_usuario,
            'id_servicio' => $service->id_servicio,
        ]));

        $response
            ->assertOk()
            ->assertJsonPath('apertura', '09:00')
            ->assertJsonPath('cierre', '23:00')
            ->assertJsonPath('horarios.0', '21:30')
            ->assertJsonPath('horarios.4', '22:30')
            ->assertJsonCount(5, 'horarios');
    }

    public function test_service_worker_does_not_cache_dynamic_availability_requests(): void
    {
        $serviceWorker = file_get_contents(public_path('sw.js'));
        $networkOnlyFetch = strpos($serviceWorker, 'if (request.destination === "")');
        $cacheLookup = strpos($serviceWorker, 'caches.match(request)');

        $this->assertNotFalse($networkOnlyFetch);
        $this->assertNotFalse($cacheLookup);
        $this->assertLessThan($cacheLookup, $networkOnlyFetch);
    }
}
