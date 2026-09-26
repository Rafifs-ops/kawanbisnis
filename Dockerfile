# ==========================================
# STAGE 1: Build Frontend Assets (Vite + Node.js)
# ==========================================
FROM node:22-alpine AS frontend-builder

WORKDIR /app

# Copy file dependency Node.js
COPY package.json package-lock.json* ./

# Install dependensi frontend
RUN npm ci

# Copy source code yang dibutuhkan untuk build asset
COPY resources/ ./resources/
COPY vite.config.js ./
COPY public/ ./public/

# Build asset menggunakan Vite Plus (vp build)
RUN npm run build

# ==========================================
# STAGE 2: PHP & Composer Dependencies
# ==========================================
FROM php:8.3-cli-alpine AS vendor-builder

# Install dependensi sistem & ekstensi PHP yang dibutuhkan Laravel, SQLite, dan Zip
RUN apk add --no-cache \
    unzip \
    libzip-dev \
    sqlite-dev \
    && docker-php-ext-install pdo pdo_sqlite zip bcmath

# Install Composer dari official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy file dependency Composer
COPY composer.json composer.lock* ./

# Install dependensi PHP tanpa dev-dependencies untuk production
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# ==========================================
# STAGE 3: Final Production Image
# ==========================================
FROM php:8.3-cli-alpine

# Install runtime dependencies & SQLite
RUN apk add --no-cache \
    sqlite \
    libzip \
    bash \
    && docker-php-ext-install pdo pdo_sqlite zip bcmath

WORKDIR /var/www/html

# Copy seluruh source code proyek
COPY . .

# Copy vendor dari Stage 2
COPY --from=vendor-builder /app/vendor ./vendor

# Copy hasil build frontend dari Stage 1
COPY --from=frontend-builder /app/public/build ./public/build

# Setup direktori storage, database SQLite, dan permission
RUN mkdir -p storage/framework/views \
    storage/framework/cache \
    storage/framework/sessions \
    storage/logs \
    bootstrap/cache \
    database \
    && touch database/database.sqlite \
    && chown -R www-data:www-data storage bootstrap/cache database

# Switch ke non-root user untuk keamanan
USER www-data

# Expose port untuk Artisan Serve
EXPOSE 8000

# Entrypoint default saat container dijalankan
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
