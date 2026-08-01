<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barberias', function (Blueprint $table) {
            $table->string('google_maps_url', 500)->nullable()->after('direccion');
        });

        Schema::create('horarios_atencion', function (Blueprint $table) {
            $table->id('id_horario');
            $table->unsignedBigInteger('id_barberia');
            $table->unsignedTinyInteger('dia_semana');
            $table->boolean('abierto')->default(true);
            $table->time('hora_apertura')->nullable();
            $table->time('hora_cierre')->nullable();
            $table->timestamps();

            $table->unique(['id_barberia', 'dia_semana']);
            $table->foreign('id_barberia')->references('id_barberia')->on('barberias')->cascadeOnDelete();
        });

        Schema::create('preguntas_frecuentes', function (Blueprint $table) {
            $table->id('id_pregunta');
            $table->unsignedBigInteger('id_barberia');
            $table->string('pregunta', 255);
            $table->text('respuesta');
            $table->unsignedSmallInteger('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index(['id_barberia', 'activo', 'orden']);
            $table->foreign('id_barberia')->references('id_barberia')->on('barberias')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preguntas_frecuentes');
        Schema::dropIfExists('horarios_atencion');

        Schema::table('barberias', function (Blueprint $table) {
            $table->dropColumn('google_maps_url');
        });
    }
};
