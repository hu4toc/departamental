<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Indicador;

class IndicadorController extends Controller
{
    // Obtener todos los indicadores
    public function index()
    {
        $indicadores = Indicador::select(
                'id',
                'nombre',
                'categoria',
                'puntaje_mayor'
            )
            ->get();

        $indicadoresFormateados = [];
        $x = 0;
        foreach($indicadores as $i) {
            $indicadoresFormateados[$x]['id'] = $i->id;
            $indicadoresFormateados[$x]['nombre'] = $i->nombre;
            $indicadoresFormateados[$x]['categoria'] = $i->categoria;
            $indicadoresFormateados[$x]['puntos'] = $this->genererarVector($i->puntaje_mayor);
            $x++;
        }

        return response()->json([
            'success' => true, 
            'data' => $indicadoresFormateados, 
        ], 200);
    }

    private function genererarVector($limite) {
        // Crear un arreglo vacío
        $vector = [];

        // Llenar el arreglo con números del 0 hasta el $limite
        for ($i = 0; $i <= $limite; $i++) {
            $vector[] = $i;  // Agregar el número al arreglo
        }

        // Retornar el arreglo generado
        return $vector;
    }

    // Crear un nuevo indicador
    public function store(Request $request)
    {
        $indicador = Indicador::create($request->all());

        return response()->json([
            'message' => 'Indicador creado correctamente',
            'data' => $indicador
        ], 201);
    }

    // Mostrar un solo indicador
    public function show($id)
    {
        $indicador = Indicador::find($id);

        if (!$indicador) {
            return response()->json(['message' => 'Indicador no encontrado'], 404);
        }

        return response()->json($indicador);
    }

    // Actualizar un indicador
    public function update(Request $request, $id)
    {
        $indicador = Indicador::find($id);

        if (!$indicador) {
            return response()->json(['message' => 'Indicador no encontrado'], 404);
        }

        $indicador->update($request->all());

        return response()->json([
            'message' => 'Indicador actualizado correctamente',
            'data' => $indicador
        ]);
    }

    // Eliminar un indicador
    public function destroy($id)
    {
        $indicador = Indicador::find($id);

        if (!$indicador) {
            return response()->json(['message' => 'Indicador no encontrado'], 404);
        }

        $indicador->delete();

        return response()->json(['message' => 'Indicador eliminado correctamente']);
    }
}
