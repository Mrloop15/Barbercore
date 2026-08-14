<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class VentaProducto extends Model
{
    protected $table = 'ventas_productos';

    protected $primaryKey = 'id_venta';

    protected $fillable = [
        'id_barberia',
        'id_cliente',
        'id_usuario',
        'total',
        'fecha_venta',
    ];

    protected $appends = ['vendedor_nombre'];

    protected function casts(): array
    {
        return [
            'fecha_venta' => 'datetime',
        ];
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleVentaProducto::class, 'id_venta', 'id_venta');
    }

    public function vendedor()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public static function supportsVendedor(): bool
    {
        return Schema::connection((new static)->getConnectionName())
            ->hasColumn((new static)->getTable(), 'id_usuario');
    }

    public function scopeWithVendedor(Builder $query): Builder
    {
        return static::supportsVendedor() ? $query->with('vendedor') : $query;
    }

    public function getVendedorNombreAttribute(): string
    {
        if (! $this->relationLoaded('vendedor')) {
            return 'No registrado';
        }

        return $this->vendedor?->nombre ?? 'No registrado';
    }
}
