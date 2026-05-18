FROM node:20-alpine AS node-builder

WORKDIR /app

COPY package.json ./
RUN npm install

COPY vite.config.js ./
COPY resources ./resources
COPY public ./public

RUN npm run build

FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    libzip-dev \
    libpq-dev \
    nginx \
    supervisor \
    && docker-php-ext-install zip pdo pdo_mysql pdo_pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

COPY --from=node-builder /app/public/build ./public/build

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache \
    && chmod -R 775 /app/storage /app/bootstrap/cache

COPY docker/nginx.conf /etc/nginx/sites-available/default
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 8080

CMD ["sh", "-c", \
    "php artisan migrate --force && \
     php artisan config:clear && \
     php artisan cache:clear && \
     php artisan optimize:clear && \
     supervisord -c /etc/supervisor/conf.d/supervisord.conf"]