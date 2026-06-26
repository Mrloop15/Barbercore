<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialServicio extends Model
{
    protected $table = 'historial_servicios';
    protected $primaryKey = 'id_historial';

    protected $fillable = [
        'id_barberia',
        'id_cliente',
        'id_cita',
        'id_servicio',
        'precio',
        'fecha_servicio',
        'observaciones',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'id_servicio', 'id_servicio');
    }

    public function cita()
    {
        return $this->belongsTo(Cita::class, 'id_cita', 'id_cita');
    }
}