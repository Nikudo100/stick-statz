<?php

namespace App\Console\Commands\Wb\Report;

use Illuminate\Console\Command;
use App\Services\Business\AbcReportService;

class CreateAbcReport extends Command
{
    protected $signature = 'wb:create-abc-report';
    protected $description = 'Generate ABC Analysis Report';

    public function handle(AbcReportService $AbcReportService)
    {
        $AbcReportService->generateReport();
        $this->info('ABC Analysis Report generated successfully.');
    }
}
