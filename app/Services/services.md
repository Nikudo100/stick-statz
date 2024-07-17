# App\Services

> Сервисы поделены на три типа
1. Business (Логика)
2. Management (Управление)
3. Fetch (Получение данных по API)


<!-- @deprecated
> Основная бизнес-логика приложения, которая напрямую 
> не входит в обработку HTTP запросов
> 
> Это основное отличие от `App\Http\Services`

## Внутренняя структура

- **Builders** - конструкторы отчетов
- **Interfaces** - интерфейсы 
- **Collections** - коллекции 
- **Traits** - трейты

### Абстракции:
- **AbstractBuilder.php** - абстракция конструктора
- **AbstractMetadater.php** - абстракция конструктора мета-полей
- **AbstractExporter.php** - абстракция экспорта в Excel

### Коллекции:
- **FetchedDataCollection** - полученные из API данные
- **ProcessedDataCollection** - подготовленные данные для отчета
- **MetadataCollection** - коллекция мета-данных

### Трейты:

Вынес установку токенов в трейты:
- **WithOzonToken** - метод setToken устанавливающий токен для Ozon 
- **WithWildberriesToken** метод setToken для Wildberries


### Конструкторы отчетов (Builders)

Внутренняя структура конструктора следующая:
- `{отчет}` (ex. StocksReport)
  - `{маркетплейс}` *(ex. Wildberries)*
    - `Builder` - главный класс конструктора
    - `Processor` - обработчик ответа из API
    - `Metadater` - конструктор мета-данных
    - Папка `Processors` - декомпозиция обработки запроса

---
## Жизненный цикл

1. Вызов конструктора `Builder::getReport() <array>`
2. Получение данных из API `Builder::fetchData() <FetchedDataCollection>`
3. Постобработка данных `Builder::prepareData() <ProcessedDataCollection>` 
   1. `Processor::processData($fetchedData) <ProcessedDataCollection>`
   2. Внутри `Processor::class` вызываются подпроцессоры из папки `Processors`
4. Получение метаданных `Metadater::getMetadata() <MetadataCollection>`

 -->
