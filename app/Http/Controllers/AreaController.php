<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;

class AreaController extends Controller
{
    public function select()
    {
        $carreras = Area::select(
            'id as code',
            'descripcion as label'
        )->get();

        return response()->json([
            'success' => true, 
            'data' => $carreras
        ], 200);
    }
}
