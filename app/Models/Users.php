<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Users extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'user';
    public $timestamps = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'acc_first_name',
        'acc_last_name',
        'acc_short_name',
        'address',
        'avatar',
        'birth',
        'country',
        'iban',
        'licence_sams',
        'name',
        'phone',
        'role',
        'rre_id',
        'shirt',
        'steam_id',
        'username',
        'ac_first_name',
        'ac_last_name',
        'ac_short_name',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
