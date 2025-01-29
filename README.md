graph TD;
    A[Vue 3 (Bootstrap)] -- API запросы --> B(Laravel Backend);
    B -- Данные --> C[MySQL/PostgreSQL];
    B -- Очереди --> D[Redis / Laravel Queues];
    B -- Логирование --> E[Prometheus / Grafana];
    B -- Ошибки --> F[Sentry];
    B -- Запросы API --> G[OZON API];
    G -- Webhooks --> B;

    subgraph "Backend (Laravel + PHP 8+)"
        B
        D
        E
        F
    end

    subgraph "Infrastructure"
        C
        D
        E
        F
    end

    subgraph "External Services"
        G
    end
