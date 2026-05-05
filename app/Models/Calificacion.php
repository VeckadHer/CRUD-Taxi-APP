<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model
{
    protected $table = 'calificacion';
    protected $primaryKey = 'id_calificacion';
    public $timestamps = false;
    
    protected $fillable = ['id_viaje', 'id_evaluador', 'id_evaluado', 'puntuacion', 'comentario', 'fecha', 'tipo'];

    public function viaje()
    {
        return $this->belongsTo(Viaje::class, 'id_viaje', 'id_viaje');
    }
}
