<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudConductor extends Model
{
    protected $table = 'solicitud_conductor';
    protected $primaryKey = 'id_solicitud';
    public $timestamps = false;

    protected $fillable = ['nombre_completo', 'telefono', 'email', 'mensaje', 'estado'];
}
