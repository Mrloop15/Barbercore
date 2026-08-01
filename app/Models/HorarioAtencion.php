<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HorarioAtencion extends Model
{
    protected $table = 'horarios_atencion';
    protected $primaryKey = 'id_horario';

    protected $fillable = [
        'id_barberia',
        'dia_semana',
        'abierto',
        'hora_apertura',
        'hora_cierre',
    ];

    protected function casts(): array
    {
        return [
            'dia_semana' => 'integer',
            'abierto' => 'boolean',
        ];
    }

    public function barberia()
    {
        return $this->belongsTo(Barberia::class, 'id_barberia', 'id_barberia');
    }
}
