<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'personas';
    public $timestamps = false;

    protected $fillable = [
        'nombres',
        'apellidos',
        'ci',
        'ci_complemento',
        'celular',
        'correo_electronico',
        'fecha_nacimiento',
        'sexo',
        'tipo',
        'imagen',
        'estado',
        'fecha_registro',
        'registrado_por',
        'fecha_actualizacion',
        'actualizado_por',
        'foto'
    ];
}
