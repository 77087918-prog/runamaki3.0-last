#!/bin/bash
set -e

echo "🚀 Iniciando Runa Maki..."

# Esperar MySQL (más tiempo)
echo "⏳ Esperando MySQL... (30s)"
sleep 30

# Limpiar cualquier cache problemático
echo "🧹 Limpiando cache..."
php artisan config:clear || true
php artisan cache:clear || true
php artisan view:clear || true

# Configurar aplicación con variables reales
echo "🔧 Configurando aplicación..."

# Ejecutar migraciones con reintentos
echo "🗄️ Ejecutando migraciones..."
for i in {1..5}; do
    if php artisan migrate --force; then
        echo "✅ Migraciones exitosas"
        break
    else
        echo "❌ Intento $i fallido, reintentando en 10s..."
        sleep 10
    fi
done

# Poblar base de datos si está vacía
echo "🌱 Poblando base de datos..."
if php artisan db:seed --force; then
    echo "✅ Datos de prueba creados exitosamente"
else
    echo "⚠️ Los datos ya existen o falló el seeding (normal en redeploys)"
fi

# Cachear configuración después de todo
echo "💾 Cacheando configuración..."
php artisan config:cache

# Storage link
echo "🔗 Creando storage link..."
php artisan storage:link || echo "⚠️ Storage link ya existe o falló"

# Iniciar servidor
echo "🌐 Iniciando servidor en puerto $PORT..."
php artisan serve --host=0.0.0.0 --port=$PORT