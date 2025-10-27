<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Weaknesses extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];
    protected $hidden =[
        'updated_at',
        'created_at'
    ];
    public function WeaknessDucks(): BelongsToMany
    {
        return $this->belongsToMany(PrimordialDuck::class, 'duck_weaknesses', 'weakness_id', 'primordial_duck_id')
            ->withPivot('discovered_by')
            ->withTimestamps();
    }
}
