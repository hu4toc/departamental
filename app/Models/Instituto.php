<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instituto extends Model
{
    protected $table = 'institutos';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'ubicacion',
        'estado',
        'fecha_registro',
        'registrado_por',
        'fecha_actualizacion',
        'actualizado_por',
    ];
}
