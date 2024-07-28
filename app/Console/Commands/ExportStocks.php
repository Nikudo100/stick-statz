<?php

// app/Console/Commands/ExportStocks.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Stock;

class ExportStocks extends Command
{
    protected $signature = 'stocks:export';
    protected $description = 'Export stocks data to a file';

    public function handle()
    {
        // Извлечение всех данных из таблицы stocks
        $stocks = Stock::all();

        if ($stocks->isEmpty()) {
            $this->info('No data found in stocks table.');
            return 0;
        }

        // Преобразование данных в CSV формат
        $csvData = [];

        // Динамическое определение заголовков на основе первой записи
        $header = array_keys($stocks->first()->toArray());
        $csvData[] = $header;

        foreach ($stocks as $stock) {
            $csvData[] = $stock->toArray();
        }

        // Определение имени файла и его пути
        $filename = __DIR__ . '/stocks-dump.csv';

        // Запись данных в файл
        $fileContent = "";
        foreach ($csvData as $row) {
            $fileContent .= implode(",", $row) . "\n";
        }

        file_put_contents($filename, $fileContent, FILE_APPEND);

        $this->info('Stocks data exported successfully to ' . $filename);
        return 0;
    }
}
