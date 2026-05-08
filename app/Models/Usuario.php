<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuario';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'nombre_usuario', 'hash_contrasena', 'nombre_completo',
        'email', 'telefono', 'activo', 'rol'
    ];

    protected $hidden = ['hash_contrasena', 'remember_token'];

    // Laravel usa 'password' para auth, mapeamos a hash_contrasena
    public function getAuthPassword()
    {
        return $this->hash_contrasena;
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['hash_contrasena'] = Hash::make($value);
    }

    // Relaciones
    public function pasajero()
    {
        return $this->hasOne(Pasajero::class, 'id_usuario', 'id_usuario');
    }

    public function conductor()
    {
        return $this->hasOne(Conductor::class, 'id_usuario', 'id_usuario');
    }

    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class, 'id_usuario', 'id_usuario');
    }

    // Helpers de rol
    public function esAdmin() { return $this->rol === 'admin'; }
    public function esConductor() { return $this->rol === 'conductor'; }
    public function esPasajero() { return $this->rol === 'pasajero'; }
}
