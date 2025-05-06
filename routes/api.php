<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\IndicadorController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\InstitutoController;
use App\Http\Controllers\CarreraController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\EvaluacionController;
use App\Http\Controllers\PersonaProyectoController;
use App\Http\Controllers\AreaController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Indicadores
Route::get('/indicadores', [IndicadorController::class, 'index']);
Route::post('/indicadores', [IndicadorController::class, 'store']);
Route::get('/indicadores/{id}', [IndicadorController::class, 'show']);
Route::put('/indicadores/{id}', [IndicadorController::class, 'update']);
Route::delete('/indicadores/{id}', [IndicadorController::class, 'destroy']);


// Personas
Route::post('/personas', [PersonaController::class, 'store']);
Route::post('/personas/update/{id}', [PersonaController::class, 'update']);
Route::get('/personas', [PersonaController::class, 'index']);
Route::get('/personas/docentes', [PersonaController::class, 'docentes']);
Route::get('/personas/integrantes', [PersonaController::class, 'integrantes']);
Route::get('/personas/{id}/foto', [PersonaController::class, 'mostrarPersonaPorId']);

// Institutos
Route::post('/institutos', [InstitutoController::class, 'store']);
Route::post('/institutos/update/{id}', [InstitutoController::class, 'update']);
Route::get('/institutos', [InstitutoController::class, 'index']);
Route::get('/institutos/select', [InstitutoController::class, 'select']);
Route::get('/institutos/{id}/foto', [InstitutoController::class, 'mostrarInstitutoPorId']);

// Carreras
Route::get('/carreras', [CarreraController::class, 'index']);
Route::get('/carreras/select', [CarreraController::class, 'select']);

// Proyectos
Route::get('/proyectos', [ProyectoController::class, 'index']);
Route::post('/proyectos', [ProyectoController::class, 'store']);
Route::post('/proyectos/update/{id}', [ProyectoController::class, 'update']);
Route::get('/proyectos/{id}', [ProyectoController::class, 'show']);
Route::get('/proyectos/{id}/integrantes', [ProyectoController::class, 'integrantes']);
Route::get('/proyectos/{id}/calificaciones', [ProyectoController::class, 'calificaciones']);
Route::get('/proyectos/{id}/portada', [ProyectoController::class, 'mostrarPortadaPorId']);
Route::get('/proyectos/{id}/documento', [ProyectoController::class, 'mostrarDocumentoPorId']);

// Evaluacion
Route::post('/evaluacion', [EvaluacionController::class, 'store']);

// ProyectoPersona
Route::post('/personasproyectos', [PersonaProyectoController::class, 'store']);

// Areas
Route::get('/areas/select', [AreaController::class, 'select']);