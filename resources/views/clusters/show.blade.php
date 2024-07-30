    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        /* Добавьте классы для каждого типа столбца */
        .total-column {
            background-color: #ffcccc;
            /* Цвет для общих столбцов */
        }

        .krasnodar-column {
            background-color: #ccffcc;
            /* Цвет для Краснодара */
        }

        .koledino-column {
            background-color: #ccccff;
            /* Цвет для Электростали */
        }

        .default-column {
            background-color: #f2f2f2;
            /* Цвет по умолчанию для остальных */
        }
    </style>
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Кластер Развернутый</h2>
        </x-slot>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <table class="table-auto w-full border border-gray-200 rounded-lg shadow-sm">
                        <thead class="bg-gray-100 text-gray-600">
                            <tr>
                                <th class="px-4 py-2 border-b">№</th>
                                <th class="px-4 py-2 border-b">Артикул</th>
                                <th class="px-4 py-2 border-b total-column">Итог по складам (Общий)</th>
                                <th class="px-4 py-2 border-b total-column">Заказов за 30 дней (Общий)</th>
                                <th class="px-4 py-2 border-b total-column">Среднее в день (Общий)</th>
                                <th class="px-4 py-2 border-b total-column">Закончится через (Общий)</th>
                                <th class="px-4 py-2 border-b total-column">Дозаказ (Общий)</th>
                                @if (isset($data[0]))
                                    @foreach (array_keys($data[array_key_first($data)]) as $cluster)
                                        @if ($cluster !== 'vendorCode' && $cluster !== 'main')
                                            @php
                                                $class = strtolower($cluster) . '-column';
                                            @endphp
                                            <th class="px-4 py-2 border-b {{ $class }}">Итог по складам
                                                ({{ ucfirst($cluster) }})
                                            </th>
                                            <th class="px-4 py-2 border-b {{ $class }}">Заказов за 30 дней
                                                ({{ ucfirst($cluster) }})</th>
                                            <th class="px-4 py-2 border-b {{ $class }}">Среднее в день
                                                ({{ ucfirst($cluster) }})</th>
                                            <th class="px-4 py-2 border-b {{ $class }}">Закончится через
                                                ({{ ucfirst($cluster) }})</th>
                                            <th class="px-4 py-2 border-b {{ $class }}">Дозаказ
                                                ({{ ucfirst($cluster) }})
                                            </th>
                                        @endif
                                    @endforeach
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @if (!empty($data))
                                @foreach ($data as $index => $product)
                                    <tr>
                                        <td class="px-4 py-2 border-b">{{ (int) $index + 1 }}</td>
                                        <td class="px-4 py-2 border-b">{{ $product['vendorCode'] }}</td>
                                        <td class="px-4 py-2 border-b total-column">
                                            {{ $product['main']['totalStock'] }}</td>
                                        <td class="px-4 py-2 border-b total-column">
                                            {{ $product['main']['orders30Days'] }}</td>
                                        <td class="px-4 py-2 border-b total-column">
                                            {{ number_format($product['main']['avgPerDay'], 2) }}</td>
                                        <td class="px-4 py-2 border-b total-column">
                                            {{ number_format($product['main']['daysToFinish'], 2) }}</td>
                                        <td class="px-4 py-2 border-b total-column">
                                            {{ number_format($product['main']['reorder'], 2) }}</td>
                                        @foreach ($product as $cluster => $values)
                                            @if ($cluster !== 'vendorCode' && $cluster !== 'main')
                                                @php
                                                    $class = strtolower($cluster) . '-column';
                                                @endphp
                                                <td class="px-4 py-2 border-b {{ $class }}">
                                                    {{ $values['clusterStock'] }}</td>
                                                <td class="px-4 py-2 border-b {{ $class }}">
                                                    {{ $values['clusterOrders30Days'] }}</td>
                                                <td class="px-4 py-2 border-b {{ $class }}">
                                                    {{ number_format($values['clusterAvgPerDay'], 2) }}</td>
                                                <td class="px-4 py-2 border-b {{ $class }}">
                                                    {{ number_format($values['clusterDaysToFinish'], 2) }}</td>
                                                <td class="px-4 py-2 border-b {{ $class }}">
                                                    {{ number_format($values['clusterReorder'], 2) }}</td>
                                            @endif
                                        @endforeach
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="100%" class="px-4 py-2 border-b text-center">Нет данных для отображения
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </x-app-layout>
