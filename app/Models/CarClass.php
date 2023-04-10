<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarClass extends Model
{
    protected $table = 'car_class';
    public $timestamps = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'color',
        'drivers_per_entry',
        'max_entries',
        'name',
        'need_sams_license',
        'scoring_id',
        'series_id',
        'acc_category_id',
    ];
}
