<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLicenses extends Model
{
    protected $table = 'user_licenses';
    public $timestamps = false;

    protected $fillable = [
        'users_id',
        'licences_id',
    ];
}
