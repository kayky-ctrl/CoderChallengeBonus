<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DefenseSystem extends Model
{
    protected $fillable = [
        'name',
        'description',
        'resource_cost'
    ];
    protected $hidden =[
        'updated_at',
        'created_at'
    ];
    public function DefenseSuperpower(): BelongsToMany
    {
        return $this->belongsToMany(Superpower::class, 'superpower_counters', 'defense_system_id', 'superpower_id')
            ->withPivot('effectiveness_notes')
            ->withTimestamps();
    }
}
