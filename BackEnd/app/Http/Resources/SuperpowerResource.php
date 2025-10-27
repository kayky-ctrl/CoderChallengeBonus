<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuperpowerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'classifications' => $this->classifications,

            // --- CORREÇÃO AQUI ---
            // Envolva o valor em uma "function () { ... }"
            'pato_designation' => $this->whenLoaded('primordialDuck', function () {
                return $this->primordialDuck->designation;
            }),
            // ---------------------
        ];
    }
}
