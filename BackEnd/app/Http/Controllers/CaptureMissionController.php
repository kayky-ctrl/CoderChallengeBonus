<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCaptureMissionRequest;
use App\Http\Resources\CaptureMissionResource;
use App\Models\CaptureDrone;
use App\Models\CaptureMission;
use Illuminate\Http\Request;

class CaptureMissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Carrega os relacionamentos necessários pelo Resource
        $missions = CaptureMission::with(['MissionDrone', 'MissionDuck'])->get(); 
        
        // Retorna a coleção formatada pelo Resource (chave "data" por padrão)
        return CaptureMissionResource::collection($missions);
    }

    public function store(StoreCaptureMissionRequest $request)
    {
        $mission = CaptureMission::create([
            'capture_drone_id' => $request->validated('capture_drone_id'),
            'primordial_duck_id' => $request->validated('primordial_duck_id'),
            'briefing_notes' => $request->validated('briefing_notes'),
            'status' => 'planejamento',
        ]);


        $drone = CaptureDrone::find($request->validated('capture_drone_id'));
        if ($drone) {
            $drone->update(['status' => 'em_missao']);
        }

        $mission->load(['MissionDrone', 'MissionDuck']); // Use os nomes dos métodos do Model

// 4. Retorna a missão criada, formatada por um Resource
        return (new CaptureMissionResource($mission))
            ->response()
            ->setStatusCode(201); // 201 Created
    }

    /**
     * Display the specified resource.
     */
    public function show(CaptureMission $mission) // Route Model Binding!
{
    $mission->load([
        'MissionDrone',
        'MissionDuck.analysis',
        'MissionDuck.superpower'
    ]);

    return new CaptureMissionResource($mission);
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CaptureMission $captureMission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CaptureMission $captureMission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CaptureMission $captureMission)
    {
        //
    }
}
