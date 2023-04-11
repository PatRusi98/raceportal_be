<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $table = 'event';
    public $timestamps = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'description',
        'image',
        'name',
        'practice_start',
        'qualify_start',
        'race_start',
        'state',
        'series_id',
        'code',
        'briefing',
    ];

    public function series(): BelongsTo {
        return $this->belongsTo(Series::class);
    }

    public function session(): HasMany {
        return $this->hasMany(Session::class);
    }
}
