<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PersonaProyecto;
use App\Http\Requests\PersonaProyectoRequest;
use DB;
use Carbon\Carbon;

class PersonaProyectoController extends Controller
{
    public function store(PersonaProyectoRequest $request)
    {
        $input = $request->all();
        $input['estado'] = 'ACTIVO';
        $input['fecha_registro'] = Carbon::now();

        PersonaProyecto::create($input);

        return response()->json([
            'success' => true,
            'message' => 'Integrante agregado correctamente',
        ], 201);
    }
}
