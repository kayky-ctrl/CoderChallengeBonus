<?php

namespace App\Http\Controllers;

use App\Http\Resources\DefenseSystemResource;
use App\Models\CaptureMission;
use App\Services\DefenseService;
use Illuminate\Http\Request;

class DefenseActivationController extends Controller
{
    protected $defenseService;

    // Injete o serviço
    public function __construct(DefenseService $defenseService)
    {
        $this->defenseService = $defenseService;
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, CaptureMission $mission) // Usa Route Model Binding
    {
        // 1. Pede ao serviço para encontrar a contramedida
        $defense = $this->defenseService->getCounterMeasure($mission);

        // 2. Monta a resposta
        if ($defense) {
            // Retorna a defesa encontrada (formatada por um Resource)
            return response()->json([
                'action' => 'activate',
                'defense' => new DefenseSystemResource($defense)
            ]);
        } else {
            // Nenhuma defesa encontrada ou pato não tem superpoder
            return response()->json([
                'action' => 'none',
                'message' => 'Nenhuma contramedida encontrada para o superpoder atual.'
            ], 404); // Not Found
        }
    }
}
