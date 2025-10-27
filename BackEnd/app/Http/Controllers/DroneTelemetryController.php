<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTelemetryRequest;
use App\Models\CaptureDrone;
use Illuminate\Http\Request;

class DroneTelemetryController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreTelemetryRequest $request, CaptureDrone $drone)
    {
        // 1. Pega os dados validados
        $validatedData = $request->validated();

        // 2. Atualiza o drone (o $drone já é injetado pela rota!)
        $drone->update($validatedData);

        // 3. Retorna uma resposta simples de sucesso
        return response()->json(['message' => "Telemetria do Drone {$drone->designation} atualizada."]);

        // Opcional: Retornar o drone atualizado usando CaptureDroneResource
        // return new CaptureDroneResource($drone);
    }
}
