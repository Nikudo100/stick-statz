<?php


use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Stringable;
use Carbon\Carbon;
use App\Models\CommandLog;

$now = Carbon::now()->startOfDay()->format('Y-m-d');

// Команды в кроне
app()->booted(function () use ($now) {
    $schedule = app(Schedule::class);
    // Команда собирающия продукты каждый час
    scheduleCommand($schedule, 'wb:get-products')->hourly();
    // Команда собирающия остатки каждый час
    scheduleCommand($schedule, 'wb:get-stocks')->hourly();
    // Команда собирающия закзазы за сегодня каждый час
    scheduleCommand($schedule, "wb:get-orders {$now} 1")->hourly();
    // Команда собирающия отзывы каждый час
    scheduleCommand($schedule, "wb:get-feedbacks")->hourly();

    // Команда собирающия заказы за последние 33 дня от каждого дня (использует Jobs) время выполнения ~33минут
    scheduleCommand($schedule, 'wb:get-orders-last-month')->monthly();
    // Команда собирающия заказы за последние 33 дня по дате последнего изменения заказов
    scheduleCommand($schedule, 'wb:get-orders')->monthlyOn(1, '01:00');

    // Команда генерирует отчет АBC в Понедельник 00:00
    scheduleCommand($schedule, 'wb:create-abc-report')->weeklyOn(1, '00:00');
    // Команда генерирует отчет Оборачиваемость  в Понедельник 00:00
    scheduleCommand($schedule, 'wb:create-turnover-report')->weeklyOn(1, '00:00');
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
