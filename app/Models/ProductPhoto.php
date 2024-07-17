<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPhoto extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'big', 'c246x328', 'c516x688', 'square', 'tm'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
