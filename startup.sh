#!/bin/bash
set -e

echo "🚀 Iniciando Runa Maki..."

# Esperar MySQL
echo "⏳ Esperando MySQL..."
sleep 15

# Limpiar configuraciones
echo "🧹 Limpiando configuraciones..."
php artisan config:clear || true
php artisan cache:clear || true

# Ejecutar migraciones
echo "🗄️ Ejecutando migraciones..."
php artisan migrate --force

# Cachear configuración
echo "💾 Cacheando configuración..."
php artisan config:cache

# Storage link
echo "🔗 Creando storage link..."
php artisan storage:link || true

# Iniciar servidor
echo "🌐 Iniciando servidor en puerto $PORT..."
php artisan serve --host=0.0.0.0 --port=$PORT