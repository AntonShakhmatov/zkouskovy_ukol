# Используем официальный PHP-образ с версией 8.1 и поддержкой FPM
FROM php:8.2-fpm

# Установим системные зависимости
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo pdo_mysql zip

# Установим Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER=1

# Копируем код проекта в контейнер
COPY . /var/www/symfony

# Устанавливаем рабочую директорию
WORKDIR /var/www/symfony

# Устанавливаем права на запись в директорию var
RUN ls -la /var/www/symfony && \
    mkdir -p /var/www/symfony/var && \
    chown -R www-data:www-data /var/www/symfony/var

# Сначала обновить зависимости Symfony
# RUN composer update

# Установим зависимости Symfony
RUN composer install

# Указываем порт, который будет слушать контейнер
EXPOSE 9000
