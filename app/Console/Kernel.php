<?php

namespace App\Console;

use Carbon\Carbon;
use App\Models\ReportSetting;
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
        // $schedule->command('wb:parse-feedback')->hourlyAt('15');
        // $schedule->command('wb:parse-statistics')->hourlyAt('20');
        // $schedule->command('wb:parse-cards-lists')->hourlyAt('25');
        // $schedule->command('wb:parse-stocks')->hourlyAt('5');
        // $schedule->command('wb:parse-orders')->hourlyAt('7');
        // // доделать команду, тк она затирает коменты
        // // $schedule->command('wb:add-not-empty-warehouses-to-stocks-fields')->hourlyAt('8');
        // $schedule->command('wb:parse-sales')
        // ->hourlyAt('7')
        // ;
        // $schedule->command('wb:parse-supplier-orders')->hourlyAt('10');

        // // Крон для реалтайм ABC\Turnover
        // // $currentDateTime = Carbon::now()->format('Y-m-d\TH:i:s');
        // // $schedule->command("wb:abc_analysis_with_date '{$currentDateTime}' 1")->everyMinute();
        // // $schedule->command("wb:turnover_with_date '{$currentDateTime}' 1")->everyMinute();

        // ReportSetting::query()->where('is_realtime', 0)->get()->map(function ($x) use($schedule){
        //     $schedule->command("wb:{$x->slug}")->cron($x->cron);
        // });
        $this->scheduleCommand($schedule, 'wb:parse-feedback')->hourlyAt('15');
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
