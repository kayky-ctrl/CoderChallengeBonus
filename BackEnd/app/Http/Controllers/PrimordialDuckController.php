<?php

namespace App\Http\Controllers;

use App\Http\Resources\PrimordialDuckResource;
use App\Models\PrimordialDuck;
use App\Models\Superpower;
use Illuminate\Http\Request;

class PrimordialDuckController extends Controller
{
    public function index(Request $request){
        // Use 'with()' para Eager Loading e evitar N+1 queries
        $ducks = PrimordialDuck::with('superpower', 'analysis')->get();

        // Retorne a coleção de Resources
        return PrimordialDuckResource::collection($ducks);
    }
    public function show(PrimordialDuck $primordialDuck){
        // Carregue os relacionamentos
        $primordialDuck->load('superpower', 'analysis');

        // Retorne um único Resource
        return new PrimordialDuckResource($primordialDuck);
    }
}
