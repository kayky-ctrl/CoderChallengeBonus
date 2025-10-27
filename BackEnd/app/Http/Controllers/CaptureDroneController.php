<?php

namespace App\Http\Controllers;

use App\Http\Resources\CaptureDroneResource;
use App\Models\CaptureDrone;
use Illuminate\Http\Request;

class CaptureDroneController extends Controller
{
    public function index()
    {
        $captureDrones = CaptureDrone::all();
        return CaptureDroneResource::collection($captureDrones);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'designation' => 'required|string|max:255',
            'status' => 'required',
            'battery_percent' => 'required|decimal:2',
            'fuel_percent' => 'required|decimal:2',
            'integrity_percent' => 'required|decimal:2'
        ]);

        try {
            $newCaptureDrone = CaptureDrone::create([
                'designation' => $request->designation,
                'status' => $request->status,
                'battery_percent' => $request->battery_percent,
                'fuel_percent' => $request->fuel_percent,
                'integrity_percent' => $request->integrity_percent
            ]);

            return response()->json([
                'message' => 'Drone de Captura Adicionado Com Sucesso!',
                'DroneDeCaptura' => $newCaptureDrone
            ], 201);
        } catch (\Exception $ex) {
            return response()->json([
                'error' => 'Erro ao Adicionar Drone de Captura.'
            ], 400);
        }
    }

    public function show(string $id)
    {
        $CaptureDrone = CaptureDrone::findOrFail($id);

        if ($CaptureDrone){
            return response()->json([
                'message' => 'Consulta de Drone de Captura',
                'DroneDeCaptura' => $CaptureDrone
            ], 200);
        }
        else{
            return response()->json([
                'error' => 'Erro Ao Consultar Drone de Captura',
            ], 404);
        }
    }

    public function update(Request $request, string $id)
    {
        $CaptureDrone = CaptureDrone::findOrFail($id);

        try {
            $update = $CaptureDrone->update([
                'designation' => $request->designation,
                'status' => $request->status,
                'battery_percent' => $request->battery_percent,
                'fuel_percent' => $request->fuel_percent,
                'integrity_percent' => $request->integrity_percent
            ]);

            return response()->json([
                'message' => 'Drone de Captura Atualizado com Sucesso!',
                'DroneDeCaptura' => $CaptureDrone
            ], 200);
        } catch (\Exception $ex) {
            return response()->json([
                'error' => 'Erro ao Atualizar Drone de Captura.'
            ], 404);
        }
    }

    public function destroy(string $id)
    {
        $CaptureDrone = CaptureDrone::findOrFail($id);
        $delete = $CaptureDrone->delete();

        if ($delete){
            return response()->json([
                'message' => 'Drone de Captura Deletado com Sucesso!'
            ], 200);
        }
        else{
            return response()->json([
                'error' => 'Erro Ao Deletar Drone de Captura.'
            ], 404);
        }
    }
}
