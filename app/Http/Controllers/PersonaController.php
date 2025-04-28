<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use DB;

class PersonaController extends Controller
{
    public function index()
    {
        $personas = Persona::all();
        $totalRegistros = count($personas);
        

        return response()->json([
            'success' => true, 
            'data' => $personas, 
            'totalRegistros' => $totalRegistros
        ], 200);
    }

    public function docentes()
    {
        $personas = Persona::select(
                'id as code', 
                DB::raw('UPPER(CONCAT(nombres, " ", apellidos)) as label')
            )
            ->where('tipo', '!=', 'alumno')
            ->orderBy('nombres', 'asc')
            ->get();

        return response()->json([
            'success' => true, 
            'data' => $personas, 
        ], 200);
    }

    public function integrantes()
    {
        $personas = Persona::select(
                'id as code', 
                DB::raw('UPPER(CONCAT(nombres, " ", apellidos)) as label')
            )
            ->orderBy('nombres', 'asc')
            ->get();

        return response()->json([
            'success' => true, 
            'data' => $personas, 
        ], 200);
    }
}
