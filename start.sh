#!/bin/bash

echo "Iniciando aplicación Runa Maki..."

# Esperar un poco para MySQL
sleep 10

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

# Cachear configuración
php artisan config:cache

# Crear enlace de storage
php artisan storage:link

# Iniciar servidor
echo "Iniciando servidor en puerto $PORT..."
php artisan serve --host=0.0.0.0 --port=$PORT