<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'inn',
        'ogrn',
        'type',
        'name',
    ];

    function users()
    {
        return $this->hasMany(User::class);
    }

    function setting()
    {
        return $this->hasOne(Setting::class);
    }
}
