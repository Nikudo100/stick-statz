<?

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WbApiService
{
    public function getData()
    {
        // логика для получения данных из API WB
        $response = Http::get('api_endpoint');
        return $response->json();
    }
}
