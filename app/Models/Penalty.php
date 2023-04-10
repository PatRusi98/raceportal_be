<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penalty extends Model
{
    protected $table = 'penalty';
    public $timestamps = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'cleared_lap',
        'value',
        'violation_lap',
        'result_id',
        'type',
        'penalty',
        'reason',
    ];
}
