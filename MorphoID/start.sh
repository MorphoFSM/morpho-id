#!/bin/sh

# Cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations
php artisan migrate --force

# Start php-fpm in background
php-fpm -D

# Start Nginx in foreground to keep container running
nginx -g "daemon off;"
