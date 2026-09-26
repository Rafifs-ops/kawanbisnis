# ==========================================
# STAGE 1: PHP & Composer Dependencies
# ==========================================
FROM php:8.5-cli-alpine AS vendor-builder

# Install dependensi sistem & ekstensi PHP yang dibutuhkan Laravel, SQLite, dan Zip
RUN apk add --no-cache \
    unzip \
    libzip-dev \
    sqlite-dev \
    postgresql-dev \
    icu-dev \
    && docker-php-ext-install pdo pdo_sqlite pdo_pgsql zip bcmath intl exif

# Install Composer dari official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy file dependency Composer
COPY composer.json composer.lock* ./

# Install dependensi PHP tanpa dev-dependencies untuk production
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction --no-progress

# ==========================================
# STAGE 2: Build Frontend Assets (Vite + Node.js)
# ==========================================
# Debian (glibc) image: package.json pins the *-linux-x64-gnu native binaries
# (lightningcss, @tailwindcss/oxide, @rollup/rollup), which do not run on musl.
FROM node:22-bookworm-slim AS frontend-builder

WORKDIR /app

# Copy file dependency Node.js
COPY package.json package-lock.json* ./

# Install dependensi frontend
RUN npm ci

# resources/css/app.css imports Flux CSS and @source paths from vendor/, so the
# production Composer dependencies must be present for the asset build.
COPY --from=vendor-builder /app/vendor ./vendor

# Copy source code yang dibutuhkan untuk build asset
COPY resources/ ./resources/
COPY vite.config.js ./
COPY public/ ./public/

# Build asset menggunakan Vite Plus (vp build)
RUN npm run build

# ==========================================
# STAGE 3: Final Production Image
# ==========================================
FROM php:8.5-cli-alpine

# Install runtime dependencies & SQLite/PostgreSQL. pcntl lets the queue worker
# honor signal handling and the --timeout option. intl is required by Filament
# and exif by spatie/image. The -dev headers are only needed while compiling
# and are removed again to keep the image small.
RUN apk add --no-cache \
    sqlite \
    libzip \
    bash \
    libpq \
    icu-libs \
    && apk add --no-cache --virtual .build-deps \
        libzip-dev \
        sqlite-dev \
        postgresql-dev \
        icu-dev \
    && docker-php-ext-install pdo pdo_sqlite pdo_pgsql zip bcmath pcntl intl exif \
    && apk del .build-deps

WORKDIR /var/www/html

# Copy seluruh source code proyek
COPY . .

# Copy vendor dari Stage 1
COPY --from=vendor-builder /app/vendor ./vendor

# Copy hasil build frontend dari Stage 2
COPY --from=frontend-builder /app/public/build ./public/build

# bootstrap/cache and Filament assets are excluded from the build context, so
# they are recreated/regenerated here. The throwaway APP_KEY only lives in this
# layer and is never baked into the runtime environment.
RUN mkdir -p \
        storage/app/public \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        bootstrap/cache \
        database \
    && touch database/database.sqlite \
    && export APP_KEY="base64:$(head -c 32 /dev/urandom | base64)" \
    && php artisan package:discover --ansi \
    && php artisan filament:assets --ansi \
    && php artisan storage:link --ansi \
    && sed -i 's/\r$//' docker/entrypoint.sh \
    && chmod +x docker/entrypoint.sh \
    && chown -R www-data:www-data storage bootstrap/cache database

# Switch ke non-root user untuk keamanan
USER www-data

# Expose port untuk Artisan Serve
EXPOSE 8000

HEALTHCHECK --interval=30s --timeout=5s --start-period=30s --retries=3 \
    CMD wget -qO- "http://127.0.0.1:${PORT:-8000}/up" >/dev/null 2>&1 || exit 1

# Entrypoint: migrate, cache, start queue worker + web server
ENTRYPOINT ["/var/www/html/docker/entrypoint.sh"]
