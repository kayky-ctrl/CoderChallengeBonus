<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrimordialDuckResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'designation' => $this->designation, // A "chave" amigável
            'height_cm' => $this->height_cm,
            'weight_g' => $this->weight_g,
            'hibernation_status' => $this->hibernation_status,
            'mutation_count' => $this->mutation_count,
            'last_known_location' => [
                'city' => $this->last_known_city,
                'country' => $this->last_known_country,
                'latitude' => $this->last_known_lat,
                'longitude' => $this->last_known_lon,
            ],
            // Carrega o superpoder SÓ SE ele foi incluído na consulta (com ->load() ou ->with())
            'superpower' => new SuperpowerResource($this->whenLoaded('superpower')),
            'analysis' => new DuckAnalysisResource($this->whenLoaded('analysis')),
            'updated_at' => $this->updated_at->toIso8601String(),

        ];
    }
}
