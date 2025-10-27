<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DuckAnalyse extends Model
{
    protected $fillable =[
        'operational_cost',
        'military_power_needed',
        'risk_level',
        'scientific_value',
        'capture_priority',
        'analysis_notes',
        'primordial_duck_id'
    ];
    protected $hidden =[
        'updated_at',
        'created_at'
    ];
    public function DuckAnalyses():BelongsTo
    {
        return $this->belongsTo(PrimordialDuck::class);
    }
}
