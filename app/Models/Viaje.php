<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Viaje extends Model
{
    protected $table = 'viaje';
    protected $primaryKey = 'id_viaje';
    public $timestamps = false;

    protected $fillable = [
        'id_pasajero', 'id_conductor', 'id_tarifa',
        'origen_descripcion', 'origen_lat', 'origen_lng',
        'destino_descripcion', 'destino_lat', 'destino_lng',
        'fecha_solicitud', 'fecha_inicio', 'fecha_fin',
        'estado', 'distancia_km', 'duracion_minutos',
        'tarifa_estimada', 'tarifa_final',
        'cancelado_por', 'razon_cancelacion'
    ];

    public function pasajero() { return $this->belongsTo(Pasajero::class, 'id_pasajero', 'id_pasajero'); }
    public function conductor() { return $this->belongsTo(Conductor::class, 'id_conductor', 'id_conductor'); }
    public function tarifa() { return $this->belongsTo(Tarifa::class, 'id_tarifa', 'id_tarifa'); }
    public function pago() { return $this->hasOne(Pago::class, 'id_viaje', 'id_viaje'); }
}
