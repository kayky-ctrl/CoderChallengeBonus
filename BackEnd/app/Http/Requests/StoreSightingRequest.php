<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSightingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'drone_serial_number' => 'required|string|exists:survey_drones,serial_number',
            'designation' => 'required|string|max:255',
            'height' => 'required|numeric',
            'height_unit' => 'required|in:cm,feet',
            'weight' => 'required|numeric',
            'weight_unit'=> 'required|in:g,lbs',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'gps_precision' =>'required|numeric',
            'gps_precision_unit' => 'required|in:m,yards',
            'last_known_city' => 'nullable|string',
            'last_known_country' => 'nullable|string',
            'hibernation_status' => ['required', Rule::in(['desperto', 'em_transe', 'hibernacao_profunda'])],
            'heart_rate_bpm' => 'nullable|required_if:hibernation_status,em_transe,hibernacao_profunda',
            'mutation_count' => 'required|integer',
            'superpower_name' => 'nullable|required_if:hibernation_status,desperto',
            'superpower_description' => 'nullable|required_if:hibernation_status,desperto',
            'superpower_classifications' =>'nullable|array|required_if:hibernation_status,desperto'
        ];
    }
}
