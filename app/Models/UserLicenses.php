<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserLicenses extends Model
{
    protected $table = 'user_licenses';
    public $timestamps = false;

    protected $fillable = [
        'users_id',
        'licences_id',
    ];

    public function user(): HasMany {
        return $this->hasMany(User::class);
    }

    public function license(): HasMany {
        return $this->hasMany(License::class);
    }
}
