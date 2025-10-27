<?php

namespace App\Services;

use App\Models\CaptureMission;
use App\Models\DefenseSystem;

class DefenseService
{
    public function getCounterMeasure(CaptureMission $mission): ?DefenseSystem
    {
        // 1. Pega o pato alvo e seu superpoder
        // -> Carregar a relação do pato primeiro (MissionDuck)
        $duck = $mission->MissionDuck()->with('superpower')->first(); // Use o nome correto da relação da Missão

        if (!$duck || !$duck->superpower) {
            \Log::warning("DefenseService: Pato (ID: {$mission->primordial_duck_id}) ou Superpoder não encontrado para Missão ID: {$mission->id}");
            return null; // Pato não encontrado ou não tem superpoder
        }

        // 2. Busca a primeira defesa que countera esse superpoder (CORRIGIDO)
        // -> Use o nome correto da relação do Model Superpower: SuperpowerDefense()
        \Log::debug("DefenseService: Buscando counter para Superpoder ID: {$duck->superpower->id}");
        $defense = $duck->superpower->SuperpowerDefense()->first();

        if ($defense) {
            \Log::debug("DefenseService: Counter encontrado: DefenseSystem ID: {$defense->id}");
        } else {
            \Log::warning("DefenseService: Nenhum counter encontrado para Superpoder ID: {$duck->superpower->id}");
        }

        return $defense;
    }
}
