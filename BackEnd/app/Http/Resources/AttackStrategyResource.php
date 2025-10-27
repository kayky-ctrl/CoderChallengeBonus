<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttackStrategyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Retorna os campos principais do Model AttackStrategy
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'trigger_logic' => $this->trigger_logic, // Já é um array devido ao $casts no Model
        ];
    }
}
