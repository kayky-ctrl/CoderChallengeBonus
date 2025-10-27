<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuperpowerCounter extends Model
{
    protected $fillable = [
        'effectiveness_notes',
        'superpower_id',
        'defense_system_id',
    ];
    protected $hidden =[
        'updated_at',
        'created_at'
    ];
}
