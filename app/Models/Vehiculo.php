<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    protected $table = 'vehiculo';
    protected $primaryKey = 'id_vehiculo';
    public $timestamps = false;
    
    protected $fillable = ['id_conductor', 'placa', 'marca', 'modelo', 'anio', 'color', 'tipo_vehiculo'];

    public function conductor()
    {
        return $this->belongsTo(Conductor::class, 'id_conductor', 'id_conductor');
    }
}
