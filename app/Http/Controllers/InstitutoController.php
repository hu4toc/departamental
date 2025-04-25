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
}
