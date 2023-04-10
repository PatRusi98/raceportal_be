<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntryDrivers extends Model
{
    protected $table = 'entry_drivers';
    public $timestamps = false;

    protected $fillable = [
        'entry_id',
        'drivers_id',
    ];
}
