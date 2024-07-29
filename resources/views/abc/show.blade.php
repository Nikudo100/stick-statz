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
    <h1>Abc Анализ</h1>
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
                    @if (!empty($abc) && isset($abc[0]['reports']))
                        @foreach ($abc[0]['reports'] as $date => $report)
                            <th>Значение ({{ $date }})</th>
                            <th>Статус ({{ $date }})</th>
                        @endforeach
                    @endif
                </tr>
            </thead>
            <tbody>
                @if (!empty($abc))
                    @foreach ($abc as $index => $item)
                        <tr>
                            <td>{{ (int)$index + 1 }}</td>
                            <td>{{ $item['vendorCode'] }}</td>
                            <td><img src="{{ $item['img'] }}" alt="Product Image" width="50"></td>
                            <td>{{ $item['title'] }}</td>
                            <td>{{ $item['category'] }}</td>
                            @foreach ($item['reports'] as $report)
                                <td>{{ $report['value'] }}</td>
                                <td>{{ $report['status'] }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="8">Нет данных для отображения</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</x-app-layout>
