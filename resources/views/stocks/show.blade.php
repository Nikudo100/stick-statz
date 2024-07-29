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
    <h2>Сумма</h2>
    <table>
        <thead>
            <tr>
                <th>Итог по складам (Общий)</th>
                <th>В пути к клиенту</th>
                <th>В пути от клиента</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $sum['total_amount'] }}</td>
                <td>{{ $sum['in_way_to_client'] }}</td>
                <td>{{ $sum['in_way_from_client'] }}</td>
            </tr>

        </tbody>

    </table>

        <table>
            <thead>
                <tr>
                    <th>№</th>
                    <th>Артикул</th>
                    <th>Картинка</th>
                    <th>Наименование</th>
                    <th>Категория</th>
                    <th>Итог по складам</th>
                    <th>В пути к клиенту</th>
                    <th>В пути от клиента</th>
                    @if (!empty($warehouses))
                        @foreach ($warehouses as $warehouse)
                            <th>{{ $warehouse }}</th>
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
                        <td><img src="{{ $product['img'] }}" alt=""></td>
                        <td>{{ $product['title'] }}</td>
                        <td>{{ $product['category'] }}</td>
                        <td>{{ $product['total_amount'] }}</td>
                        <td>{{ $product['in_way_to_client'] }}</td>
                        <td>{{ $product['in_way_from_client'] }}</td>
                        @foreach ($warehouses as $warehouse)
                            @php
                                $warehouseAmount = '';
                            @endphp
                            @foreach ($product['warehouses'] as $pWarehouse)
                                @if ($warehouse == $pWarehouse['warehouse_name'])
                                    @php
                                        $warehouseAmount = $pWarehouse['total_amount'];
                                    @endphp
                                    @break
                                @endif
                            @endforeach
                            <td>{{ $warehouseAmount }}</td>
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
