<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Остатки</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Общее</h1>
        <div class="row mb-4">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Всего на складах</h5>
                        <p class="card-text">27382</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">В пути к клиенту</h5>
                        <p class="card-text">8097</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">В пути от клиента</h5>
                        <p class="card-text">1642</p>
                    </div>
                </div>
            </div>
        </div>

        <h1>Остатки</h1>
        <div class="mb-4">
            <label for="statusFilter" class="form-label">Сортировка по статусу:</label>
            <select id="statusFilter" class="form-select">
                <option selected>Не выбрано...</option>
                <!-- Добавьте другие опции статуса -->
            </select>
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">№</th>
                    <th scope="col">Изображение</th>
                    <th scope="col">Название</th>
                    <th scope="col">Всего на складах</th>
                    <th scope="col">В пути к клиенту</th>
                    <th scope="col">В пути от клиента</th>
                    <th scope="col">Электросталь</th>
                    <th scope="col">Коледино</th>
                    <th scope="col">Краснодар</th>
                    <th scope="col">Казань</th>
                    <th scope="col">Невинномысск</th>
                    <th scope="col">Тула</th>
                    <th scope="col">Рязань (Пошеское)</th>
                    <th scope="col">Санкт-Петербург Уткин Завод</th>
                    <th scope="col">Атакент</th>
                    <th scope="col">Екатеринбург - Испытателей 14г</th>
                    <th scope="col">Белая дача</th>
                    <th scope="col">Подольск</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stocks as $index => $stock)
                    <tr>
                        <th scope="row">{{ $index + 1 }}</th>
                        <td><img src="{{ asset('path/to/image') }}" alt="Изображение" width="50"></td>
                        <td>
                            Категория: {{ $stock->product->category }}<br>
                            Название: {{ $stock->product->name }}<br>
                            Артикул: {{ $stock->supplier_article }}<br>
                            Артикул WB: {{ $stock->sku_external_id }}
                        </td>
                        <td>{{ $stock->amount }}</td>
                        <td>{{ $stock->in_way_to_client }}</td>
                        <td>{{ $stock->in_way_from_client }}</td>
                        <td>{{ $stock->warehouse->elektrostal ?? '-' }}</td>
                        <td>{{ $stock->warehouse->koledino ?? '-' }}</td>
                        <td>{{ $stock->warehouse->krasnodar ?? '-' }}</td>
                        <td>{{ $stock->warehouse->kazan ?? '-' }}</td>
                        <td>{{ $stock->warehouse->nevinnomyssk ?? '-' }}</td>
                        <td>{{ $stock->warehouse->tula ?? '-' }}</td>
                        <td>{{ $stock->warehouse->ryazan ?? '-' }}</td>
                        <td>{{ $stock->warehouse->spb ?? '-' }}</td>
                        <td>{{ $stock->warehouse->atakent ?? '-' }}</td>
                        <td>{{ $stock->warehouse->ekb ?? '-' }}</td>
                        <td>{{ $stock->warehouse->belaya_dacha ?? '-' }}</td>
                        <td>{{ $stock->warehouse->podolsk ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
