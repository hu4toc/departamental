<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    protected $table = 'proyectos';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'portada',
        'estado',
        'documento',
        'fecha_registro',
        'registrado_por',
        'fecha_actualizacion',
        'actualizado_por',
        'id_instituto',
        'id_carrera',
    ];
}
