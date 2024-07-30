<?php

namespace App\Services\Fetch\Wb;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GetStocks
{
    protected $baseUrl = 'https://statistics-api.wildberries.ru/api/v1/supplier/stocks';
    protected $headers = [];

    function setToken($token)
    {
        $this->headers["Authorization"] = $token;
    }

    public function fetchStocks()
    {
        $url = $this->baseUrl;
        $params = [
            'dateFrom' => '2000-01-01'
        ];

        $response = Http::withHeaders($this->headers)
            ->timeout(240)
            ->retry(3, 2000)
            ->get($url, $params);

        if ($response->successful()) {
            return $response->json();
        }

        Log::error("Failed to fetch stocks data from WB API", ['response' => $response->body()]);
        return null;
    }
}
