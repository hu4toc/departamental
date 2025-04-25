<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\IndicadorController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\InstitutoController;
use App\Http\Controllers\CarreraController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\EvaluacionController;

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
Route::get('/personas', [PersonaController::class, 'index']);
Route::get('/personas/docentes', [PersonaController::class, 'docentes']);

// Institutos
Route::get('/institutos', [InstitutoController::class, 'index']);

// Carreras
Route::get('/carreras', [CarreraController::class, 'index']);

// Proyectos
Route::get('/proyectos', [ProyectoController::class, 'index']);
Route::get('/proyectos/{id}', [ProyectoController::class, 'show']);
Route::get('/proyectos/{id}/integrantes', [ProyectoController::class, 'integrantes']);
Route::get('/proyectos/{id}/calificaciones', [ProyectoController::class, 'calificaciones']);

// Evaluacion
Route::post('/evaluacion', [EvaluacionController::class, 'store']);
