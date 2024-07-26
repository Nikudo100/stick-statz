<?php

namespace App\Jobs;

use App\Services\Fetch\Wb\GetOrders;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\JobsLog;
use Exception;

class SyncOrdersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $date;
    
    // Максимальное количество попыток
    public $tries = 3;
    
    // Интервал между повторными попытками в секундах
    public $backoff = 60;

    public function __construct($date)
    {
        $this->date = $date;
    }

    public function handle(GetOrders $getOrders)
    {
        try {
            $getOrders->fetchOrders($this->date, 1);
            $this->logStatus('success', "Orders synced for date {$this->date}", $this->date);
        } catch (Exception $e) {
            $this->logStatus('failure',$e->getMessage(), $this->date);
            throw $e;
        }
    }

    protected function logStatus($status, $details, $date)
    {
        JobsLog::create([
            'job' => 'SyncOrdersJob',
            'date' => $date,
            'status' => $status,
            'details' => $details
        ]);
    }
}
