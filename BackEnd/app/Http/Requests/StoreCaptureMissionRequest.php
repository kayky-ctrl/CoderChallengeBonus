<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\CaptureDrone;

class StoreCaptureMissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Permitir a todos por enquanto
    }

    public function rules(): array
    {
        return [
            // Garante que o ID do drone existe na tabela 'capture_drones'
            'capture_drone_id' => [
                'required',
                'integer',
                'exists:capture_drones,id',
                // Regra customizada: Garante que o drone esteja 'ocioso'
                function ($attribute, $value, $fail) {
                    $drone = CaptureDrone::find($value);
                    if ($drone && $drone->status !== 'ocioso') {
                        $fail("O drone selecionado (ID: {$value}) não está ocioso.");
                    }
                },
            ],
            // Garante que o ID do pato existe na tabela 'primordial_ducks'
            'primordial_duck_id' => 'required|integer|exists:primordial_ducks,id',
            'briefing_notes' => 'nullable|string', // Notas opcionais
        ];
    }
}
