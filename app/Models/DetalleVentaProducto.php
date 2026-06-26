<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleVentaProducto extends Model
{
    protected $table = 'detalle_venta_productos';
    protected $primaryKey = 'id_detalle';

    protected $fillable = [
        'id_venta',
        'id_producto',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ];

    public function venta()
    {
        return $this->belongsTo(VentaProducto::class, 'id_venta', 'id_venta');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }
}