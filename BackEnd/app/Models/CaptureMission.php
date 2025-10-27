<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PrimordialDuck;
use App\Models\CaptureDrone;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaptureMission extends Model
{
    protected $fillable =[
        'status',
        'briefing_notes',
        'debriefing_notes',
        'capture_drone_id',
        'primordial_duck_id'
    ];
    protected $hidden =[
        'updated_at',
        'created_at'
    ];
    public function MissionDrone(): BelongsTo
    {
        return  $this->belongsTo(CaptureDrone::class, 'capture_drone_id', 'id');
    }
    public function MissionDuck(): BelongsTo
    {
        return $this->belongsTo(PrimordialDuck::class, 'primordial_duck_id', "id");
    }
}
