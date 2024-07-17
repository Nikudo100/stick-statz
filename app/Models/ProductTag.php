<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTag extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'external_tag_id', 'name', 'color'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
