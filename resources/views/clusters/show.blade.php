<x-app-layout>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        /* Добавьте классы для каждого типа столбца */
        .total-column {
            background-color: #ffcccc; /* Цвет для общих столбцов */
        }
        .krasnodar-column {
            background-color: #ccffcc; /* Цвет для Краснодара */
        }
        .koledino-column {
            background-color: #ccccff; /* Цвет для Электростали */
        }
        .default-column {
            background-color: #f2f2f2; /* Цвет по умолчанию для остальных */
        }
    </style>
    <h1>Кластер Развернутый</h1>
    <br>
    <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
        <table>
            <thead>
                <tr>
                    <th>№</th>
                    <th>Артикул</th>
                    <th class="total-column">Итог по складам (Общий)</th>
                    <th class="total-column">Заказов за 30 дней (Общий)</th>
                    <th class="total-column">Среднее в день (Общий)</th>
                    <th class="total-column">Закончится через (Общий)</th>
                    <th class="total-column">Дозаказ (Общий)</th>
                    @if (isset($data[0]))
                        @foreach (array_keys($data[array_key_first($data)]) as $cluster)
                            @if ($cluster !== 'vendorCode' && $cluster !== 'main')
                                @php
                                    $class = strtolower($cluster) . '-column';
                                @endphp
                                <th class="{{ $class }}">Итог по складам ({{ ucfirst($cluster) }})</th>
                                <th class="{{ $class }}">Заказов за 30 дней ({{ ucfirst($cluster) }})</th>
                                <th class="{{ $class }}">Среднее в день ({{ ucfirst($cluster) }})</th>
                                <th class="{{ $class }}">Закончится через ({{ ucfirst($cluster) }})</th>
                                <th class="{{ $class }}">Дозаказ ({{ ucfirst($cluster) }})</th>
                            @endif
                        @endforeach
                    @endif
                </tr>
            </thead>
            <tbody>
                @if (!empty($data))
                    @foreach ($data as $index => $product)
                    <tr>
                        <td>{{ (int)$index + 1 }}</td>
                        <td>{{ $product['vendorCode'] }}</td>
                        <td class="total-column">{{ $product['main']['totalStock'] }}</td>
                        <td class="total-column">{{ $product['main']['orders30Days'] }}</td>
                        <td class="total-column">{{ number_format($product['main']['avgPerDay'], 2) }}</td>
                        <td class="total-column">{{ number_format($product['main']['daysToFinish'], 2) }}</td>
                        <td class="total-column">{{ number_format($product['main']['reorder'], 2) }}</td>
                        @foreach ($product as $cluster => $values)
                            @if ($cluster !== 'vendorCode' && $cluster !== 'main')
                                @php
                                    $class = strtolower($cluster) . '-column';
                                @endphp
                                <td class="{{ $class }}">{{ $values['clusterStock'] }}</td>
                                <td class="{{ $class }}">{{ $values['clusterOrders30Days'] }}</td>
                                <td class="{{ $class }}">{{ number_format($values['clusterAvgPerDay'], 2) }}</td>
                                <td class="{{ $class }}">{{ number_format($values['clusterDaysToFinish'], 2) }}</td>
                                <td class="{{ $class }}">{{ number_format($values['clusterReorder'], 2) }}</td>
                            @endif
                        @endforeach
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="100%">Нет данных для отображения</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</x-app-layout>
