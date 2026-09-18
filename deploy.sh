#!/bin/bash

echo "🚀 Running Laravel deployment tasks..."

# 1. Run Database Migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# 2. Clear & Optimize Application Caches
echo "⚡ Optimizing application cache..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Deployment tasks completed successfully!"
