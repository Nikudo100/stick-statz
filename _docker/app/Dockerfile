FROM php:8.2-fpm

ENV TZ=Europe/Moscow

# Установка необходимых пакетов и расширений PHP
RUN apt-get update && apt-get install -y \
    apt-utils \
    locales \
    tzdata \
    libpq-dev \
    libpng-dev \
    libzip-dev \
    zip unzip \
    git \
    curl

# Установка Node.js (последняя стабильная версия 20.x)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs

# Установка глобальных npm пакетов
RUN npm install -g npm \
    && npm install -g pnpm \
    && npm install -g bun

# Установка PHP расширений
RUN docker-php-ext-install pdo_pgsql pgsql pdo_mysql bcmath gd zip \
    && pecl install redis \
    && docker-php-ext-enable redis

# Очистка
RUN apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Настройка локали и временной зоны
RUN echo "en_US.UTF-8 UTF-8" > /etc/locale.gen \
    && locale-gen \
    && ln -sf /usr/share/zoneinfo/Europe/Moscow /etc/localtime \
    && echo "Europe/Moscow" > /etc/timezone

# Копирование php.ini
COPY ./_docker/app/php.ini /usr/local/etc/php/conf.d/php.ini

# Установка Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- \
    --filename=composer \
    --install-dir=/usr/local/bin

WORKDIR /var/www

# Установка прав доступа
RUN chmod -R 777 ./
