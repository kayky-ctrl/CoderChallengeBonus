<?php

namespace App\Http\Controllers;

use App\Http\Resources\WeaknessesResource;
use App\Models\Weaknesses;
use Illuminate\Http\Request;

class WeaknessesController extends Controller
{

    public function index()
    {
        $weaknesses = Weaknesses::all();
        return WeaknessesResource::collection($weaknesses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255|unique:weaknesses,name',
            'description' => 'required',
        ]);

        try {
            $newWeaknesses = Weaknesses::create([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            return response()->json([
                'message' => 'Fraqueza Adicionada Com Sucesso!',
                'Fraqueza' => $newWeaknesses
            ], 201);
        } catch (\Exception $ex) {
            return response()->json([
                'error' => 'Erro ao Adicionar Fraqueza.'
            ], 400);
        }
    }

    public function show(string $id)
    {
        $ShowWeaknesses = Weaknesses::findOrFail($id);

        if ($ShowWeaknesses){
            return response()->json([
                'message' => 'Consulta de Fraqueza',
                'DroneDeCaptura' => $ShowWeaknesses
            ], 200);
        }
        else{
            return response()->json([
                'error' => 'Erro Ao Consultar Fraqueza',
            ], 404);
        }
    }

    public function update(Request $request, string $id)
    {
        $UpdateWeaknesses = Weaknesses::findOrFail($id);

        try {
            $update = $UpdateWeaknesses->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            return response()->json([
                'message' => 'Fraqueza Atualizada com Sucesso!',
                'Fraqueza' => $UpdateWeaknesses
            ], 200);
        } catch (\Exception $ex) {
            return response()->json([
                'error' => 'Erro ao Atualizar Fraqueza.'
            ], 404);
        }
    }

    public function destroy(string $id)
    {
        $DestroyWeaknesses = Weaknesses::findOrFail($id);
        $delete = $DestroyWeaknesses->delete();

        if ($delete){
            return response()->json([
                'message' => 'Fraqueza Deletada com Sucesso!'
            ], 200);
        }
        else{
            return response()->json([
                'error' => 'Erro Ao Deletar Fraqueza.'
            ], 404);
        }
    }
}
