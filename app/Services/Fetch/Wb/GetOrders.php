<?php

namespace App\Services\Fetch\Wb;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GetOrders
{
    protected $baseUrl = 'https://statistics-api.wildberries.ru/api/v1/supplier/orders';
    protected $headers = [
        'Authorization' => 'eyJhbGciOiJFUzI1NiIsImtpZCI6IjIwMjQwNTA2djEiLCJ0eXAiOiJKV1QifQ.eyJlbnQiOjEsImV4cCI6MTczMzUwOTgyMywiaWQiOiJlN2Q5ZGNkYi01YjEzLTQxOTktYmM1Mi05YjhjNzQ4ZjU5ZDciLCJpaWQiOjIzMDk2MTM3LCJvaWQiOjc5NzQ2LCJzIjo0MDk0LCJzaWQiOiJlNDRhZTk0ZS03NjVjLTVjMjMtYTZlNC0xYmE4NTY4MzUzNmYiLCJ0IjpmYWxzZSwidWlkIjoyMzA5NjEzN30.dtHHMviZ6QKhYRoBfLw19riROVcmdXhXNlohsPXeFXKUCwLjngIKWbPsLWHWYuuxkvXkrnHlgRj3Ia0UmYZocA',
    ];

    public function fetchOrders($dateFrom, $flag = 0)
    {
        $url = $this->baseUrl;

        $params = [
            'dateFrom' => $dateFrom,
            'flag' => $flag
        ];

        $response = Http::withHeaders($this->headers)
            ->timeout(120)
            ->retry(4, 60000) // 3 раза через минуту
            ->get($url, $params);

        if ($response->successful()) {
            echo 'fetching done';
            return $response->json();
        }

        Log::error("Failed to fetch orders from WB API", ['response' => $response->body()]);

        return null;
    }
}
