<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DuckWeakness extends Model
{
    protected $fillable = [
        'discovered_by',
        'primordial_duck_id',
        'weakness_id',
    ];
    protected $hidden =[
        'updated_at',
        'created_at'
    ];

}
