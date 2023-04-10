<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warning extends Model
{
    protected $table = 'warning';
    public $timestamps = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'warning_text',
        'result_id',
    ];
}
