<?php

namespace App\Services\Management;

use App\Services\Business\FeedbackService;
use App\Services\Fetch\Wb\Feedbacks;

class FeedbackManager
{
    protected $feedbackFetch;
    protected $feedbackService;

    public function __construct(Feedbacks $feedbackFetch, FeedbackService $feedbackService)
    {
        $this->feedbackFetch = $feedbackFetch;
        $this->feedbackService = $feedbackService;
    }

    public function syncUnansweredFeedbacks()
    {
        $feedbacks = $this->feedbackFetch->getAllUnansweredFeedbacks();
        if ($feedbacks) {
            $this->feedbackService->updateOrCreateFeedbacks($feedbacks);
        }
    }
}
