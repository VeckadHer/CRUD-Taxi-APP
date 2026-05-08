<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresa';
    protected $primaryKey = 'id_empresa';
    public $timestamps = false;

    protected $fillable = ['nombre', 'razon_social', 'telefono', 'direccion', 'activa'];

    public function conductores()
    {
        return $this->hasMany(Conductor::class, 'id_empresa', 'id_empresa');
    }
}
