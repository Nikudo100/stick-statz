<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSize extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'external_chrtID', 'techSize', 'wbSize', 'skus'];

    protected $casts = [
        'skus' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
