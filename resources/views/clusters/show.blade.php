<!DOCTYPE html>
<html>
<head>
    <title>Кластер Развернутый</title>
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
    </style>
</head>
<body>
    <h1>Кластер Развернутый</h1>
    <table>
        <thead>
            <tr>
                <th>№</th>
                <th>Артикул</th>
                <th>Итог по складам (Общий)</th>
                <th>Заказов за 30 дней (Общий)</th>
                <th>Среднее в день (Общий)</th>
                <th>Закончится через (Общий)</th>
                <th>Дозаказ (Общий)</th>
                @foreach (array_keys($data[0]) as $cluster)
                    @if ($cluster !== 'vendorCode' && $cluster !== 'main')
                        <th>Итог по складам ({{ ucfirst($cluster) }})</th>
                        <th>Заказов за 30 дней ({{ ucfirst($cluster) }})</th>
                        <th>Среднее в день ({{ ucfirst($cluster) }})</th>
                        <th>Закончится через ({{ ucfirst($cluster) }})</th>
                        <th>Дозаказ ({{ ucfirst($cluster) }})</th>
                    @endif
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $product)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $product['vendorCode'] }}</td>
                <td>{{ $product['main']['totalStock'] }}</td>
                <td>{{ $product['main']['orders30Days'] }}</td>
                <td>{{ number_format($product['main']['avgPerDay'], 2) }}</td>
                <td>{{ number_format($product['main']['daysToFinish'], 2) }}</td>
                <td>{{ number_format($product['main']['reorder'], 2) }}</td>
                @foreach ($product as $cluster => $values)
                    @if ($cluster !== 'vendorCode' && $cluster !== 'main')
                        <td>{{ $values['clusterStock'] }}</td>
                        <td>{{ $values['clusterOrders30Days'] }}</td>
                        <td>{{ number_format($values['clusterAvgPerDay'], 2) }}</td>
                        <td>{{ number_format($values['clusterDaysToFinish'], 2) }}</td>
                        <td>{{ number_format($values['clusterReorder'], 2) }}</td>
                    @endif
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
