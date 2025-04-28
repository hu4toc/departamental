<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Instituto;

class InstitutoController extends Controller
{
    public function index()
    {
        $institutos = Instituto::all();
        $totalRegistros = count($institutos);
        

        return response()->json([
            'success' => true, 
            'data' => $institutos, 
            'totalRegistros' => $totalRegistros
        ], 200);
    }

    public function select()
    {
        $institutos = Instituto::select(
                'id as code',
                'nombre as label'
            )->get();

        return response()->json([
            'success' => true, 
            'data' => $institutos
        ], 200);
    }
}
