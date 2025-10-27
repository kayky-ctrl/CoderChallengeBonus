<?php

namespace App\Http\Controllers;

use App\Models\PrimordialDuck;
use App\Services\TacticalService;
use Illuminate\Http\Request;

class TacticalPlanController extends Controller
{
    /**
     * Handle the incoming request.
     */protected $tacticalService;

    // Injete o serviço
    public function __construct(TacticalService $tacticalService)
    {
        $this->tacticalService = $tacticalService;
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, PrimordialDuck $duck) // Usa Route Model Binding pelo {duck} na rota
    {
        // Carrega a relação de fraquezas necessária pelo serviço
        $duck->load('DuckWeaknesses');

        // Delega a lógica para o serviço
        $plan = $this->tacticalService->getTacticalPlan($duck);

        // Retorna o plano como JSON
        return response()->json($plan);
    }
}
