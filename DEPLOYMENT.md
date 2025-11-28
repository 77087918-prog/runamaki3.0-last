# 🚀 Guía de Despliegue en Producción

## Pasos para Railway/Producción

### 1. Compilar Assets
```bash
npm install
npm run build
```

### 2. Ejecutar Migraciones
```bash
php artisan migrate --force
```

### 3. Limpiar Caché
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 4. Optimizar Composer
```bash
composer install --optimize-autoloader --no-dev
```

### 5. Variables de Entorno Requeridas

Asegúrate de tener estas variables en Railway:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-app.railway.app

# Database
DB_CONNECTION=mysql
DB_HOST=
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

# Session
SESSION_DRIVER=database

# Queue
QUEUE_CONNECTION=database
```

### 6. Permisos de Storage
```bash
php artisan storage:link
chmod -R 775 storage bootstrap/cache
```

## ⚠️ Problemas Comunes

### Gráficos no se visualizan

**Causa**: Assets no compilados o mal referenciados

**Solución**:
1. Ejecutar `npm run build` localmente
2. Hacer commit de la carpeta `public/build/`
3. Push a producción
4. Verificar en Railway que exista `public/build/manifest.json`

### CSP bloqueando recursos

**Causa**: Content Security Policy muy restrictiva

**Solución**: Ya configurado en `SecureHeaders` middleware para permitir recursos locales

### Chart.js no carga

**Verificar en consola del navegador**:
- ¿Se carga `admin-charts-DBMCaHWx.js`?
- ¿Hay errores 404 en los assets?
- ¿El evento `chartjs-loaded` se dispara?

**Debug**:
```javascript
// En la consola del navegador
console.log(typeof Chart); // Debe mostrar "function"
console.log(window.Chart); // Debe mostrar el objeto Chart
```

## 📊 Verificación Post-Deployment

1. **Assets cargados**: Revisar Network tab del navegador
2. **Gráficos visibles**: Dashboard admin debe mostrar 3 gráficos
3. **Exportaciones funcionando**: Probar CSV y PDF
4. **Middleware activo**: Usuario suspendido no debe poder entrar
5. **Alpine.js funcionando**: Dropdowns deben abrir/cerrar

## 🔧 Comandos de Mantenimiento

```bash
# Limpiar todas las cachés
php artisan optimize:clear

# Ver logs en tiempo real
tail -f storage/logs/laravel.log

# Crear usuario admin
php artisan make:admin {email}
```
