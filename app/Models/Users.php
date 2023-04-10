<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Users extends Model implements JWTSubject
{
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
        'password',
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

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
