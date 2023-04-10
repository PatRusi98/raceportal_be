<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    protected $table = 'track';
    public $timestamps = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
    ];
}
