#!/bin/bash

# Railway Build Script
# Este script se ejecuta durante el build en Railway

echo "🚀 Starting Railway build process..."

# Install PHP dependencies
echo "📦 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# Install Node dependencies  
echo "📦 Installing Node dependencies..."
npm ci --production=false

# Build frontend assets
echo "🏗️  Building frontend assets..."
npm run build

# Clear Laravel caches (sin DB)
echo "🧹 Clearing Laravel caches..."
php artisan config:clear
php artisan route:clear  
php artisan view:clear

# Cache Laravel config (sin DB)
echo "⚡ Caching Laravel configuration..."
php artisan config:cache

# Cache routes
echo "⚡ Caching routes..."
php artisan route:cache

echo "✅ Build completed successfully!"
echo "🎯 Ready for Railway deployment!"