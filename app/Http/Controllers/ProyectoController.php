<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;

use DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Http\Requests\ProyectoRequest;
use Illuminate\Support\Facades\Storage;

class ProyectoController extends Controller
{
    public function index()
    {
        $proyectos = Proyecto::select(
            'proyectos.id',
            'proyectos.nombre',
            'proyectos.descripcion',
            'proyectos.id_instituto',
            'institutos.nombre as instituto',
            'proyectos.id_carrera',
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
            'carreras.nombre as carrera',
            'proyectos.portada',
            'proyectos.documento'
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

    public function store(ProyectoRequest $request)
    {
        $input = $request->all();
        // return response()->json($input);
        $input['estado'] = 'ACTIVO';
        $input['fecha_registro'] = Carbon::now();

        if ($request->hasFile('portada')) {
            $file = $request->file('portada');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            // Guardar en storage/app/departamental
            $file->storeAs('departamental', $filename);

            $input['portada'] = $filename;
        }

        if ($request->hasFile('documento')) {
            $file = $request->file('documento');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            // Guardar en storage/app/departamental
            $file->storeAs('departamental', $filename);

            $input['documento'] = $filename;
        }

        Proyecto::create($input);

        return response()->json([
            'success' => true,
            'message' => 'Proyecto creado correctamente',
        ], 201);
    }

    public function update($id, ProyectoRequest $request)
    {
        $proyecto = Proyecto::find($id);

        if (!$proyecto) {
            return response()->json([
                'success' => false,
                'message' => 'Portada no encontrada'
            ], 404);
        }

        $input = $request->all();
        // return response()->json($input);
        $input['estado'] = 'ACTIVO';
        $input['fecha_actualizacion'] = Carbon::now();

        if ($request->hasFile('portada')) {
            $file = $request->file('portada');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            // Guardar en storage/app/departamental
            $file->storeAs('departamental', $filename);

            $input['portada'] = $filename;
        }

        if ($request->hasFile('documento')) {
            $file = $request->file('documento');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            // Guardar en storage/app/departamental
            $file->storeAs('departamental', $filename);

            $input['documento'] = $filename;
        }

        $proyecto->update($input);

        return response()->json([
            'success' => true,
            'message' => 'Proyecto actualizado correctamente',
        ], 201);
    }

    public function mostrarPortadaPorId($id)
    {
        $proyecto = Proyecto::find($id);

        if (!$proyecto || !$proyecto->portada) {
            return response()->json(['message' => 'Portada no encontrada'], 404);
        }

        $path = 'departamental/' . $proyecto->portada;

        if (!Storage::disk('local')->exists($path)) {
            return response()->json(['message' => 'Archivo no encontrado en el servidor'], 404);
        }

        $file = Storage::get($path);
        $mimeType = Storage::mimeType($path);

        return response($file, 200)->header('Content-Type', $mimeType);
    }

    public function mostrarDocumentoPorId($id)
    {
        $proyecto = Proyecto::find($id);

        if (!$proyecto || !$proyecto->documento) {
            return response()->json(['message' => 'Documento no encontrado'], 404);
        }

        $path = 'departamental/' . $proyecto->documento;

        if (!Storage::disk('local')->exists($path)) {
            return response()->json(['message' => 'Archivo no encontrado en el servidor'], 404);
        }

        $file = Storage::get($path);

        $mimeType = Storage::mimeType($path);

        if ($mimeType !== 'application/pdf') {
            return response()->json(['message' => 'El archivo no es un documento PDF'], 400);
        }

        return response($file, 200)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline; filename="' . basename($path) . '"');
    }
}
