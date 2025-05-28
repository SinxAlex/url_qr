FROM php:8.1-apache

# Устанавливаем системные зависимости и Imagick
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libmagickwand-dev \
    --no-install-recommends && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) gd && \
    pecl install imagick-3.7.0 && \
    docker-php-ext-enable imagick && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*
# Устанавливаем PDO MySQL и другие часто нужные расширения
RUN docker-php-ext-install pdo pdo_mysql

# Включение mod_rewrite для Apache
RUN a2enmod rewrite

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Копирование проекта
WORKDIR /var/www/html
COPY . .

# Установка зависимостей Composer (без dev-зависимостей для production)
RUN composer install

# Установка прав на runtime и assets
RUN chmod -R 775 /var/www/html/runtime \
    && chmod -R 775 /var/www/html/web/assets \
    && chown -R www-data:www-data /var/www/html/runtime \
    && chown -R www-data:www-data /var/www/html/web/assets