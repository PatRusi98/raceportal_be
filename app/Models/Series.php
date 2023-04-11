<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Series extends Model
{
    protected $table = 'series';
    public $timestamps = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'code',
        'color',
        'description',
        'image',
        'multiclass',
        'name',
        'registrations',
        'rules',
        'simulator',
        'state',
        'teams_enable',
    ];

    public function event(): HasMany {
        return $this->hasMany(Event::class);
    }

    public function carClass(): HasMany {
        return $this->hasMany(CarClass::class);
    }

    public function entry(): HasMany {
        return $this->hasMany(Entry::class);
    }
}
