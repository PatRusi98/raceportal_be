<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Session extends Model
{
    protected $table = 'session';
    public $timestamps = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'type',
        'event_id',
    ];

    public function event(): BelongsTo {
        return $this->belongsTo(Event::class);
    }

    public function result(): HasMany {
        return $this->hasMany(Result::class);
    }
}

