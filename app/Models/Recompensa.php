<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recompensa extends Model
{
    protected $table = 'recompensas';
    protected $primaryKey = 'id_recompensa';

    protected $fillable = [
        'id_barberia',
        'nombre',
        'descripcion',
        'puntos_requeridos',
        'tipo',
        'valor',
        'activo',
    ];

    public function canjes()
    {
        return $this->hasMany(CanjeRecompensa::class, 'id_recompensa', 'id_recompensa');
    }
}