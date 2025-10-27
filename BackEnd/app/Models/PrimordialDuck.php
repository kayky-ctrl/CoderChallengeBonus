<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use PhpParser\Node\Stmt\Return_;

class PrimordialDuck extends Model
{
    protected $fillable =[
        'designation',
        'height_cm',
        'weight_g',
        'last_known_city',
        'last_known_country',
        'last_known_lat',
        'last_known_lon',
        'gps_precision_m',
        'reference_point',
        'hibernation_status',
        'heart_rate_bpm',
        'mutation_count'
    ];
    protected $hidden =[
        'updated_at',
        'created_at'
    ];
    public function superpower():HasOne{
        return $this->hasOne(Superpower::class, 'primordial_duck_id');
    }
    public function duck_sighting_log():HasMany{
        return $this->hasMany(SightingLog::class, 'primordial_duck_id');
    }
    public function analysis():HasOne
    {
        return $this->hasOne(DuckAnalysis::class, 'primordial_duck_id');
    }
    public function DuckMission(): HasMany
    {
        return $this->hasMany(CaptureMission::class, 'primordial_duck_id', 'id');
    }
    public function DuckWeaknesses(): BelongsToMany
    {
        return $this->belongsToMany(Weaknesses::class, 'duck_weaknesses', 'primordial_duck_id', 'weakness_id')
            ->withPivot('discovered_by')
            ->withTimestamps();
    }
}
