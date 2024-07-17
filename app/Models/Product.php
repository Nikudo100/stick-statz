<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['account_id', 'nmId', 'imtID', 'subjectID', 'vendorCode', 'brand_id', 'title', 'description', 'dimensions', 'price', 'article', 'images', 'price_base', 'discount_base', 'barcode', 'size', 'package_size', 'end_sale', 'status_id'];

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    
    public function abcAnalyses()
    {
        return $this->hasMany(AbcAnalysis::class);
    }

    public function turnovers()
    {
        return $this->hasMany(Turnover::class);
    }
}
