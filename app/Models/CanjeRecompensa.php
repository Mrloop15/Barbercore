<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CanjeRecompensa extends Model
{
    protected $table = 'canjes_recompensas';
    protected $primaryKey = 'id_canje';

    protected $fillable = [
        'id_barberia',
        'id_cliente',
        'id_recompensa',
        'puntos_usados',
        'fecha_canje',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    public function recompensa()
    {
        return $this->belongsTo(Recompensa::class, 'id_recompensa', 'id_recompensa');
    }
}