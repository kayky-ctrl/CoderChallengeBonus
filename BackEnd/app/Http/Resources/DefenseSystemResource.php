<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DefenseSystemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Retorna os dados públicos do sistema de defesa
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'resource_cost' => $this->resource_cost,
        ];
    }
}
