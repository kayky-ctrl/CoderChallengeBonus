<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Superpower extends Model
{
    protected $fillable = [
        'name',
        'description',
        'classifications',
        'primordial_duck_id'
    ];
    protected $casts = [
        'classifications' => 'array',
    ];
    protected $hidden = [
        'updated_at',
        'created_at',
        'primordial_duck_id',
    ];

    public function primordialDuckSuperPower(): BelongsTo
    {
        return $this->belongsTo(PrimordialDuck::class);
    }

    public function SuperpowerDefense(): BelongsToMany
    {
        return $this->belongsToMany(DefenseSystem::class, 'superpower_counters', 'superpower_id', 'defense_system_id')
            ->withPivot('effectiveness_notes')
            ->withTimestamps();
    }
}
