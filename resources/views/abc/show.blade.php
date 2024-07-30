<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold mb-6">ABC Анализ</h1>
        <br>
        <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
            <table class="table-auto w-full border border-gray-200 rounded-lg shadow-sm">
                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="px-4 py-2 border-b">№</th>
                        <th class="px-4 py-2 border-b">Артикул</th>
                        <th class="px-4 py-2 border-b">Картинка</th>
                        <th class="px-4 py-2 border-b">Наименование</th>
                        <th class="px-4 py-2 border-b">Категория</th>
                        @if (!empty($abc) && isset(reset($abc)['reports']))
                            @foreach (reset($abc)['reports'] as $date => $report)
                                <th class="px-4 py-2 border-b">Значение {{ Carbon\Carbon::parse($date)->format('d-m-Y H:i') }}</th>
                                <th class="px-4 py-2 border-b">Статус {{ Carbon\Carbon::parse($date)->format('d-m-Y H:i') }}</th>
                            @endforeach
                        @endif  
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @php
                        $index = 0;
                    @endphp
                    @if (!empty($abc))
                        @foreach ($abc as $item)
                            @php
                                $index++;
                            @endphp
                            <tr>
                                <td class="px-4 py-2 border-b">{{ $index }}</td>
                                <td class="px-4 py-2 border-b">{{ $item['vendorCode'] }}</td>
                                <td class="px-4 py-2 border-b"><img src="{{ $item['img'] }}" alt="Product Image" width="50"></td>
                                <td class="px-4 py-2 border-b">{{ $item['title'] }}</td>
                                <td class="px-4 py-2 border-b">{{ $item['category'] }}</td>
                                @foreach ($item['reports'] as $report)
                                    <td class="px-4 py-2 border-b">{{ $report['value'] }}</td>
                                    <td class="px-4 py-2 border-b">{{ $report['status'] }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="px-4 py-2 border-b text-center">Нет данных для отображения</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
