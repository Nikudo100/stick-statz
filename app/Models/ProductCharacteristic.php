<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCharacteristic extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'external_char_id', 'name', 'value'];

    protected $casts = [
        'value' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
