<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $table = 'participant';
    public $timestamps = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'firstname',
        'lastname',
        'steam_id',
        'result_id',
        'user_id',
    ];
}
