<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $table = 'servicios';
    protected $primaryKey = 'id_servicio';

    protected $fillable = [
        'id_barberia',
        'nombre',
        'descripcion',
        'precio',
        'duracion_minutos',
        'activo',
    ];

    public function barberia()
    {
        return $this->belongsTo(Barberia::class, 'id_barberia', 'id_barberia');
    }

    public function citas()
    {
        return $this->hasMany(Cita::class, 'id_servicio', 'id_servicio');
    }
}