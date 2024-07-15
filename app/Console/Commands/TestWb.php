<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Facades\WbApi;


class TestWb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-wb';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Fetching data from WB API...");

        $response = WbApi::getCardsList();

        if ($response) {
            $this->info("Data fetched successfully!");
            $this->info(print_r($response, true));
        } else {
            $this->error("Failed to fetch data from WB API.");
        }
    }
}
