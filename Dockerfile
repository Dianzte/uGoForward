# 1. Etapa de Node.js para compilar assets (Vite)
FROM node:20-alpine AS build-frontend
WORKDIR /app
COPY package*.json ./
RUN npm install --force
COPY . .
RUN npm run build

# 2. Etapa de PHP 8.4
FROM php:8.4-fpm

# Dependencias del sistema para PHP 8.4 y PostgreSQL
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    nginx

# Extensiones necesarias para Laravel con PostgreSQL
RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

# Copiar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .

# Copiar assets compilados desde la etapa de frontend
COPY --from=build-frontend /app/public/build ./public/build

# Instalar dependencias de PHP
RUN composer install --no-dev --optimize-autoloader

# Permisos para storage y bootstrap
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Configuración de Nginx adaptada a Render (Escucha en puerto 80 y 10000)
RUN echo 'server { \
    listen 80; \
    listen 10000; \
    index index.php index.html; \
    root /var/www/public; \
    location / { \
        try_files $uri $uri/ /index.php?$query_string; \
    } \
    location ~ \.php$ { \
        fastcgi_split_path_info ^(.+\.php)(/.+)$; \
        fastcgi_pass 127.0.0.1:9000; \
        fastcgi_index index.php; \
        include fastcgi_params; \
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name; \
        fastcgi_param PATH_INFO $fastcgi_path_info; \
    } \
}' > /etc/nginx/sites-available/default

EXPOSE 80 10000

# Arrancar PHP-FPM en segundo plano y Nginx en primer plano
CMD php artisan migrate --force && php-fpm -D && nginx -g 'daemon off;'