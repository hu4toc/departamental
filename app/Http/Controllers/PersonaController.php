<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Http\Requests\PersonaRequest;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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

    public function store(PersonaRequest $request)
    {
        $input = $request->all();
        
        $input['estado'] = 'ACTIVO';
        $input['fecha_registro'] = Carbon::now();

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            // Guardar en storage/app/departamental
            $file->storeAs('departamental', $filename);

            $input['foto'] = $filename;
        }

        Persona::create($input);

        return response()->json([
            'success' => true,
            'message' => 'Persona creada correctamente.',
        ], 201);
    }
}
