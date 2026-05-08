<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarifa extends Model
{
    protected $table = 'tarifa';
    protected $primaryKey = 'id_tarifa';
    public $timestamps = false;
    protected $fillable = ['tipo_servicio', 'tarifa_base', 'costo_por_km', 'costo_por_minuto', 'tarifa_minima'];
}
