<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'warehouseName',
        'countryName',
        'oblastOkrugName',
        'regionName',
        'nmId',
        'barcode',
        'category',
        'subject',
        'brand',
        'techSize',
        'incomeID',
        'isSupply',
        'isRealization',
        'totalPrice',
        'discountPercent',
        'spp',
        'forPay',
        'finishedPrice',
        'priceWithDisc',
        'saleID',
        'isCancel',
        'cancelDate',
        'orderType',
        'sticker',
        'gNumber',
        'srid',
        'product_id',
        'account_id',
        'warehouse_id',
        'date',
        'lastChangeDate'
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
