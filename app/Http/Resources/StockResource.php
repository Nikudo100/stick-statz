<?

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StockResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'sku_external_id' => $this->sku_external_id,
            'warehouse_id' => $this->warehouse_id,
            'product_id' => $this->product_id,
            'name' => $this->name,
            'quantityFull' => $this->quantityFull,
            'in_way_to_client' => $this->in_way_to_client,
            'in_way_from_client' => $this->in_way_from_client,
            'techSize' => $this->techSize,
            'price' => $this->price,
            'discount' => $this->discount,
        ];
    }
}
