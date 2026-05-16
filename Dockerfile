# =============================================================================
# Stage 1: Node.js — build frontend assets (Vite + Tailwind)
# =============================================================================
FROM node:20-alpine AS node-builder

WORKDIR /app

# Copy only the files needed for the JS build
COPY package.json ./
RUN npm install

# Copy source files required by Vite
COPY vite.config.js ./
COPY resources/ ./resources/
COPY public/ ./public/

RUN npm run build

# =============================================================================
# Stage 2: PHP — production application server (php-fpm + nginx)
# =============================================================================
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    libzip-dev \
    libpq-dev \
    nginx \
    supervisor \
    && docker-php-ext-install zip pdo pdo_mysql pdo_pgsql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy application source
COPY . .

# Copy compiled frontend assets from the node-builder stage
COPY --from=node-builder /app/public/build ./public/build

# Install PHP dependencies (production only, no dev)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Set correct permissions for Laravel storage and cache
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache \
    && chmod -R 775 /app/storage /app/bootstrap/cache

# Copy nginx site configuration
COPY docker/nginx.conf /etc/nginx/sites-available/default

# Copy supervisord configuration
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 8080

# Entrypoint: run migrations first, then clear caches and start services
CMD ["sh", "-c", \
    "php artisan migrate --force && \
     php artisan config:clear && \
     php artisan cache:clear && \
     php artisan optimize:clear && \
     php artisan optimize && \
     supervisord -c /etc/supervisor/conf.d/supervisord.conf"]