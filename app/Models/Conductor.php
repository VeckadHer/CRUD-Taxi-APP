<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conductor extends Model
{
    protected $table = 'conductor';
    protected $primaryKey = 'id_conductor';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario', 'id_empresa', 'licencia_conducir',
        'calificacion_promedio', 'disponible', 'estado',
        'lat_actual', 'lng_actual', 'tipo_vehiculo_operar'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'id_empresa', 'id_empresa');
    }

    public function viajes()
    {
        return $this->hasMany(Viaje::class, 'id_conductor', 'id_conductor');
    }
}
