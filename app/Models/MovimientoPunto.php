<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoPunto extends Model
{
    protected $table = 'movimientos_puntos';
    protected $primaryKey = 'id_movimiento';

    public $timestamps = false;

    protected $fillable = [
        'id_barberia',
        'id_cliente',
        'tipo',
        'puntos',
        'motivo',
        'referencia',
        'created_at',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }
}