<?php

namespace App\Http\Controllers;

use App\Http\Resources\DefenseSystemResource;
use App\Models\DefenseSystem;
use Illuminate\Http\Request;

class DefenseSystemController extends Controller
{
    public function index()
    {
        $DefenseSystems = DefenseSystem::all();
        return DefenseSystemResource::collection($DefenseSystems);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required',
            'resource_cost' => 'required|integer'
        ]);

        try {
            $newDefenseSystem = DefenseSystem::create([
                'name' => $request->name,
                'description' => $request->description,
                'resource_cost' => $request->resource_cost
            ]);

            return response()->json([
                'message' => 'Sistema de Defesa Adicionado Com Sucesso!',
                'SistemaDeDefesa' => $newDefenseSystem
            ], 201);
        } catch (\Exception $ex) {
            return response()->json([
                'error' => 'Erro ao Adicionar Sistema de Defesa'
            ], 400);
        }
    }

    public function show(string $id)
    {
        $ShowDefenseSystem = DefenseSystem::findOrFail($id);

        if ($ShowDefenseSystem){
            return response()->json([
                'message' => 'Consulta de Sistema de Defesa',
                'SistemaDeDefesa' => $ShowDefenseSystem
            ], 200);
        }
        else{
            return response()->json([
                'error' => 'Erro Ao Consultar Sistema de Defesa',
            ], 404);
        }
    }

    public function update(Request $request, string $id)
    {
        $UpdateDefenseSystem = DefenseSystem::findOrFail($id);

        try {
            $update = $UpdateDefenseSystem->update([
                'name' => $request->name,
                'description' => $request->description,
                'resource_cost' => $request->resource_cost,

            ]);

            return response()->json([
                'message' => 'Sistema de Defesa Atualizado com Sucesso!',
                'Sistema de Defesa' => $UpdateDefenseSystem
            ], 200);
        } catch (\Exception $ex) {
            return response()->json([
                'error' => 'Erro ao Atualizar Sistema de Defesa.'
            ], 404);
        }
    }

    public function destroy(string $id)
    {
        $DestroyDefenseSystem = DefenseSystem::findOrFail($id);
        $delete = $DestroyDefenseSystem->delete();

        if ($delete){
            return response()->json([
                'message' => 'Sistema de Defesa Deletado com Sucesso!'
            ], 200);
        }
        else{
            return response()->json([
                'error' => 'Erro Ao Deletar Sistema de Defesa.'
            ], 404);
        }
    }
}
