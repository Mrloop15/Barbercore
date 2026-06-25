<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barberia extends Model
{
    protected $table = 'barberias';
    protected $primaryKey = 'id_barberia';

    protected $fillable = [
        'nombre',
        'telefono',
        'direccion',
        'logo',
        'activo',
    ];

    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'id_barberia', 'id_barberia');
    }

    public function clientes()
    {
        return $this->hasMany(Cliente::class, 'id_barberia', 'id_barberia');
    }

    public function servicios()
    {
        return $this->hasMany(Servicio::class, 'id_barberia', 'id_barberia');
    }

    public function productos()
    {
        return $this->hasMany(Producto::class, 'id_barberia', 'id_barberia');
    }

    public function citas()
    {
        return $this->hasMany(Cita::class, 'id_barberia', 'id_barberia');
    }
}