<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conductor extends Model
{
    protected $table = 'conductor';
    protected $primaryKey = 'id_conductor';
    public $timestamps = false;
    
    protected $fillable = ['id_usuario', 'licencia_conducir', 'calificacion_promedio', 'disponible', 'estado'];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function vehiculos()
    {
        return $this->hasMany(Vehiculo::class, 'id_conductor', 'id_conductor');
    }

    public function viajes()
    {
        return $this->hasMany(Viaje::class, 'id_conductor', 'id_conductor');
    }

    public function ubicaciones()
    {
        return $this->hasMany(GpsUbicacion::class, 'id_conductor', 'id_conductor');
    }
}