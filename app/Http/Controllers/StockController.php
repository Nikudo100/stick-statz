<?
namespace App\Http\Controllers;

use App\Models\Stock;
use App\Http\Resources\StockResource;

class StockController extends Controller
{
    public function index()
    {
        $stocks = Stock::all();
        return StockResource::collection($stocks);
    }
}