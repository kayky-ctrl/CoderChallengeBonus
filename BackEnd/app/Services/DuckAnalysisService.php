<?php

namespace App\Services;

use App\Models\PrimordialDuck;

class DuckAnalysisService
{
    public function generateAnalysis(PrimordialDuck $duck):array
    {
        $baseLatitude = -23.5505;
        $baseLongitude = -46.6333;

        $cost = $this->calculateOperationalCost($duck, $baseLatitude, $baseLongitude);
        $value = $this->calculateScientificValue($duck);

        $riskData = $this->calculateRiskAndMilitary($duck);

        $priority = $this->calculateCapturePriority($cost, $riskData['risk_level'], $value);

        return [
            'operational_cost' =>$cost,
            'risk_level' => $riskData['risk_level'],
            'military_power_needed' =>$riskData['military_power'],
            'scientific_value' =>$value,
            'capture_priority' => $priority,
            'analysis_notes' => $riskData['notes']
        ];
    }
    private function calculateOperationalCost($duck, $baseLat, $baseLon):float
    {
        $distance = 1000;
        $sizeFactor = ($duck->weight_g / 1000) + ($duck->height_cm / 100);
        $cost = ($sizeFactor * 50) + ($distance * 1.5);
        return $cost;
    }
    private function calculateRiskAndMilitary($duck): array
    {
        $risk = 'baixo';
        $military = 'nenhum';
        $notes = '';

        switch ($duck->hibernation_status) {
            case 'desperto':
                $risk = 'critico';
                $military = 'alto';
                $notes = 'Alvo desperto. Alto risco.';

                // Checa o superpoder (requer que ele esteja carregado!)
                if ($duck->superpower && in_array('bélico', $duck->superpower->classifications)) {
                    $military = 'extremo';
                    $notes .= ' Superpoder bélico detectado!';
                }
                break;

            case 'em_transe':
                $risk = 'medio';
                $military = 'baixo';
                $notes = 'Alvo em transe.';

                if ($duck->heart_rate_bpm > 100) {
                    $risk = 'alto';
                    $notes .= ' Risco de despertar: BPM elevado.';
                }
                break;

            case 'hibernacao_profunda':
                $risk = 'minimo';
                $military = 'nenhum';
                $notes = 'Alvo em hibernação profunda. Janela ideal.';
                break;
        }
        return ['risk_level' => $risk, 'military_power' => $military, 'notes' => $notes];
    }
    private function calculateScientificValue($duck): int
    {
        // Lógica de Valor: Mutações + Superpoder
        $value = $duck->mutation_count * 10; // 10 pontos por mutação
        if ($duck->superpower) {
            $value += 50; // +50 pontos se tiver superpoder
            if (in_array('raro', $duck->superpower->classifications)) {
                $value += 100; // +100 se for raro
            }
        }
        return min($value, 1000); // Limita o valor
    }
    private function calculateCapturePriority($cost, $risk, $value): int
    {
        // Lógica de Prioridade: Equilibra Valor vs (Custo + Risco)
        $riskScore = ['minimo' => 1, 'baixo' => 2, 'medio' => 4, 'alto' => 8, 'critico' => 16];
        $priority = ($value * 10) / ($cost + ($riskScore[$risk] * 100));
        return max(1, min(10, (int)$priority)); // Retorna 1-10
    }
}
