<?php

namespace App\Services\Fetch\Wb;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GetOrders
{
    protected $baseUrl = 'https://statistics-api.wildberries.ru/api/v1/supplier/orders';

    public function fetchOrders($dateFrom, $flag = 0)
    {
        $url = $this->baseUrl;
        $headers = [
            'Authorization' => 'Your-API-Key-Here',
        ];

        $params = [
            'dateFrom' => $dateFrom,
            'flag' => $flag
        ];

        $response = Http::withHeaders($headers)
            ->timeout(120)
            ->get($url, $params);

        if ($response->successful()) {
            return $response->json();
        }

        Log::error("Failed to fetch orders from WB API", ['response' => $response->body()]);

        return null;
    }
}
