<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('ventas_productos', 'id_usuario')) {
            return;
        }

        Schema::table('ventas_productos', function (Blueprint $table) {
            $table->unsignedBigInteger('id_usuario')->nullable()->after('id_cliente');
            $table->foreign('id_usuario', 'ventas_productos_id_usuario_foreign')
                ->references('id_usuario')
                ->on('usuarios')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('ventas_productos', 'id_usuario')) {
            return;
        }

        Schema::table('ventas_productos', function (Blueprint $table) {
            $table->dropForeign('ventas_productos_id_usuario_foreign');
            $table->dropColumn('id_usuario');
        });
    }
};
