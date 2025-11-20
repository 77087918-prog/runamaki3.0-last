#!/bin/bash
set -e

echo "🚀 Iniciando Runa Maki..."

# Configurar variables de entorno para Railway HTTPS
echo "🔧 Configurando HTTPS para Railway..."
export APP_ENV=production
export APP_DEBUG=false
export SESSION_SECURE_COOKIE=true
export SESSION_SAME_SITE=strict

# Esperar MySQL (más tiempo)
echo "⏳ Esperando MySQL... (30s)"
sleep 30

# Limpiar cualquier cache problemático
echo "🧹 Limpiando cache..."
php artisan config:clear || true
php artisan cache:clear || true
php artisan view:clear || true

# Verificar que los assets de Vite existen
echo "📦 Verificando assets compilados..."
if [ -f "public/build/manifest.json" ]; then
    echo "✅ Manifest de Vite encontrado"
    cat public/build/manifest.json
    ls -la public/build/assets/ || echo "⚠️ Directorio assets vacío"
elif [ -f "public/build/.vite/manifest.json" ]; then
    echo "🔧 Manifest encontrado en .vite/, moviéndolo..."
    cp public/build/.vite/manifest.json public/build/manifest.json
    echo "✅ Manifest movido a la ubicación correcta"
    cat public/build/manifest.json
else
    echo "❌ Manifest de Vite no encontrado"
    echo "📂 Contenido de public/:"
    ls -la public/ || echo "⚠️ Public directory error"
    echo "📂 Buscando manifest en todo el proyecto:"
    find . -name "manifest.json" -type f || echo "⚠️ No manifest found anywhere"
    echo "🔧 Ejecutando build de emergencia..."
    npm run build || echo "⚠️ Build falló"
    if [ -f "public/build/.vite/manifest.json" ]; then
        echo "🔧 Moviendo manifest desde .vite/"
        cp public/build/.vite/manifest.json public/build/manifest.json
        echo "✅ Manifest creado y movido exitosamente"
    elif [ -f "public/build/manifest.json" ]; then
        echo "✅ Manifest creado exitosamente"
    else
        echo "❌ Manifest aún no existe después del build"
    fi
fi

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

# Agregar habilidades adicionales
echo "📚 Agregando habilidades adicionales..."
php artisan db:seed --class=NuevasHabilidadesSeeder --force || echo "⚠️ Las habilidades adicionales ya existen"

# Cachear configuración después de todo
echo "💾 Cacheando configuración..."
php artisan config:cache

# Storage link
echo "🔗 Creando storage link..."
php artisan storage:link || echo "⚠️ Storage link ya existe o falló"

# Iniciar servidor
echo "🌐 Iniciando servidor en puerto $PORT..."
php artisan serve --host=0.0.0.0 --port=$PORT