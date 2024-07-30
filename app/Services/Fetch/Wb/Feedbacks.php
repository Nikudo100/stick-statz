<?php

namespace App\Services\Fetch\Wb;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Feedbacks
{
    protected $baseUrl = 'https://feedbacks-api.wildberries.ru/api/v1/';
    protected $headers = [
        'Authorization' => 'eyJhbGciOiJFUzI1NiIsImtpZCI6IjIwMjQwNTA2djEiLCJ0eXAiOiJKV1QifQ.eyJlbnQiOjEsImV4cCI6MTczMzUwOTgyMywiaWQiOiJlN2Q5ZGNkYi01YjEzLTQxOTktYmM1Mi05YjhjNzQ4ZjU5ZDciLCJpaWQiOjIzMDk2MTM3LCJvaWQiOjc5NzQ2LCJzIjo0MDk0LCJzaWQiOiJlNDRhZTk0ZS03NjVjLTVjMjMtYTZlNC0xYmE4NTY4MzUzNmYiLCJ0IjpmYWxzZSwidWlkIjoyMzA5NjEzN30.dtHHMviZ6QKhYRoBfLw19riROVcmdXhXNlohsPXeFXKUCwLjngIKWbPsLWHWYuuxkvXkrnHlgRj3Ia0UmYZocA',
    ];

    function getAllUnansweredFeedbacks()
    {
        $allFeedbacks = [];
        $take = 5000;
        $skip = 0;

        do {
            $data = $this->fetchFeedbacks(false, $take, $skip);

            if ($data["error"])
                return $allFeedbacks;

            $feedbacksData = $data["data"];
            $allFeedbacks = array_merge($allFeedbacks, $feedbacksData['feedbacks']);
            $skip += $take;
        } while ($skip <= $feedbacksData['countUnanswered']);

        return $allFeedbacks;
    }

    public function fetchFeedbacks($isAnswered = false, $take = 5000, $skip = 0)
    {
        $url = $this->baseUrl . 'feedbacks';
        $params = [
            'isAnswered' => $isAnswered,
            'take' => $take,
            'skip' => $skip
        ];

        $response = Http::withHeaders($this->headers)
            ->timeout(240)
            ->retry(3, 2000)
            ->get($url, $params);

        if ($response->successful()) {
            return $response->json();
        }

        Log::error("Failed to fetch data feedbacks from WB API", ['response' => $response->body()]);
        return null;
    }
}
