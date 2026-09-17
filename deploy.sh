#!/bin/bash

echo "🚀 Deploying Ekspedisi LCL Staff..."

# 1. Pull Latest Code
echo "📥 Pulling latest code from git..."
git pull origin main

# 2. Install PHP Dependencies
echo "📦 Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader

# 3. Install NPM Dependencies & Build Assets
echo "📦 Installing JS dependencies & building assets..."
npm install
npm run build

# 4. Run Migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# 5. Clear & Optimize Caches
echo "⚡ Optimizing application cache..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Deployment Successful!"
