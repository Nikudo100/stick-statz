<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <h1 class="text-2xl font-bold mb-6">Остатки</h1>
        <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
            <h2 class="text-xl font-semibold mb-4">Сумма</h2>
            <table class="table-auto w-full border border-gray-200 rounded-lg shadow-sm">
                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="px-2 py-2 border-b">Итог по складам (Общий)</th>
                        <th class="px-2 py-2 border-b">В пути к клиенту</th>
                        <th class="px-2 py-2 border-b">В пути от клиента</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <tr>
                        <td class="px-2 py-2 border-b">{{ $sum['total_amount'] }}</td>
                        <td class="px-2 py-2 border-b">{{ $sum['in_way_to_client'] }}</td>
                        <td class="px-2 py-2 border-b">{{ $sum['in_way_from_client'] }}</td>
                    </tr>
                </tbody>
            </table>

            <table class="table-auto w-full border border-gray-200 rounded-lg shadow-sm mt-6">
                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="px-2 py-2 border-b">№</th>
                        <th class="px-2 py-2 border-b">Артикул</th>
                        <th class="px-2 py-2 border-b">Картинка</th>
                        <th class="px-2 py-2 border-b">Наименование</th>
                        <th class="px-2 py-2 border-b">Категория</th>
                        <th class="px-2 py-2 border-b">Итог по складам</th>
                        <th class="px-2 py-2 border-b">В пути к клиенту</th>
                        <th class="px-2 py-2 border-b">В пути от клиента</th>
                        @if (!empty($warehouses))
                            @foreach ($warehouses as $warehouse)
                                <th class="px-2 py-2 border-b">{{ $warehouse }}</th>
                            @endforeach
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @if (!empty($data))
                        @foreach ($data as $index => $product)
                        <tr>
                            <td class="px-2 py-2 border-b">{{ (int)$index + 1 }}</td>
                            <td class="px-2 py-2 border-b">{{ $product['vendorCode'] }}</td>
                            <td class="px-2 py-2 border-b"><img src="{{ $product['img'] }}" alt="" class="w-16 h-16 object-cover"></td>
                            <td class="px-2 py-2 border-b">{{ $product['title'] }}</td>
                            <td class="px-2 py-2 border-b">{{ $product['category'] }}</td>
                            <td class="px-2 py-2 border-b">{{ $product['total_amount'] }}</td>
                            <td class="px-2 py-2 border-b">{{ $product['in_way_to_client'] }}</td>
                            <td class="px-2 py-2 border-b">{{ $product['in_way_from_client'] }}</td>
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
                                <td class="px-2 py-2 border-b">{{ $warehouseAmount }}</td>
                            @endforeach
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="{{ count($warehouses) + 8 }}" class="px-2 py-2 border-b text-center">Нет данных для отображения</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
