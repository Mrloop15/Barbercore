<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            $table->string('imagen')->nullable()->after('descripcion');
            $table->boolean('mostrar_landing')->default(true)->after('activo')->index();
        });
    }

    public function down(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            $table->dropIndex(['mostrar_landing']);
            $table->dropColumn(['imagen', 'mostrar_landing']);
        });
    }
};
