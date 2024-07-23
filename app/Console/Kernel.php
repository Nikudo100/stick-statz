<?php

namespace App\Console;

use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\CommandLog;
use Illuminate\Support\Stringable;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {

        $this->scheduleCommand($schedule, 'wb:get-products')->hourlyAt('15');
        $this->scheduleCommand($schedule, 'wb:get-orders')->hourlyAt('15');
        $this->scheduleCommand($schedule, 'wb:get-stocks')->hourlyAt('15');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

    /**
     * Helper function to schedule a command with logging.
     */
    protected function scheduleCommand(Schedule $schedule, string $command)
    {
        $scheduledCommand = $schedule->command($command)
            ->before(function () use ($command) {
                CommandLog::create([
                    'command' => $command,
                    'started_at' => Carbon::now(),
                    'status' => 'started'
                ]);
            })
            ->after(function (Stringable $output) use ($command) {
                $log = CommandLog::where('command', $command)->latest()->first();
                if ($log) {
                    $log->update([
                        'finished_at' => Carbon::now(),
                        'status' => 'success',
                        'output' => $output
                    ]);
                }
            })
            ->onFailure(function (Stringable $output) use ($command) {
                $log = CommandLog::where('command', $command)->latest()->first();
                if ($log) {
                    $log->update([
                        'finished_at' => Carbon::now(),
                        'status' => 'failure',
                        'output' => $output
                    ]);
                }
            });

        return $scheduledCommand;
    }
}
