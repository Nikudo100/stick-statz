<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = ['name','cluster_id'];

    public function cluster()
    {
        return $this->belongsTo(Cluster::class);
    }
}
