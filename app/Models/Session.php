<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $table = 'session';
    public $timestamps = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'type',
        'event_id',
    ];
}
