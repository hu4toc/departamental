<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Indicador extends Model
{
    protected $table = 'indicadores';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'puntaje_mayor',
        'estado',
        'categoria',
    ];
}
