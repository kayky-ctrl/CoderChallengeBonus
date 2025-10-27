<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SightingLog extends Model
{
    protected $fillable =[
        'raw_data_payload',
        'sighted_at',
        'survey_drone_id',
        'primordial_duck_id'
    ];
    protected $hidden =[
        'updated_at',
        'created_at'
    ];
    protected $casts = [
        "raw_data_payload" => 'array',
        'sighted_at' => 'datetime'
    ];
    public function primordialDuck():BelongsTo{
        return $this->belongsTo(PrimordialDuck::class, 'primordial_duck_id', 'id');
    }
    public function surveyDrone():BelongsTo{
        return $this->belongsTo(SurveyDrone::class, 'survey_drone_id', 'id');
    }
}
