<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Console\Command\Command;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('barbercore:ensure-sales-audit', function () {
    if (! Schema::hasTable('ventas_productos') || ! Schema::hasTable('usuarios')) {
        $this->error('No se encontraron las tablas necesarias para la auditoria de ventas.');

        return Command::FAILURE;
    }

    if (Schema::hasColumn('ventas_productos', 'id_usuario')) {
        $this->info('La auditoria de ventas ya esta preparada.');

        return Command::SUCCESS;
    }

    Schema::table('ventas_productos', function ($table) {
        $table->unsignedBigInteger('id_usuario')->nullable()->after('id_cliente');
        $table->foreign('id_usuario', 'ventas_productos_id_usuario_foreign')
            ->references('id_usuario')
            ->on('usuarios')
            ->nullOnDelete()
            ->cascadeOnUpdate();
    });

    $this->info('Auditoria de ventas preparada. Las nuevas ventas guardaran al usuario que las registro.');

    return Command::SUCCESS;
})->purpose('Prepara de forma segura la trazabilidad de las ventas de productos');
