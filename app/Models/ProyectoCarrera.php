<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProyectoCarrera extends Model
{
    protected $table = 'proyectos_carreras';
    public $timestamps = false;

    protected $fillable = [
        'id_proyecto',
        'id_carrera',
        'fecha_registro',
    ];
}
