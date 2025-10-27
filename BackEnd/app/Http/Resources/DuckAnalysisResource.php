<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DuckAnalysisResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'operational_cost' => $this->operational_cost,
            'risk_level' => $this->risk_level,
            'military_power_needed' => $this->military_power_needed,
            'scientific_value' => $this->scientific_value,
            'capture_priority' => $this->capture_priority,
            'analysis_notes' => $this->analysis_notes,
            'pato' => new PrimordialDuckResource($this->whenLoaded('primordialDuck')),
        ];
    }
}
