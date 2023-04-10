<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    protected $table = 'entry';
    public $timestamps = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'image',
        'number',
        'points',
        'state',
        'team',
        'car_id',
        'car_class_id',
        'series_id'
    ];
}
