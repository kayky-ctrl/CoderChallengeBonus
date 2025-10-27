<?php

namespace App\Http\Controllers;

use App\Http\Resources\ManufacturerResource;
use Illuminate\Http\Request;
use App\Models\Manufacturer;

class ManufacturerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $manufacturers = Manufacturer::all();
        return ManufacturerResource::collection($manufacturers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'country_of_origin' => 'required|string|max:255'
        ]);

        try {
            $newManufacturer = Manufacturer::create([
                'name' => $request->name,
                'country_of_origin' => $request->country_of_origin
            ]);

            return response()->json([
                'message' => 'Fabricante Adicionada Com Sucesso!',
                'fabricante' => $newManufacturer
            ], 201);
        } catch (\Exception $ex) {
            return response()->json([
                'error' => 'Erro ao Adicionar Fabricante.'
            ], 400);
        }
    }

    public function show(string $id)
    {
        $manufacturer = Manufacturer::findOrFail($id);

        if ($manufacturer){
            return response()->json([
                'message' => 'Consulta de Fabricante',
                'fabricante' => $manufacturer
           ], 200);
        }
        else{
            return response()->json([
                'error' => 'Erro Ao Consultar Fabricante',
            ], 404);
        }
    }

    public function update(Request $request, string $id)
    {
        $manufacturer = Manufacturer::findOrFail($id);

        try {
            $update = $manufacturer->update([
                'name' => $request->name,
                'country_of_origin' => $request->country_of_origin
            ]);

            return response()->json([
                'message' => 'Fabricante Atualizada com Sucesso!',
                'fabricante' => $manufacturer
            ], 200);
        } catch (\Exception $ex) {
            return response()->json([
                'error' => 'Erro ao Atualizar Fabricante.'
            ], 404);
        }
    }

    public function destroy(string $id)
    {
        $manufacturer = Manufacturer::findOrFail($id);
        $delete = $manufacturer->delete();

        if ($delete){
            return response()->json([
                'message' => 'Fabricante Deletada com Sucesso!'
            ], 200);
        }
        else{
            return response()->json([
                'error' => 'Erro Ao Deletar Fabricante.'
            ], 404);
        }
    }
}
