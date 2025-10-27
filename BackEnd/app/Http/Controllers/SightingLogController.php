<?php

namespace App\Http\Controllers;

use App\Http\Resources\SightingLogResource;
use App\Models\PrimordialDuck;
use App\Models\SightingLog;
use App\Models\SurveyDrone;
use Illuminate\Http\Request;

class SightingLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Carregue AMBOS os relacionamentos!
        $logs = SightingLog::with(['primordialDuck', 'surveyDrone'])
            ->orderBy('sighted_at', 'desc')
            ->get();

        return SightingLogResource::collection($logs);;
    }

    public function indexForDuck(PrimordialDuck $primordialDuck){
        $primordialDuck->load('duck_sighting_log');

        return response()->json([
            'message' => 'Consulta do Pato',
            'PatoPrimordial' => $primordialDuck
        ], 200);
    }
    public function indexForDrone(SurveyDrone $surveyDrone){
        $surveyDrone->load('sighting_logs');

        return response()->json([
            'message' => 'Consultada de Todas as Logs do Drone',
            "LogsAvistamento" => $surveyDrone->sighting_logs
        ], 200);
    }
}
