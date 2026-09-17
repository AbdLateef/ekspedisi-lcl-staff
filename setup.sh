#!/bin/bash

echo "🚀 Memulai Setup Initial Project Ekspedisi LCL Staff..."

# 1. Install Composer Dependencies
echo "📦 Installing PHP dependencies..."
composer install

# 2. Install NPM Dependencies
echo "📦 Installing JS dependencies..."
npm install

# 3. Setup Environment
if [ ! -f .env ]; then
    echo "📄 Creating .env file..."
    cp .env.example .env
    php artisan key:generate
    echo "⚠️  Mohon sesuaikan N8N_WEBHOOK_URL, DB_DATABASE, DB_USERNAME, dan DB_PASSWORD di file .env."
    exit 1
fi

# 4. Run Migrations & Seeders
echo "🗄️  Running database migrations and seeders..."
php artisan migrate --seed

# 5. Build Assets
echo "🎨 Building assets..."
npm run build

echo "✅ Setup Selesai!"
echo "🔑 Login Staff Default: staff@ekspedisi.com / password"
