<?php

namespace App\Console\Commands;

use Database\Seeders\RefFieldsSeeder;
use Illuminate\Console\Command;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command Test';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        echo 'Test'.PHP_EOL;
        // file_put_contents(__DIR__.'/test.txt', "test\n", FILE_APPEND);
    }
}
