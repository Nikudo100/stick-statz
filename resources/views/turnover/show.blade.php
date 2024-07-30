<x-app-layout>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <h1>Обораиваемость</h1>
        <br>
        <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
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
                                <td>{{ (int) $index + 1 }}</td>
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
</div>
</x-app-layout>
