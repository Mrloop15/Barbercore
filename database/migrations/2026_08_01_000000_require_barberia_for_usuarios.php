<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropForeign('fk_usuarios_barberias');
        });

        Schema::table('usuarios', function (Blueprint $table) {
            $table->unsignedBigInteger('id_barberia')->nullable(false)->change();
            $table->foreign('id_barberia', 'fk_usuarios_barberias')
                ->references('id_barberia')->on('barberias')
                ->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropForeign('fk_usuarios_barberias');
        });

        Schema::table('usuarios', function (Blueprint $table) {
            $table->unsignedBigInteger('id_barberia')->nullable()->change();
            $table->foreign('id_barberia', 'fk_usuarios_barberias')
                ->references('id_barberia')->on('barberias')
                ->nullOnDelete()->cascadeOnUpdate();
        });
    }
};
