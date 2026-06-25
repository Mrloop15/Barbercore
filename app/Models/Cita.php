<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $table = 'citas';
    protected $primaryKey = 'id_cita';

    protected $fillable = [
        'id_barberia',
        'id_cliente',
        'id_servicio',
        'id_barbero',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'precio',
        'estado',
        'observaciones',
    ];

    public function barberia()
    {
        return $this->belongsTo(Barberia::class, 'id_barberia', 'id_barberia');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'id_servicio', 'id_servicio');
    }

    public function barbero()
    {
        return $this->belongsTo(Usuario::class, 'id_barbero', 'id_usuario');
    }
}