<?php

namespace App\Services\Fetch\Wb;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GetOrders
{
    protected $baseUrl = 'https://statistics-api.wildberries.ru/api/v1/supplier/orders';

    public function fetchOrders($dateFrom, $flag = 0)
    {
        echo 'dateFrom = '. $dateFrom . PHP_EOL;
        $url = $this->baseUrl;
        $headers = [
            'Authorization' => 'eyJhbGciOiJFUzI1NiIsImtpZCI6IjIwMjQwNTA2djEiLCJ0eXAiOiJKV1QifQ.eyJlbnQiOjEsImV4cCI6MTczMzUwOTgyMywiaWQiOiJlN2Q5ZGNkYi01YjEzLTQxOTktYmM1Mi05YjhjNzQ4ZjU5ZDciLCJpaWQiOjIzMDk2MTM3LCJvaWQiOjc5NzQ2LCJzIjo0MDk0LCJzaWQiOiJlNDRhZTk0ZS03NjVjLTVjMjMtYTZlNC0xYmE4NTY4MzUzNmYiLCJ0IjpmYWxzZSwidWlkIjoyMzA5NjEzN30.dtHHMviZ6QKhYRoBfLw19riROVcmdXhXNlohsPXeFXKUCwLjngIKWbPsLWHWYuuxkvXkrnHlgRj3Ia0UmYZocA',
        ];

        $params = [
            'dateFrom' => $dateFrom,
            'flag' => $flag
        ];

        $response = Http::withHeaders($headers)
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
