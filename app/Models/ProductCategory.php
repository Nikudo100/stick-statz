<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'external_cat_id'];

    public function products()
    {
        return $this->hasMany(Product::class, 'subjectID', 'external_cat_id');
    }
}
