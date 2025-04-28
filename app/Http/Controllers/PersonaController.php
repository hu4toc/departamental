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
        $personas = Persona::orderBy('id', 'desc')->get();
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

    public function update($id, PersonaRequest $request)
    {
        $persona = Persona::find($id);

        if (!$persona) {
            return response()->json([
                'success' => false,
                'message' => 'Persona no encontrada'
            ], 404);
        }

        $input = $request->all();
        // return response()->json($input);
        $input['estado'] = 'ACTIVO';
        $input['fecha_actualizacion'] = Carbon::now();

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            // Guardar en storage/app/departamental
            $file->storeAs('departamental', $filename);

            $input['foto'] = $filename;
        }

        $persona->update($input);

        return response()->json([
            'success' => true,
            'message' => 'Persona actualizada correctamente.',
        ], 201);
    }

    public function mostrarPersonaPorId($id) {
        $persona = Persona::find($id);

        if (!$persona || !$persona->foto) {
            return response()->json(['message' => 'Imagen no encontrada'], 404);
        }

        $path = 'departamental/' . $persona->foto;

        if (!Storage::disk('local')->exists($path)) {
            return response()->json(['message' => 'Archivo no encontrado en el servidor'], 404);
        }

        $file = Storage::get($path);
        $mimeType = Storage::mimeType($path);

        return response($file, 200)->header('Content-Type', $mimeType);
    }
}
