<?php

namespace App\Services\Fetch\Wb;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Feedbacks
{
    protected $baseUrl = 'https://feedbacks-api.wildberries.ru/api/v1/';
    protected $headers = [];

    function setToken($token)
    {
        $this->headers["Authorization"] = $token;
    }

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

    public function editFeedback($id, $text)
    {
        $url = $this->baseUrl . 'feedbacks';
        $body = [
            'id' => $id,
            'text' => $text
        ];

        $response = Http::withHeaders($this->headers)
            ->timeout(240)
            ->retry(3, 2000)
            ->patch($url, $body);

        if ($response->successful()) {
            return $response->json();
        }

        Log::error("Failed to edit data feedbacks from WB API", ['response' => $response->body()]);
        return null;
    }
}
