<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    protected $table = 'evaluacion';
    public $timestamps = false;

    protected $fillable = [
        'puntos',
        'fecha_evaluacion',
        'id_proyecto',
        'id_indicador',
        'id_persona',
        'estado',
        'fecha_registro',
        'registrado_por',
        'fecha_actualizacion',
        'actualizado_por',
    ];
}
