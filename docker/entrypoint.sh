#!/bin/sh
set -e

# Force IPv4 Resolution for Supabase and add to /etc/hosts
if [ -n "$DB_HOST" ]; then
    echo "Resolving IPv4 for $DB_HOST..."
    IP=$(getent hosts "$DB_HOST" | awk '{ print $1 }' | head -n 1)
    if [ -n "$IP" ]; then
        echo "$IP $DB_HOST" >> /etc/hosts
        echo "Added $IP $DB_HOST to /etc/hosts"
    else
        echo "Failed to resolve IPv4 for $DB_HOST"
    fi
fi

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Clear and cache config
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Start PHP-FPM in background
echo "Starting PHP-FPM..."
php-fpm -D

# Start Nginx in foreground
echo "Starting Nginx..."
nginx -g "daemon off;"
