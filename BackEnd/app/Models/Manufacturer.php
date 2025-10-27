<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Manufacturer extends Model
{
    protected $fillable =[
        'name',
        'country_of_origin',
    ];
    protected $hidden =[
        'updated_at',
        'created_at'
    ];
    public function Drones():HasMany{
        return $this->hasMany(SurveyDrone::class, 'manufacturer_id');
    }
}
    