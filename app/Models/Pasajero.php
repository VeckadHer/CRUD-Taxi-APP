<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasajero extends Model
{
    protected $table = 'pasajero';
    protected $primaryKey = 'id_pasajero';
    public $timestamps = false;
    
    protected $fillable = ['id_usuario', 'metodo_pago_default', 'calificacion_promedio'];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function viajes()
    {
        return $this->hasMany(Viaje::class, 'id_pasajero', 'id_pasajero');
    }
}
