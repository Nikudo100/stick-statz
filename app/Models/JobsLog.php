<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobsLog extends Model
{
    use HasFactory;

    protected $fillable = ['job', 'date', 'status', 'details'];
}
