#!/bin/bash

# Ejecutar migraciones
php artisan migrate --force

# Limpiar y cachear configuraciones
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Crear enlace simbólico para storage si no existe
php artisan storage:link

# Iniciar el servidor
php artisan serve --host=0.0.0.0 --port=$PORT