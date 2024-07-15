<?

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WbApiService
{
    protected $baseUrl = 'https://suppliers-api.wildberries.ru';

    public function getCardsList($cursor = null, $filter = ['withPhoto' => -1], $limit = 100)
    {
        $endpoint = '/content/v2/get/cards/list';
        $url = $this->baseUrl . $endpoint;

        $headers = [
            'Authorization' => 'eyJhbGciOiJFUzI1NiIsImtpZCI6IjIwMjQwNTA2djEiLCJ0eXAiOiJKV1QifQ.eyJlbnQiOjEsImV4cCI6MTczMzUwOTgyMywiaWQiOiJlN2Q5ZGNkYi01YjEzLTQxOTktYmM1Mi05YjhjNzQ4ZjU5ZDciLCJpaWQiOjIzMDk2MTM3LCJvaWQiOjc5NzQ2LCJzIjo0MDk0LCJzaWQiOiJlNDRhZTk0ZS03NjVjLTVjMjMtYTZlNC0xYmE4NTY4MzUzNmYiLCJ0IjpmYWxzZSwidWlkIjoyMzA5NjEzN30.dtHHMviZ6QKhYRoBfLw19riROVcmdXhXNlohsPXeFXKUCwLjngIKWbPsLWHWYuuxkvXkrnHlgRj3Ia0UmYZocA',
        ];

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

        $response = Http::withHeaders($headers)->post($url, $data);

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }
}
