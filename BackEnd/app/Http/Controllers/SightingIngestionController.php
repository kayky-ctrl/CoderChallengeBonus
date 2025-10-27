<?php

namespace App\Http\Controllers;
use App\Http\Resources\PrimordialDuckResource;
use App\Models\PrimordialDuck;
use App\Models\SightingLog;
use App\Models\Superpower;
use App\Services\UnitConversionService;
use App\Http\Requests\StoreSightingRequest;
use App\Models\SurveyDrone;
use Illuminate\Http\Request;

class SightingIngestionController extends Controller
{
    protected $conversionService;

    public function __construct(UnitConversionService $conversionService)
    {
        $this->conversionService = $conversionService;
    }

    public function __invoke(StoreSightingRequest $request)
    {
        // 1. Calcule os valores
        $height_cm = $this->conversionService->toCm($request->validated('height'), $request->validated('height_unit'));
        $weight_g = $this->conversionService->toGrams($request->validated('weight'), $request->validated('weight_unit'));
        $precision_m = $this->conversionService->toMeters($request->validated('gps_precision'), $request->validated('gps_precision_unit'));

// 2. Monte o payload de dados do Pato (A CORREÇÃO ESTÁ AQUI)
        $duckDataPayload = [
            'height_cm' => $height_cm,
            'weight_g' => $weight_g,
            'last_known_lat' => $request->validated('latitude'),
            'last_known_lon' => $request->validated('longitude'),
            'gps_precision_m' => $precision_m,
            'last_known_city' => $request->validated('city_name'),
            'last_known_country' => $request->validated('country_name'),
            'hibernation_status' => $request->validated('hibernation_status'),

            // A lógica é executada IMEDIATAMENTE e o resultado (null ou o valor) é salvo.
            // NÃO coloque "function()" ao redor disto.
            'heart_rate_bpm' => $request->validated('hibernation_status') === 'desperto'
                ? null
                : $request->validated('heart_rate_bpm'),

            'mutation_count' => $request->validated('mutation_count'),
        ];

// 3. Chame o firstOrCreate (linha 46)
        $duck = PrimordialDuck::firstOrCreate(
            ['designation' => $request->validated('designation')], // Chave para procurar
            $duckDataPayload                                     // Dados para usar SE for criar
        );

        // 4. Se o pato já existia (não foi criado agora), atualize-o
        if (!$duck->wasRecentlyCreated) {
            $duck->update($duckDataPayload);
        }
        // --- FIM DA MUDANÇA ---

        // 5. Lógica de Superpoder (como antes)
        if ($duck->hibernation_status === 'desperto') {
            Superpower::updateOrCreate(
                ['primordial_duck_id' => $duck->id],
                [ // Dados para atualizar ou criar
                    'name' => $request->validated('superpower_name'),
                    'description' => $request->validated('superpower_description'),
                    'classifications' => $request->validated('superpower_classifications'),
                ]
            );
        }

        // 6. Criar o Log (como antes)
        $drone = SurveyDrone::where('serial_number', $request->validated('drone_serial_number'))->first();
        SightingLog::create([
            'survey_drone_id' => $drone->id,
            'primordial_duck_id' => $duck->id,
            'raw_data_payload' => $request->all(),
            'sighted_at' => now(),
        ]);

        // 7. Retornar a resposta (ver Mudança 2)
        // Em vez de retornar o $duck cru, retornaremos um "Resource".
        return (new PrimordialDuckResource($duck->load('superpower')))
            ->response()
            ->setStatusCode(201);
    }

}
