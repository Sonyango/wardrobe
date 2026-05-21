#!/usr/bin/env bash

echo "Running migrations..."
php artisan migrate --force

echo "Clearing old caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Starting PHP-FPM..."
php-fpm -D

echo "Starting NGINX..."
nginx -g "daemon off;"
