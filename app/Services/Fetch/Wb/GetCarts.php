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
        $cursor = [
            'nmID' => null,
            'updatedAt' => null,
        ];

        do {
            $response = $this->getCardsList($cursor);

            if ($response && isset($response['cards'])) {
                $allCards = array_merge($allCards, $response['cards']);
                $cursor['nmID'] = $response['cursor']['nmID'] ?? null;
                $cursor['updatedAt'] = $response['cursor']['updatedAt'] ?? null;
                $total = $response['cursor']['total'];
            } else {
                $total = 0;
            }
        } while ($total >= 100);

        return $allCards;
    }

    private function getCardsList($cursor = null, $filter = ['withPhoto' => -1], $limit = 100)
    {
        $url = $this->baseUrl . '/content/v2/get/cards/list';

        $data = [
            'settings' => [
                'cursor' => [
                    'limit' => $limit,
                    'nmID' => $cursor['nmID'] ?? null,
                    'updatedAt' => $cursor['updatedAt'] ?? null,
                ],
                'filter' => $filter,
            ],
        ];

        $response = Http::withHeaders($this->headers)
            ->timeout(240)
            ->retry(3, 2000)
            ->post($url, $data);

        if ($response->successful()) {
            return $response->json();
        }
        echo $response->body();
        Log::error("Failed to fetch data from WB API", ['response' => $response->body()]);

        return null;
    }
}
