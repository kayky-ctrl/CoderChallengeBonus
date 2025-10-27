<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SurveyDrone extends Model
{
    protected $fillable=[
        'serial_number',
        'brand',
        'manufacturer_id'
    ];

    protected $hidden =[
        'updated_at',
        'created_at'
    ];
    public function sighting_logs() :HasMany{
        return $this->hasMany(SightingLog::class, 'survey_drone_id');
    }
    public function manufacturers(): BelongsTo{
        return $this->belongsTo(Manufacturer::class);
    }
}
