#!/usr/bin/env bash

echo "Running migrations..."
php artisan migrate --force

echo "Clearing old caches..."
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan view:clear

echo "Rebuilding caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Starting PHP-FPM..."
php-fpm -D

echo "Starting NGINX..."
nginx -g "daemon off;"
