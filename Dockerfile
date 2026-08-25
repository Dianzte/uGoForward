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
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Copiar configuración de Nginx
COPY ./nginx.conf /etc/nginx/sites-available/default

EXPOSE 80

CMD service nginx start && php-fpm