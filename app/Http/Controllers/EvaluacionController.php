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

        // Validacion que ingrese puntajes de todos los indicadores
        $clavesRequeridas = ['1', '2', '3', '4', '5'];
        $clavesValidas = count(array_diff($clavesRequeridas, array_keys($puntos))) === 0;
        $valoresValidos = collect($puntos)->every(fn($valor) => $valor > 0);

        if (!$clavesValidas || !$valoresValidos) {
            return response()->json([
                'success' => false,
                'message' => 'Debe ingresar los puntos de todos los indicadores.'
            ], 422);
        }

        // Validacion si ya existen calificaciones de la persona
        $calificaciones = Evaluacion::where('id_persona', $id_persona)
            ->where('id_proyecto', $id_proyecto)
            ->count();

        if ($calificaciones > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Ya se han ingresado calificaciones para esta persona.',
            ], 201);
        }

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
