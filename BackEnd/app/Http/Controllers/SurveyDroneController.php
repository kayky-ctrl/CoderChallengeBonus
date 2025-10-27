<?php

namespace App\Http\Controllers;

use App\Http\Resources\SurveyDroneResource;
use App\Models\SurveyDrone;
use Illuminate\Http\Request;

class SurveyDroneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $surveyDrones = SurveyDrone::all();

        return SurveyDroneResource::collection($surveyDrones);
    }
    public function store(Request $request)
    {
        $validate = $request->validate([
            'serial_number' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'manufacturer_id' => 'required'
        ]);
        try {
            $newSurveyDrone = SurveyDrone::create([
                'serial_number' => $request->serial_number,
                'brand' => $request->brand,
                'manufacturer_id' => $request->manufacturer_id
            ]);
            return response()->json([
                'message' => 'Drone Adicionado com Sucesso!',
                'DronePesquisa' => $newSurveyDrone
            ], 201);
        } catch (\Exception $ex) {
            return response()->json([
                'error' => 'Erro ao Adicionar Drone.'
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     * CORREÇÃO: Variável renomeada de $id para $survey_drone para bater com o Route Model Binding
     */
    public function show(SurveyDrone $survey_drone)
    {
        return response()->json([
            'message' => 'Consultando Drone',
            'DronePesquisa' => $survey_drone
        ],200);
    }
    /**
     * Update the specified resource in storage.
     * CORREÇÃO: Variável renomeada de $id para $survey_drone
     */
    public function update(Request $request, SurveyDrone $survey_drone)
    {
        try {
            $update = $survey_drone->update([ // Usa a variável injetada
                'serial_number' => $request->serial_number,
                'brand' => $request->brand,
                'manufacturer_id' => $request->manufacturer_id
            ]);
            return response()->json([
                'message' => 'Drone Atualizado com Sucesso!',
                'DronePesquisa' => $survey_drone
            ], 200);
        } catch (\Exception $ex) {
            return response()->json([
                'error' => 'Erro ao Atualizar Drone.'
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     * CORREÇÃO: Variável renomeada de $id para $survey_drone
     */
    public function destroy(SurveyDrone $survey_drone)
    {
        $delete = $survey_drone->delete(); // Usa a variável injetada

        if ($delete){
            return response()->json([
                'message' => 'Drone Desvinculado com Sucesso!'
            ], 200);
        }
        else{
            return response()->json([
                'error' => 'Erro Ao Desvincular Drone.'
            ], 404);
        }
    }
}