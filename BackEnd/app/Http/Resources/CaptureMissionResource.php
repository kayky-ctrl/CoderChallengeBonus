<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PrimordialDuckResource;
use App\Http\Resources\CaptureDroneResource;

class CaptureMissionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'briefing_notes' => $this->briefing_notes,
            'debriefing_notes' => $this->debriefing_notes,
            // Inclui o objeto do Pato (já formatado)
            'target_duck' => new PrimordialDuckResource($this->whenLoaded('MissionDuck')),
            'assigned_drone' => new CaptureDroneResource($this->whenLoaded('MissionDrone')),
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toIso8601String() : null,
        ];
    }
}
