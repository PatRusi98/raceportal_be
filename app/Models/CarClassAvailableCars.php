<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarClassAvailableCars extends Model
{
    protected $table = 'car_class_available_cars';
    public $timestamps = false;

    protected $fillable = [
        'car_class_id',
        'available_cars_id',
    ];
}
