FROM php:8.2-cli

# Instalar dependencias
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    libzip-dev \
    libpq-dev \
    && docker-php-ext-install zip pdo pdo_mysql

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar proyecto
WORKDIR /app
COPY . .

# Instalar dependencias Laravel
RUN composer install

# Exponer puerto
EXPOSE 8080

# Arrancar servidor + migraciones
CMD ["sh", "-c", "php artisan config:clear && php artisan cache:clear && php artisan optimize:clear && php artisan migrate --force && php -S 0.0.0.0:8080 -t public"]