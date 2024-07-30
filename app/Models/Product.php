<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        // Артикул WB
        'nmID',
        // Идентификатор КТ.
        // Артикулы WB из одной КТ будут иметь одинаковый imtID
        'imtID',
        // Внутренний технический идентификатор товара
        'nmUUID',
        // Артикул продавца
        'vendorCode',
        // Бренд
        'brand',
        // Наименование товара
        'title',
        // Описаниие
        'description',
        // Видео
        'video',
        // Габариты упаковки товара, см
        'dimensions',
        'subjectID',
        'account_id'
    ];

    protected $casts = [
        'dimensions' => 'array',
    ];

    // Product artibutes
    public function photos()
    {
        return $this->hasMany(ProductPhoto::class);
    }

    public function characteristics()
    {
        return $this->hasMany(ProductCharacteristic::class);
    }

    public function sizes()
    {
        return $this->hasMany(ProductSize::class);
    }

    public function tags()
    {
        return $this->hasMany(ProductTag::class);
    }
    // END Product artibutes

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
    public function category()
    {
        return $this->hasMany(ProductCategory::class, 'subjectID', 'external_cat_id');
    }
}
