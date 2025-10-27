<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CaptureDroneResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return  [
            'id' => $this->id,
            'designation' => $this->designation,
            'status' => $this->status,
            'battery_percent' => $this->battery_percent,
            'fuel_percent' => $this->fuel_percent,
            'integrity_percent' => $this->integrity_percent,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
    ];
    }
}
