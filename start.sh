#!/bin/bash

echo "Iniciando aplicación Runa Maki..."

# Esperar un poco para MySQL
sleep 10

# Limpiar cache antes de las migraciones
echo "Limpiando cache..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Ejecutar migraciones con reintentos
for i in {1..5}; do
    echo "Intento $i: Ejecutando migraciones..."
    if php artisan migrate --force; then
        echo "Migraciones ejecutadas exitosamente!"
        break
    else
        echo "Fallo en migraciones, reintentando en 5 segundos..."
        sleep 5
    fi
done

# Re-descubrir paquetes para producción
php artisan package:discover --ansi

# Cachear configuración
php artisan config:cache

# Crear enlace de storage
php artisan storage:link

# Iniciar servidor
echo "Iniciando servidor en puerto $PORT..."
php artisan serve --host=0.0.0.0 --port=$PORT