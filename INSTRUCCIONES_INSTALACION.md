# 📦 Guía de Instalación - Runa Maki

## 📋 Requisitos Previos

Antes de instalar el proyecto, asegúrate de tener instalado:

- ✅ **PHP 8.2 o superior**
- ✅ **Composer** (gestor de dependencias PHP)
- ✅ **Node.js 18 o superior** y npm
- ✅ **MySQL 8.0 o superior**
- ✅ **XAMPP, WAMP o servidor local** (opcional pero recomendado)

---

## 🚀 Pasos de Instalación

### 1️⃣ Descargar el Proyecto

Extrae todos los archivos del proyecto en una carpeta (ejemplo: `runamaki3`).

```
runamaki3/
├── app/
├── database/
├── public/
├── resources/
├── database_export.sql  ← Archivo de base de datos
├── .env.example
├── composer.json
├── package.json
└── ...
```

---

### 2️⃣ Configurar Base de Datos

#### Opción A: Importar base de datos existente (RECOMENDADO)

1. **Abrir phpMyAdmin** o tu gestor de MySQL
   - URL típica: `http://localhost/phpmyadmin`

2. **Crear nueva base de datos**:
   - Nombre: `runamaki3`
   - Collation: `utf8mb4_unicode_ci`

3. **Importar datos**:
   - Selecciona la base de datos `runamaki3`
   - Ve a la pestaña "Importar"
   - Selecciona el archivo `database_export.sql`
   - Haz clic en "Continuar"

✅ **Listo!** La base de datos ya tiene todos los usuarios y datos de prueba.

#### Opción B: Crear base de datos desde cero

1. Crear base de datos `runamaki3`
2. Luego ejecutar migraciones (ver paso 5)

---

### 3️⃣ Configurar Variables de Entorno

1. **Copiar archivo de ejemplo**:
   ```bash
   copy .env.example .env
   ```
   (En Linux/Mac: `cp .env.example .env`)

2. **Editar archivo `.env`** con tus datos:
   ```env
   APP_NAME="Runa Maki"
   APP_ENV=local
   APP_DEBUG=true
   APP_URL=http://localhost:8000

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=runamaki3
   DB_USERNAME=root
   DB_PASSWORD=
   ```

   ⚠️ **Nota**: Si tu MySQL tiene contraseña, actualiza `DB_PASSWORD`

---

### 4️⃣ Instalar Dependencias

Abre una terminal en la carpeta del proyecto y ejecuta:

```bash
# Instalar dependencias PHP
composer install

# Instalar dependencias Node.js
npm install
```

⏱️ **Tiempo estimado**: 3-5 minutos

---

### 5️⃣ Generar Clave de Aplicación

```bash
php artisan key:generate
```

---

### 6️⃣ Compilar Assets (CSS/JS)

```bash
npm run build
```

Esto compilará Tailwind CSS y los archivos JavaScript.

---

### 7️⃣ Crear Storage Links (Opcional)

Para que funcionen las imágenes de avatar:

```bash
php artisan storage:link
```

---

### 8️⃣ Iniciar el Servidor

```bash
php artisan serve
```

✅ **El proyecto estará disponible en**: `http://localhost:8000`

---

## 🔑 Credenciales de Acceso

### Usuarios de Prueba

Todos tienen la contraseña: **`admin123`**

| Rol | Email | Contraseña | Descripción |
|-----|-------|------------|-------------|
| 👑 Admin | admin@runamaki.com | admin123 | Acceso completo al sistema |
| 👩 Usuario | maria@example.com | admin123 | Usuario con habilidades |
| 👨 Usuario | carlos@example.com | admin123 | Usuario activo |
| 👩 Usuario | ana@example.com | admin123 | Usuario regular |
| 👨 Usuario | absalon@example.com | admin123 | Usuario regular |

---

## 🎯 Verificar Instalación

1. **Accede a**: `http://localhost:8000`
2. **Haz clic en "Iniciar Sesión"**
3. **Usa cualquier credencial de prueba** (panel en la página de login)
4. **Explora el dashboard**

---

## ⚙️ Comandos Útiles

### Limpiar caché
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Reiniciar base de datos (CUIDADO: borra todo)
```bash
php artisan migrate:fresh --seed
```

### Ver rutas disponibles
```bash
php artisan route:list
```

---

## 🐛 Solución de Problemas

### Error: "Class not found"
```bash
composer dump-autoload
```

### Error: "Please provide a valid APP_KEY"
```bash
php artisan key:generate
```

### Error: "SQLSTATE[HY000] [1049]"
- Verifica que la base de datos `runamaki3` existe
- Verifica credenciales en `.env`

### Assets no se ven (sin estilos)
```bash
npm run build
php artisan view:clear
```

### Puerto 8000 ocupado
```bash
php artisan serve --port=8080
```

---

## 📁 Estructura del Proyecto

```
runamaki3/
├── app/
│   ├── Http/Controllers/     # Controladores
│   ├── Models/               # Modelos Eloquent
│   └── Policies/             # Políticas de autorización
├── database/
│   ├── migrations/           # Migraciones de BD
│   └── seeders/              # Datos de prueba
├── resources/
│   ├── views/                # Vistas Blade
│   ├── css/                  # Estilos
│   └── js/                   # JavaScript
├── routes/
│   └── web.php               # Rutas de la aplicación
└── public/                   # Archivos públicos
```

---

## 🌟 Características del Sistema

- ✅ Sistema de autenticación completo
- ✅ CRUD de habilidades con categorías
- ✅ Sistema de trueques (intercambio de habilidades)
- ✅ Puntos Runa (moneda virtual)
- ✅ Niveles y logros
- ✅ Sistema de mensajería
- ✅ Valoraciones y reputación
- ✅ Panel administrativo
- ✅ Modo oscuro
- ✅ Diseño responsive (móvil, tablet, desktop)

---

## 📞 Soporte

Si tienes problemas con la instalación:

1. Verifica que todos los requisitos estén instalados
2. Revisa el archivo `.env`
3. Consulta la sección "Solución de Problemas"
4. Verifica los logs en `storage/logs/laravel.log`

---

## 📄 Tecnologías Utilizadas

- **Backend**: Laravel 12.x (PHP)
- **Frontend**: Blade Templates + Tailwind CSS
- **Base de datos**: MySQL
- **Bundler**: Vite
- **Control de versiones**: Git

---

**Desarrollado como proyecto universitario - Runa Maki 2025**

¡Disfruta explorando el sistema! 🚀
