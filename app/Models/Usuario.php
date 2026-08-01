<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';

    protected $fillable = [
        'id_barberia',
        'nombre',
        'correo',
        'password',
        'rol',
        'activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function barberia()
    {
        return $this->belongsTo(Barberia::class, 'id_barberia', 'id_barberia');
    }

    public function citas()
    {
        return $this->hasMany(Cita::class, 'id_barbero', 'id_usuario');
    }

    public function tenantId(): int
    {
        abort_unless($this->id_barberia, 403, 'El usuario no tiene una barbería asignada.');

        return (int) $this->id_barberia;
    }
}
