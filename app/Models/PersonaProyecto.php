<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonaProyecto extends Model
{
    protected $table = 'personas_proyectos';
    public $timestamps = false;

    protected $fillable = [
        'id_proyecto',
        'id_persona',
        'tipo',
        'estado',
        'fecha_inicio',
        'fecha_final',
        'fecha_registro',
        'registrado_por',
        'fecha_actualizacion',
        'actualizado_por',
    ];
}
