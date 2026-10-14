#!/bin/sh
set -e

# Port configuration for Render
PORT=${PORT:-80}
echo "==> Configuring Nginx to listen on port: $PORT"
sed -i "s/listen [0-9]*;/listen $PORT;/g" /etc/nginx/http.d/default.conf

# Ensure required storage and cache directories exist
echo "==> Setting up storage directories and permissions..."
mkdir -p /var/www/html/storage/framework/cache/data \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/logs \
         /var/www/html/bootstrap/cache

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Handle SQLite if used
if [ "$DB_CONNECTION" = "sqlite" ]; then
    DB_FILE=${DB_DATABASE:-/var/www/html/database/database.sqlite}
    echo "==> SQLite configured. Checking database file at: $DB_FILE"
    mkdir -p "$(dirname "$DB_FILE")"
    if [ ! -f "$DB_FILE" ]; then
        touch "$DB_FILE"
        echo "==> Created empty SQLite database file."
    fi
    chown www-data:www-data "$DB_FILE"
    chmod 664 "$DB_FILE"
    chown -R www-data:www-data "$(dirname "$DB_FILE")"
    chmod -R 775 "$(dirname "$DB_FILE")"
fi

# Ensure storage link exists
php artisan storage:link || true

# Run database migrations if enabled
if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    echo "==> Running database migrations..."
    php artisan migrate --force || echo "==> [Notice] Migration failed or skipped. Check database configuration."
fi

# Run database seeders if enabled
if [ "${RUN_SEEDERS:-false}" = "true" ]; then
    echo "==> Running database seeders..."
    php artisan db:seed --force || echo "==> [Notice] Seeder failed or skipped."
fi

# Cache configuration, routes, and views for production
if [ "${APP_ENV:-production}" = "production" ]; then
    echo "==> Optimizing configuration, route, and view caches..."
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
fi

echo "==> Setup complete! Starting services via Supervisord..."
exec "$@"
