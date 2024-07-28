<?php
    

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Stringable;
use Carbon\Carbon;
use App\Models\CommandLog;


// Schedule your commands
app()->booted(function () {
    $schedule = app(Schedule::class);
    scheduleCommand($schedule, 'wb:get-products')->hourly();
    scheduleCommand($schedule, 'wb:get-stocks')->hourly();
    scheduleCommand($schedule, 'wb:get-orders')->hourly();
    // scheduleCommand($schedule, 'app:test')->everyMinute();
    // scheduleCommand($schedule, 'wb:create-abc-report')->everyMinute();
});


/**
 * Logging Commands to BASE
 *
 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
 * @param  string  $command
 * @return \Illuminate\Console\Scheduling\Event
 */
function scheduleCommand(Schedule $schedule, string $command)
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