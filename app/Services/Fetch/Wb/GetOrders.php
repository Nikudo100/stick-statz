<?php

namespace App\Services\Fetch\Wb;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GetOrders
{
    protected $baseUrl = 'https://statistics-api.wildberries.ru/api/v1/supplier/orders';
    protected $headers = [];

    function setToken($token)
    {
        $this->headers["Authorization"] = $token;
    }

    public function fetchOrders($dateFrom, $flag = 0)
    {
        $url = $this->baseUrl;

        $params = [
            'dateFrom' => $dateFrom,
            'flag' => $flag
        ];

        $response = Http::withHeaders($this->headers)
            ->timeout(240) 
            ->retry(3, 60000) // 3 раза через минуту
            ->get($url, $params);

        if ($response->successful()) {
            return $response->json();
        }

        Log::error("Failed to fetch orders from WB API", ['response' => $response->body()]);

        return null;
    }
}
