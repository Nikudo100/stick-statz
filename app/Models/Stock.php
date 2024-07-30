<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'sku_external_id',
        'supplier_article',
        'warehouse_name',
        'warehouse_id',
        'product_id',
        'quantityFull',
        'in_way_to_client',
        'in_way_from_client',
        'techSize',
        'price',
        'discount',
        'account_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}

