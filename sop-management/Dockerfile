# Tahap 1: Build asset JS/CSS pakai Node.js
FROM node:20-bullseye as build

WORKDIR /app
COPY . .
RUN npm install && npm run build

# Tahap 2: Install dependency PHP pakai Composer
FROM composer:2 as vendor
WORKDIR /app
COPY . .
RUN composer install --no-dev --optimize-autoloader

# Tahap 3: Image utama PHP + extension yang diperlukan Laravel
FROM php:8.3-cli
WORKDIR /app

# Install library yang sering dipakai Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev zip git unzip libonig-dev libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql mbstring zip

# Copy hasil composer & build asset dari step sebelumnya
COPY --from=vendor /app /app
COPY --from=build /app/public /app/public

EXPOSE 8000

# Perintah untuk run server Laravel
CMD php artisan serve --host=0.0.0.0 --port=8000
