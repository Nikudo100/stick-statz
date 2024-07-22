<?php

namespace App\Services\Fetch\Wb;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GetStocks
{
    protected $baseUrl = 'https://statistics-api.wildberries.ru/api/v1/supplier/stocks';
    protected $headers = [
        'Authorization' => 'eyJhbGciOiJFUzI1NiIsImtpZCI6IjIwMjQwNTA2djEiLCJ0eXAiOiJKV1QifQ.eyJlbnQiOjEsImV4cCI6MTczMzUwOTgyMywiaWQiOiJlN2Q5ZGNkYi01YjEzLTQxOTktYmM1Mi05YjhjNzQ4ZjU5ZDciLCJpaWQiOjIzMDk2MTM3LCJvaWQiOjc5NzQ2LCJzIjo0MDk0LCJzaWQiOiJlNDRhZTk0ZS03NjVjLTVjMjMtYTZlNC0xYmE4NTY4MzUzNmYiLCJ0IjpmYWxzZSwidWlkIjoyMzA5NjEzN30.dtHHMviZ6QKhYRoBfLw19riROVcmdXhXNlohsPXeFXKUCwLjngIKWbPsLWHWYuuxkvXkrnHlgRj3Ia0UmYZocA',
    ];

    public function fetchStocks()
    {
        $url = $this->baseUrl . '?dateFrom=2000-01-01';

        $response = Http::withHeaders($this->headers)
            ->timeout(240)
            ->retry(3, 2000)
            ->get($url);

        if ($response->successful()) {
            return $response->json();
        }

        Log::error("Failed to fetch data from WB API", ['response' => $response->body()]);
        return null;
    }
}
