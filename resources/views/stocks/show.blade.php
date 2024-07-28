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
    </style>
    <h1>Остатки</h1>
    <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
        <table>
            <thead>
                <tr>
                    <th>№</th>
                    <th>Артикул</th>
                    <th>Наименование</th>
                    <th>Категория</th>
                    <th>Итог по складам (Общий)</th>
                    <th>В пути к клиенту</th>
                    <th>В пути от клиента</th>
                    @if (!empty($warehouses))
                        @foreach ($warehouses as $warehouse)
                            <th>{{ $warehouse['warehouse_name'] }}</th>
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
                        <td>{{ $product['title'] }}</td>
                        <td>{{ $product['category'] }}</td>
                        <td>{{ $product['total_amount'] }}</td>
                        <td>{{ $product['in_way_to_client'] }}</td>
                        <td>{{ $product['in_way_from_client'] }}</td>
                        @foreach ($product['warehouses'] as $warehouse)
                            <td>{{ $warehouse['total_amount'] }}</td>
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
