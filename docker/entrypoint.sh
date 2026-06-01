#!/bin/sh
set -e

cd /var/www/html

# Create required storage directories
mkdir -p storage/framework/{cache,sessions,views}
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Set permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Cache configuration for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations if DB is configured
if [ -n "$DB_HOST" ]; then
    php artisan migrate --force --no-interaction 2>/dev/null || true
fi

exec "$@"
