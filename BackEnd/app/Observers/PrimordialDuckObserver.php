<?php

namespace App\Observers;

use App\Models\DuckAnalysis;
use App\Models\PrimordialDuck;
use App\Services\DuckAnalysisService;

class PrimordialDuckObserver
{
    protected $analysisService;

    // 2. Injete o serviço no construtor
    public function __construct(DuckAnalysisService $analysisService)
    {
        $this->analysisService = $analysisService;
    }

    /**
     * Handle the PrimordialDuck "saved" event.
     * (O evento "saved" roda tanto no "created" quanto no "updated")
     */
    public function saved(PrimordialDuck $duck): void
    {
        // 3. Carregue o superpoder, pois o serviço de análise precisa dele!
        $duck->load('superpower');

        // 4. Chame o serviço para fazer os cálculos
        $analysisData = $this->analysisService->generateAnalysis($duck);

        // 5. Salve os resultados na tabela de análise!
        DuckAnalysis::updateOrCreate(
            ['primordial_duck_id' => $duck->id], // Chave de busca
            $analysisData                        // Dados para atualizar/inserir
        );
    }
}
