<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carrera;

class CarreraController extends Controller
{
    public function index()
    {
        $carreras = Carrera::all();
        $totalRegistros = count($carreras);
        

        return response()->json([
            'success' => true, 
            'data' => $carreras, 
            'totalRegistros' => $totalRegistros
        ], 200);
    }

    public function select()
    {
        $carreras = Carrera::select(
            'id as code',
            'nombre as label'
        )->get();

        return response()->json([
            'success' => true, 
            'data' => $carreras
        ], 200);
    }
}
