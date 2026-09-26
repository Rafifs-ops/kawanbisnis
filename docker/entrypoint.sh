#!/bin/sh
set -e

cd /var/www/html

# Recreate writable directories in case volumes are mounted over them.
mkdir -p \
    storage/app/public \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    database

# Laravel needs an application key. Fail fast instead of silently generating
# one per container (which would break sessions/jobs across multiple services).
if [ -z "${APP_KEY:-}" ]; then
    echo "ERROR: APP_KEY is not set. Generate one with 'php artisan key:generate --show' and set it as an environment variable." >&2
    exit 1
fi

# Render (and other Heroku-style platforms) expose the database as DATABASE_URL.
if [ -z "${DB_URL:-}" ] && [ -n "${DATABASE_URL:-}" ]; then
    export DB_URL="$DATABASE_URL"
    export DB_CONNECTION="${DB_CONNECTION:-pgsql}"
fi

php artisan storage:link >/dev/null 2>&1 || true

# Cache configuration, routes and views using the runtime environment.
php artisan config:cache
php artisan route:cache
php artisan view:cache

ROLE="${CONTAINER_ROLE:-web}"

# Background worker service (Render "Background Worker"): run the queue only.
if [ "$ROLE" = "worker" ]; then
    exec php artisan queue:work --sleep=3 --tries=3 --timeout=300
fi

# Cron/scheduler service: run the scheduler in the foreground.
if [ "$ROLE" = "scheduler" ]; then
    exec php artisan schedule:work
fi

# Web service: apply migrations once, then serve HTTP and drain the queue.
php artisan migrate --force --no-interaction

# The AI diagnosis pipeline runs on the queue (QUEUE_CONNECTION=database by
# default), so a worker must run alongside the web server.
php artisan queue:work --sleep=3 --tries=3 --timeout=300 &
QUEUE_PID=$!

cleanup() {
    kill "$QUEUE_PID" 2>/dev/null || true
}
trap cleanup EXIT INT TERM

php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"
