<?php

namespace App\Services\Fetch\Wb;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GetCarts
{
    protected $baseUrl = 'https://suppliers-api.wildberries.ru';
    protected $headers = [
        'Authorization' => 'eyJhbGciOiJFUzI1NiIsImtpZCI6IjIwMjQwNTA2djEiLCJ0eXAiOiJKV1QifQ.eyJlbnQiOjEsImV4cCI6MTczMzUwOTgyMywiaWQiOiJlN2Q5ZGNkYi01YjEzLTQxOTktYmM1Mi05YjhjNzQ4ZjU5ZDciLCJpaWQiOjIzMDk2MTM3LCJvaWQiOjc5NzQ2LCJzIjo0MDk0LCJzaWQiOiJlNDRhZTk0ZS03NjVjLTVjMjMtYTZlNC0xYmE4NTY4MzUzNmYiLCJ0IjpmYWxzZSwidWlkIjoyMzA5NjEzN30.dtHHMviZ6QKhYRoBfLw19riROVcmdXhXNlohsPXeFXKUCwLjngIKWbPsLWHWYuuxkvXkrnHlgRj3Ia0UmYZocA',
    ];

    public function getAllCards()
    {
        $allCards = [];
        $cursor = null;

        do {
            $response = $this->getCardsList($cursor);
            
            if ($response && isset($response['cards'])) {
                $allCards = array_merge($allCards, $response['cards']);
                $cursor = [
                    'nmID' => $response['cursor']['nmID'] ?? null,
                    'updatedAt' => $response['cursor']['updatedAt'] ?? null,
                ];
                $limit = count($response['cards']);
            } else {
                $limit = 0;
            }
        } while ($limit >= 100);

        return $allCards;
    }

    private function getCardsList($cursor = null, $filter = ['withPhoto' => -1], $limit = 100)
    {
        $endpoint = '/content/v2/get/cards/list';
        $url = $this->baseUrl . $endpoint;

        $data = [
            'settings' => [
                'cursor' => [
                    'limit' => $limit,
                ],
                'filter' => $filter,
            ],
        ];

        if ($cursor) {
            $data['settings']['cursor'] = array_merge($data['settings']['cursor'], $cursor);
        }

        $response = Http::withHeaders($this->headers)
                        ->timeout(120) // Увеличение тайм-аута до 120 секунд
                        ->post($url, $data);

        if ($response->successful()) {
            return $response->json();
        }
        
        Log::error("Failed to fetch data from WB API", ['response' => $response->body()]);

        return null;
    }
}
