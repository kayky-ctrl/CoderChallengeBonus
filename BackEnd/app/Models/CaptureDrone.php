<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CaptureDrone extends Model
{
    protected $fillable =[
        'designation',
        'status',
        'battery_percent',
        'fuel_percent',
        'integrity_percent'
    ];
    protected $hidden =[
        'updated_at',
        'created_at'
    ];
    public function CaptureDroneMission(): HasMany
    {
        return $this->hasMany(CaptureMission::class, 'capture_drone_id', 'id');
    }
}
