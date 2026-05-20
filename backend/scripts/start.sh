#!/usr/bin/env bash

echo "Running migrations..."
php artisan migrate --force

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Starting PHP-FPM..."
php-fpm -D

echo "Starting NGINX..."
nginx -g "daemon off;"
