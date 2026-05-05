<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GpsUbicacion extends Model
{
    protected $table = 'gps_ubicacion';
    protected $primaryKey = 'id_ubicacion';
    public $timestamps = false;
    
    protected $fillable = ['id_conductor', 'latitud', 'longitud', 'fecha_registro'];

    public function conductor()
    {
        return $this->belongsTo(Conductor::class, 'id_conductor', 'id_conductor');
    }
}
