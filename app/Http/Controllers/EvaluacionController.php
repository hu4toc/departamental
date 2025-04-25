<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evaluacion;

class EvaluacionController extends Controller
{
    public function store(Request $request)
    {
        $id_persona = $request->id_persona;
        $id_proyecto = $request->id_proyecto;
        $puntos = $request->puntos;

        foreach ($puntos as $indicador => $calificacion) {
            $evaluacion = new Evaluacion;

            $evaluacion->id_indicador = $indicador;
            $evaluacion->puntos = $calificacion;
            $evaluacion->id_persona = $id_persona;
            $evaluacion->id_proyecto = $id_proyecto;
            $evaluacion->estado = 'ACTIVO';

            $evaluacion->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Evaluacion registrada correctamente',
        ], 201);
    }
}
