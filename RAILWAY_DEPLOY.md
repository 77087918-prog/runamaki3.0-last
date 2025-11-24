# 🚀 Railway Deployment Guide - Runa Maki v2.0

## 📋 Variables de Entorno Requeridas en Railway

### Base Datos MySQL
```bash
MYSQLHOST=xxxx.railway.app
MYSQLPORT=3306  
MYSQLDATABASE=railway
MYSQLUSER=root
MYSQLPASSWORD=xxxxx
```

### Aplicación Laravel
```bash
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:xxxxxxxxxxxx  # Railway auto-genera
APP_URL=https://tu-app.railway.app
APP_LOCALE=es
APP_FALLBACK_LOCALE=es
```

### WebSocket Reverb (Chat Tiempo Real)
```bash
BROADCAST_DRIVER=reverb
REVERB_APP_ID=railway-app-id
REVERB_APP_KEY=railway-app-key
REVERB_APP_SECRET=railway-app-secret  
REVERB_HOST=tu-app.railway.app
REVERB_PORT=443
REVERB_SCHEME=https

# Cliente Pusher/Echo
PUSHER_APP_ID=${REVERB_APP_ID}
PUSHER_APP_KEY=${REVERB_APP_KEY}
PUSHER_APP_SECRET=${REVERB_APP_SECRET}
PUSHER_HOST=${REVERB_HOST}
PUSHER_PORT=443
PUSHER_SCHEME=https

# Vite Frontend
VITE_PUSHER_APP_KEY=${REVERB_APP_KEY}
VITE_PUSHER_HOST=${REVERB_HOST}
VITE_PUSHER_PORT=443
VITE_PUSHER_SCHEME=https
```

### Base de Datos UTF-8
```bash
DB_CHARSET=utf8mb4
DB_COLLATION=utf8mb4_unicode_ci
```

### Storage & Sessions
```bash
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
FILESYSTEM_DISK=local
```

### Seguridad Production
```bash
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=strict
SESSION_ENCRYPT=false
```

## ⚙️ Comandos Post-Deploy

Railway ejecutará automáticamente:

```bash
# 1. Install dependencies
composer install --no-dev --optimize-autoloader

# 2. Build assets  
npm ci && npm run build

# 3. Run migrations
php artisan migrate --force

# 4. Cache optimization
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🎯 Funcionalidades Desplegadas

### ✅ Chat Tiempo Real
- WebSocket con Laravel Reverb nativo
- Canales privados autenticados
- Mensajes instantáneos sin recargar

### ✅ Integración Trueques-Chat  
- Chat automático al proponer trueque
- Mensajes del sistema contextuales
- Acceso directo desde cualquier trueque

### ✅ UTF-8 Completo
- Acentos y tildes funcionando
- Emojis y caracteres especiales
- Base de datos utf8mb4_unicode_ci

### ✅ UI Moderna
- Diseño responsive Tailwind CSS
- Notificaciones visuales dinámicas  
- Indicadores de estado WebSocket

## 🔧 Troubleshooting

### Problema de charset en Railway:
- Variables `DB_CHARSET` y `DB_COLLATION` configuradas
- AppServiceProvider con detección de entorno
- Migraciones con manejo de errores

### WebSocket no funciona:
- Verificar variables `REVERB_*` en Railway
- Confirmar `BROADCAST_DRIVER=reverb`
- Revisar que el puerto 443 esté abierto

### Chat sin acceso:
- Verificar que existe trueque entre usuarios
- Confirmar autenticación Laravel
- Revisar canales privados en `routes/channels.php`

## 🎖️ Rollback Plan

Tags disponibles para rollback:
- `v2.0-production-ready` ← **ACTUAL**
- `v1.3-chat-utf8-completo` 
- `v1.2-chat-completo`
- `v1.1-main-clean` ← **BACKUP RAILWAY**

```bash
# Rollback rápido si es necesario:
git checkout v1.1-main-clean
git push origin main --force
```

---

**🚀 Estado: Listo para Railway Production Deploy**