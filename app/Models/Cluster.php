<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cluster extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug','sort', 'warehouse_ids', 'order_region_names'];

    protected $casts = [
        'warehouse_ids' => 'array',
        'order_region_names' => 'array',
    ];

        // Связь с регионами
        public function regions()
        {
            return $this->hasMany(Region::class);
        }
    
        // Связь со складами
        public function warehouses()
        {
            return $this->hasMany(Warehouse::class);
        }
}
