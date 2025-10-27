<?php

use App\Http\Controllers\AttackStrategyController;
use App\Http\Controllers\CaptureDroneController;
use App\Http\Controllers\CaptureMissionController;
use App\Http\Controllers\DefenseActivationController;
use App\Http\Controllers\DefenseSystemController;
use App\Http\Controllers\DroneTelemetryController;
use App\Http\Controllers\ManufacturerController;
use App\Http\Controllers\PrimordialDuckController;
use App\Http\Controllers\SightingLogController;
use App\Http\Controllers\SurveyDroneController;
use App\Http\Controllers\SightingIngestionController;
use App\Http\Controllers\TacticalPlanController;
use App\Http\Controllers\WeaknessesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('/manufacturer', ManufacturerController::class);
Route::apiResource('/survey-drones', SurveyDroneController::class);
Route::apiResource('/capture-drones', CaptureDroneController::class);
Route::apiResource('/weaknesses', WeaknessesController::class);
Route::apiResource('/attack-strategies', AttackStrategyController::class);
Route::apiResource('/defense-systems', DefenseSystemController::class);

// --- MISSÃO 3: OPERAÇÃO ---
Route::apiResource('capture-missions', CaptureMissionController::class)->parameters(['capture-missions' => 'mission'])->only(['index', 'show', 'store']);
Route::post('capture-drones/{drone}/telemetry', DroneTelemetryController::class);

// --- MISSÃO 3: IA TÁTICA ---
// Atenção aqui: Usamos {duck:designation} para buscar o pato pelo nome!
Route::get('primordial-ducks/{duck:designation}/tactical-plan', TacticalPlanController::class);
Route::post('capture-missions/{mission}/activate-defense', DefenseActivationController::class);

Route::get('/primordial-ducks', [PrimordialDuckController::class , 'index']);
Route::get('/primordial-ducks/{primordialDuck:designation}', [PrimordialDuckController::class, 'show']);
Route::get('/primordial-ducks/{primordialDuck:designation}/logs', [SightingLogController::class, 'indexForDuck']);
Route::get('/survey-drones/{surveyDrone:serial_number}/logs', [SightingLogController::class, 'indexForDrone']);
Route::get('/sighting-logs', [SightingLogController::class, 'index']);
Route::post('/sightings' , SightingIngestionController::class);
