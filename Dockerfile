FROM php:8.1-apache

# Устанавливаем системные утилиты и зависимости
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libmagickwand-dev \
    libzip-dev \
    --no-install-recommends && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) gd pdo pdo_mysql zip && \
    pecl install imagick-3.7.0 && \
    docker-php-ext-enable imagick && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Включение mod_rewrite для Apache
RUN a2enmod rewrite

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
# Копирование только необходимых файлов для composer
COPY composer.json composer.lock ./

# Установка зависимостей (с оптимизацией для production)
RUN composer install --no-dev --no-interaction --optimize-autoloader

# Копирование остальных файлов проекта
COPY . .

# Установка прав
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 775 /var/www/html/runtime /var/www/html/web/assets

