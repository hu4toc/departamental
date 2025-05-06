<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Instituto;
use App\Http\Requests\InstitutoRequest;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class InstitutoController extends Controller
{
    public function index()
    {
        $institutos = Instituto::orderBy('id', 'desc')->get();
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

    public function store(InstitutoRequest $request)
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

        Instituto::create($input);

        return response()->json([
            'success' => true,
            'message' => 'Instituto creado correctamente.',
        ], 201);
    }

    public function update($id, InstitutoRequest $request)
    {
        $instituto = Instituto::find($id);

        if (!$instituto) {
            return response()->json([
                'success' => false,
                'message' => 'Instituto no encontrado'
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

        $instituto->update($input);

        return response()->json([
            'success' => true,
            'message' => 'Instituto actualizado correctamente.',
        ], 201);
    }

    public function mostrarInstitutoPorId($id) {
        $instituto = Instituto::find($id);

        if (!$instituto || !$instituto->foto) {
            return response()->json(['message' => 'Imagen no encontrada'], 404);
        }

        $path = 'departamental/' . $instituto->foto;

        if (!Storage::disk('local')->exists($path)) {
            return response()->json(['message' => 'Archivo no encontrado en el servidor'], 404);
        }

        $file = Storage::get($path);
        $mimeType = Storage::mimeType($path);

        return response($file, 200)->header('Content-Type', $mimeType);
    }
}
