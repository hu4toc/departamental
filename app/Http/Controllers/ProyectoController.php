<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;

use DB;

class ProyectoController extends Controller
{
    public function index()
    {
        $proyectos = Proyecto::select(
            'proyectos.id',
            'proyectos.nombre',
            'proyectos.descripcion',
            'institutos.nombre as instituto',
            'carreras.nombre as carrera'
        )
        ->join('institutos', 'institutos.id', '=', 'proyectos.id_instituto')
        ->join('carreras', 'carreras.id', '=', 'proyectos.id_carrera')
        ->get();

        $totalRegistros = count($proyectos);
        

        return response()->json([
            'success' => true, 
            'data' => $proyectos, 
            'totalRegistros' => $totalRegistros
        ], 200);
    }

    public function show($id)
    {
        $proyectos = Proyecto::select(
            'proyectos.id',
            'proyectos.nombre',
            'proyectos.descripcion',
            'institutos.nombre as instituto',
            'carreras.nombre as carrera'
        )
        ->join('institutos', 'institutos.id', '=', 'proyectos.id_instituto')
        ->join('carreras', 'carreras.id', '=', 'proyectos.id_carrera')
        ->where('proyectos.id', $id)
        ->first();
        

        return response()->json([
            'success' => true, 
            'data' => $proyectos, 
        ], 200);
    }

    public function integrantes($id)
    {
        $personas = Proyecto::select(
            'personas.id',
            DB::raw('UPPER(personas.nombres) as nombres'),
            DB::raw('UPPER(personas.apellidos) as apellidos'),
            DB::raw('UPPER(personas_proyectos.tipo) as tipo')
        )
        ->join('personas_proyectos', 'personas_proyectos.id_proyecto', '=', 'proyectos.id')
        ->join('personas', 'personas.id', '=', 'personas_proyectos.id_persona')
        ->where('proyectos.id', $id)
        ->orderBy('personas_proyectos.tipo', 'desc')
        ->get();
        

        return response()->json([
            'success' => true, 
            'data' => $personas, 
        ], 200);
    }

    public function calificaciones($id)
    {
        $datos = Proyecto::select(
            DB::raw('UPPER(CONCAT(personas.nombres, " ", personas.apellidos)) as nombre'),
            'indicadores.categoria',
            'evaluacion.puntos'
        )
        ->join('evaluacion', 'evaluacion.id_proyecto', '=', 'proyectos.id')
        ->join('personas', 'personas.id', '=', 'evaluacion.id_persona')
        ->join('indicadores', 'indicadores.id', '=', 'evaluacion.id_indicador')
        ->where('proyectos.id', $id)
        ->get();

        // Aquí agrupamos las calificaciones
        $calificaciones = $datos->groupBy('nombre')->map(function ($items, $nombre) {
            $result = ['nombre' => $nombre];
            $total = 0;

            foreach ($items as $item) {
                $puntos = (int) $item->puntos;
                $result[$item->categoria] = $puntos;
                $total += $puntos;
            }

            $result['Total'] = $total;

            return $result;
        })->values();

        $totalGeneral = $calificaciones->sum('Total');
        $nroRegistros = $calificaciones->count();
        $puntajeFinal = $nroRegistros > 0 ? round($totalGeneral / $nroRegistros, 2) : 0;

        return response()->json([
            'success' => true, 
            'data' => $calificaciones, 
            'puntaje' => $puntajeFinal
        ], 200);
    }
}
