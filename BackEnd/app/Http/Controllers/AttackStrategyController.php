<?php

namespace App\Http\Controllers;

use App\Http\Resources\AttackStrategyResource;
use App\Models\AttackStrategy;
use Illuminate\Http\Request;

class AttackStrategyController extends Controller
{
    public function index()
    {
        $strategies = AttackStrategy::all();
        return AttackStrategyResource::collection($strategies);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required',
        ]);

        try {
            $newAttackStrategy = AttackStrategy::create([
                'name' => $request->name,
                'description' => $request->description,
                'trigger_logic' => $request->trigger_logic
            ]);

            return response()->json([
                'message' => 'Estrategia De Ataque Adicionada Com Sucesso!',
                'EstrategiaDeAtaque' => $newAttackStrategy
            ], 201);
        } catch (\Exception $ex) {
            return response()->json([
                'error' => 'Erro ao Adicionar Estrategia De Ataque'
            ], 400);
        }
    }

    public function show(string $id)
    {
        $ShowAttackStrategy = AttackStrategy::findOrFail($id);

        if ($ShowAttackStrategy){
            return response()->json([
                'message' => 'Consulta de Estrategia De Ataque',
                'EstrategiaDeAtaque' => $ShowAttackStrategy
            ], 200);
        }
        else{
            return response()->json([
                'error' => 'Erro Ao Consultar Estrategia De Ataque',
            ], 404);
        }
    }

    public function update(Request $request, string $id)
    {
        $UpdateAttackStrategy = AttackStrategy::findOrFail($id);

        try {
            $update = $UpdateAttackStrategy->update([
                'name' => $request->name,
                'description' => $request->description,
                'trigger_logic' => $request->trigger_logic,

            ]);

            return response()->json([
                'message' => 'Estrategia de Ataque Atualizada com Sucesso!',
                'EstrategiaDeAtaque' => $UpdateAttackStrategy
            ], 200);
        } catch (\Exception $ex) {
            return response()->json([
                'error' => 'Erro ao Atualizar Estrategia de Ataque.'
            ], 404);
        }
    }

    public function destroy(string $id)
    {
        $DestroyAttackStrategy = AttackStrategy::findOrFail($id);
        $delete = $DestroyAttackStrategy->delete();

        if ($delete){
            return response()->json([
                'message' => 'Estrategia de Ataque Deletada com Sucesso!'
            ], 200);
        }
        else{
            return response()->json([
                'error' => 'Erro Ao Deletar Estrategia de Ataque.'
            ], 404);
        }
    }
}
