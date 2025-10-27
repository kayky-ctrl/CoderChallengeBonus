<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ManufacturerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Retorna os campos principais do Model Manufacturer
        return [
            'id' => $this->id,
            'name' => $this->name,
            'country_of_origin' => $this->country_of_origin,
        ];
    }
}
