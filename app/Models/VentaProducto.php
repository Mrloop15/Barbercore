<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VentaProducto extends Model
{
    protected $table = 'ventas_productos';
    protected $primaryKey = 'id_venta';

    protected $fillable = [
        'id_barberia',
        'id_cliente',
        'total',
        'fecha_venta',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleVentaProducto::class, 'id_venta', 'id_venta');
    }
}