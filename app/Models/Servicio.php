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
        'imagen',
        'precio',
        'duracion_minutos',
        'activo',
        'mostrar_landing',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
            'mostrar_landing' => 'boolean',
            'precio' => 'decimal:2',
        ];
    }

    public function barberia()
    {
        return $this->belongsTo(Barberia::class, 'id_barberia', 'id_barberia');
    }

    public function citas()
    {
        return $this->hasMany(Cita::class, 'id_servicio', 'id_servicio');
    }
}
