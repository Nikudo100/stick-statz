<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommandLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'command',
        'started_at',
        'finished_at',
        'status',
        'output',
    ];
}
