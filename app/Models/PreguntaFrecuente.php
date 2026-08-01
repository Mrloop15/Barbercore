<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreguntaFrecuente extends Model
{
    protected $table = 'preguntas_frecuentes';
    protected $primaryKey = 'id_pregunta';

    protected $fillable = [
        'id_barberia',
        'pregunta',
        'respuesta',
        'orden',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'orden' => 'integer',
            'activo' => 'boolean',
        ];
    }

    public function barberia()
    {
        return $this->belongsTo(Barberia::class, 'id_barberia', 'id_barberia');
    }
}
