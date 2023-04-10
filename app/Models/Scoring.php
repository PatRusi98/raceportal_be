<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scoring extends Model
{
    protected $table = 'scoring';
    public $timestamps = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'race_scoring_cars',
        'qualification_scoring_cars',
        'fl_scoring_cars',
    ];

    public function raceScoring()
    {
        return $this->hasMany(ScoringRaceScoring::class);
    }

    public function qualificationScoring()
    {
        return $this->hasMany(ScoringQualificationScoring::class);
    }

    public function flScoring()
    {
        return $this->hasMany(ScoringFlScoring::class);
    }
}

class ScoringRaceScoring extends Model
{
    protected $table = 'scoring_race_scoring';
    public $timestamps = false;
    protected $foreignKey = 'scoring_id';

    protected $fillable = [
        'race_scoring_key',
        'race_scoring',
    ];

    public function scoring()
    {
        return $this->belongsTo(Scoring::class);
    }
}

class ScoringQualificationScoring extends Model
{
    protected $table = 'scoring_qualification_scoring';
    public $timestamps = false;
    protected $foreignKey = 'scoring_id';

    protected $fillable = [
        'qualification_scoring_key',
        'qualification_scoring',
    ];

    public function scoring()
    {
        return $this->belongsTo(Scoring::class);
    }
}

class ScoringFlScoring extends Model
{
    protected $table = 'scoring_fl_scoring';
    public $timestamps = false;

    protected $foreignKey = 'scoring_id';

    protected $fillable = [
        'fl_scoring_key',
        'fl_scoring',
    ];

    public function scoring()
    {
        return $this->belongsTo(Scoring::class);
    }
}
