<?php

namespace App\Services\Management;

use App\Models\Account;
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
        $accounts = Account::whereNotNull('wb_token')->get();

        foreach ($accounts as $key => $account) {
            $this->feedbackFetch->setToken($account->wb_toke);
            $feedbacks = $this->feedbackFetch->getAllUnansweredFeedbacks();
            if ($feedbacks) {
                $this->feedbackService->updateOrCreateFeedbacks($account, $feedbacks);
            }
        }

    }
}
