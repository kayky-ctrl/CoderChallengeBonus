<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTelemetryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Pega o drone da rota para garantir que a telemetria é dele mesmo
        $drone = $this->route('drone');

        return [
            // Valida os campos de telemetria
            'battery_percent' => 'required|numeric|min:0|max:100',
            'fuel_percent' => 'required|numeric|min:0|max:100',
            'integrity_percent' => 'required|numeric|min:0|max:100',
            // Valida o status do drone (se ele for enviado)
            'status' => ['nullable', Rule::in(['ocioso', 'em_missao', 'carregando', 'manutencao'])],
        ];
    }
}
