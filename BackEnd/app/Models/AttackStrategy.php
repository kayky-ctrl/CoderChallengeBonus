<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttackStrategy extends Model
{
    protected $fillable =[
        'name',
        'description',
        'trigger_logic',
    ];
    protected $hidden =[
        'updated_at',
        'created_at'
    ];
    protected $casts = [
        'trigger_logic' => 'array', // <--- TEM QUE ESTAR EXATAMENTE ASSIM!
    ];
}
