<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuario';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;
    
    protected $fillable = ['nombre_usuario', 'hash_contrasena', 'nombre_completo', 'email', 'telefono', 'activo'];

    public function pasajero()
    {
        return $this->hasOne(Pasajero::class, 'id_usuario', 'id_usuario');
    }

    public function conductor()
    {
        return $this->hasOne(Conductor::class, 'id_usuario', 'id_usuario');
    }
}