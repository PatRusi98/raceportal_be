<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $table = 'result';
    public $timestamps = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'best_lap',
        'lap_count',
        'points',
        'position',
        'position_inclass',
        'total_time',
        'total_time_with_penalties',
        'entry_id',
        'session_id',
        'last_lap',
    ];
}
