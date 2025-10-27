<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SurveyDroneResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Retorna os campos principais do Model SurveyDrone
        return [
            'id' => $this->id,
            'serial_number' => $this->serial_number,
            'brand' => $this->brand,
            'manufacturer_id' => $this->manufacturer_id,
            // Opcional: Carregar dados do fabricante se necessário
            'fabricante' => new ManufacturerResource($this->whenLoaded('manufacturers')),
        ];
    }
}
