<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'id_cliente';

    protected $fillable = [
        'id_barberia',
        'nombre',
        'apellido',
        'telefono',
        'cumpleanos',
        'foto',
        'observaciones',
        'puntos',
        'ultima_visita',
        'activo',
    ];

    public function barberia()
    {
        return $this->belongsTo(Barberia::class, 'id_barberia', 'id_barberia');
    }

    public function citas()
    {
        return $this->hasMany(Cita::class, 'id_cliente', 'id_cliente');
    }

    public function nombreCompleto()
    {
        return trim($this->nombre . ' ' . $this->apellido);
    }
}