<?php

namespace App\Console\Commands\Wb;

use App\Services\Management\FeedbackManager;
use Illuminate\Console\Command;

class GetStocks extends Command
{
    protected $signature = 'wb:get-feedbacks';
    protected $description = 'Get feedbaks from Wildberries and save to database';

    public function handle(FeedbackManager $feedbackManager)
    {
        $this->info('Syncing feedbaks from Wildberries...');
        $feedbackManager->syncUnansweredFeedbacks();
        $this->info('Feedbaks synced successfully!');
    }
}
