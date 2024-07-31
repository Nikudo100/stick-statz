<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold">Обораиваемость</h1>
        <br>
        <!-- Tooltip -->
        {{-- <div class="relative flex items-center mb-4">
            <div class="bg-black text-white text-sm rounded-lg p-2 absolute top-0 left-0 z-10">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-yellow-300 mr-2"></div>
                    <span>Недотарили (когда меньше 30)</span>
                </div>
                <div class="flex items-center mt-2">
                    <div class="w-4 h-4 bg-green-200 mr-2"></div>
                    <span>Все в норме (когда от 30 до 60)</span>
                </div>
                <div class="flex items-center mt-2">
                    <div class="w-4 h-4 bg-red-200 mr-2"></div>
                    <span>Перетарили (когда больше 60)</span>
                </div>
            </div>
        </div> --}}

        <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
            <table class="table-auto w-full border border-gray-200 rounded-lg shadow-sm">
                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="px-4 py-2 border-b">№</th>
                        <th class="px-4 py-2 border-b">Артикул</th>
                        <th class="px-4 py-2 border-b">Картинка</th>
                        <th class="px-4 py-2 border-b">Наименование</th>
                        <th class="px-4 py-2 border-b">Категория</th>
                        @if (!empty($turnover) && isset(reset($turnover)['reports']))
                            @php
                                $firstItem = true;
                            @endphp
                            @foreach (reset($turnover)['reports'] as $date => $report)
                                @if ($firstItem)
                                    <th class="px-4 py-2 border-b">Статус {{ Carbon\Carbon::parse($date)->format('d-m-Y H:i') }}</th>
                                    @php
                                        $firstItem = false;
                                    @endphp
                                @endif
                                <th class="px-4 py-2 border-b">Значение {{ Carbon\Carbon::parse($date)->format('d-m-Y H:i') }}</th>
                            @endforeach
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @php
                        $index = 0;
                    @endphp
                    @if (!empty($turnover))
                        @foreach ($turnover as $item)
                            @php
                                $index++;
                            @endphp
                            <tr>
                                <td class="px-4 py-2 border-b">{{ $index }}</td>
                                <td class="px-4 py-2 border-b">{{ $item['vendorCode'] }}</td>
                                <td class="px-4 py-2 border-b"><img src="{{ $item['img'] }}" alt="Product Image" width="50"></td>
                                <td class="px-4 py-2 border-b">{{ $item['title'] }}</td>
                                <td class="px-4 py-2 border-b">{{ $item['category'] }}</td>
                                @php
                                    $firstReport = true;
                                @endphp
                                @foreach ($item['reports'] as $date => $report)
                                    @if ($firstReport)
                                        <td class="px-4 py-2 border-b">{{ $report['status'] }}</td>
                                        @php
                                            $firstReport = false;
                                        @endphp
                                    @endif
                                    @php
                                        $valueColor = '';
                                        if ($report['value'] < 30) {
                                            $valueColor = 'bg-yellow-300';
                                        } elseif ($report['value'] >= 30 && $report['value'] <= 60) {
                                            $valueColor = 'bg-green-200';
                                        } else {
                                            $valueColor = 'bg-red-200';
                                        }
                                    @endphp
                                    <td class="px-4 py-2 border-b {{ $valueColor }}">{{ $report['value'] }}</td>
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
