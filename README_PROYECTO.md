# 🌟 Runa Maki - Plataforma de Intercambio de Habilidades

## 📖 Descripción del Proyecto

**Runa Maki** es una plataforma web innovadora que permite a los usuarios intercambiar habilidades y conocimientos de manera justa y equitativa, utilizando un sistema de puntos virtuales llamado "Runas".

### 🎯 Objetivo

Crear una comunidad colaborativa donde las personas puedan:
- Compartir sus habilidades y conocimientos
- Aprender nuevas habilidades de otros usuarios
- Realizar intercambios justos sin necesidad de dinero
- Construir reputación y ganar logros

---

## ✨ Características Principales

### 🔐 Sistema de Autenticación
- Registro e inicio de sesión seguro
- Gestión de perfiles de usuario
- Avatares personalizables
- Roles (Administrador y Usuario)

### 🎓 Gestión de Habilidades
- Crear y publicar habilidades personales
- Buscar habilidades por categorías
- Filtrar por disponibilidad y valoración
- Editar y eliminar habilidades propias

### 🔄 Sistema de Trueques
- Proponer intercambios de habilidades
- Aceptar o rechazar propuestas
- Seguimiento del estado (pendiente, aceptado, rechazado, completado)
- Historial completo de transacciones

### 💰 Puntos Runa
- Moneda virtual para intercambios equitativos
- Cada habilidad tiene un valor en Runas
- Sistema de transacciones automático
- Balance de puntos en tiempo real

### 🏆 Sistema de Niveles y Logros
- **Niveles**: Principiante → Aprendiz → Intermedio → Avanzado → Experto → Maestro
- **Logros desbloqueables**: Primer intercambio, Maestro del trueque, etc.
- Emojis representativos por nivel
- Gamificación del aprendizaje

### ⭐ Valoraciones y Reputación
- Calificar intercambios completados
- Sistema de estrellas (1-5)
- Reputación visible en perfiles
- Comentarios y feedback

### 💬 Sistema de Mensajería
- Chat entre usuarios
- Notificaciones de mensajes no leídos
- Mensajes relacionados a trueques

### 🎨 Interfaz Moderna
- Diseño elegante con Tailwind CSS
- Modo oscuro completo
- Responsive design (móvil, tablet, desktop)
- Animaciones y transiciones suaves
- Dashboard interactivo con estadísticas

### 👑 Panel Administrativo
- Gestión de usuarios
- Moderación de habilidades
- Estadísticas del sistema
- Control total de la plataforma

---

## 🛠️ Tecnologías Utilizadas

### Backend
- **Framework**: Laravel 12.x
- **Lenguaje**: PHP 8.2+
- **Base de datos**: MySQL 8.0
- **ORM**: Eloquent

### Frontend
- **Motor de plantillas**: Blade
- **CSS Framework**: Tailwind CSS 3.x
- **JavaScript**: Vanilla JS
- **Bundler**: Vite
- **Iconos**: Heroicons (SVG)

### Herramientas
- **Composer**: Gestión de dependencias PHP
- **NPM**: Gestión de dependencias Node.js
- **Git**: Control de versiones

---

## 📦 Instalación

### Opción 1: Ver el proyecto en GitHub
👉 **Repositorio**: [https://github.com/AbsaCG/runamaki3.0](https://github.com/AbsaCG/runamaki3.0)

### Opción 2: Instalación local
📖 **Consulta**: `INSTRUCCIONES_INSTALACION.md` para una guía paso a paso detallada

### Resumen rápido:
```bash
# 1. Clonar o extraer el proyecto
# 2. Configurar .env
copy .env.example .env

# 3. Instalar dependencias
composer install
npm install

# 4. Generar clave
php artisan key:generate

# 5. Importar base de datos
# Usar phpMyAdmin: importar database_export.sql

# 6. Compilar assets
npm run build

# 7. Iniciar servidor
php artisan serve
```

---

## 🔑 Credenciales de Prueba

Ver archivo `CREDENCIALES_PRUEBA.md` para lista completa.

**Acceso rápido**:
- 👑 Admin: `admin@runamaki.com` / `admin123`
- 👩 Usuario: `maria@example.com` / `admin123`
- 👨 Usuario: `carlos@example.com` / `admin123`

---

## 📂 Estructura del Proyecto

```
runamaki3/
├── app/
│   ├── Http/Controllers/      # Lógica de negocio
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── HabilidadController.php
│   │   ├── TruequeController.php
│   │   └── PerfilController.php
│   ├── Models/                 # Modelos de datos
│   │   ├── User.php
│   │   ├── Habilidad.php
│   │   ├── Trueque.php
│   │   ├── Categoria.php
│   │   ├── Logro.php
│   │   └── ...
│   └── Policies/               # Autorización
├── database/
│   ├── migrations/             # Esquema de BD
│   └── seeders/                # Datos de prueba
├── resources/
│   ├── views/                  # Vistas Blade
│   │   ├── auth/              # Login/Registro
│   │   ├── dashboard/         # Panel principal
│   │   ├── habilidades/       # Gestión habilidades
│   │   ├── trueques/          # Gestión trueques
│   │   └── perfil/            # Perfil usuario
│   ├── css/app.css            # Estilos
│   └── js/app.js              # JavaScript
├── routes/web.php             # Rutas de la app
├── public/                    # Archivos públicos
├── database_export.sql        # Backup BD
└── ...
```

---

## 🎮 Funcionalidades por Rol

### 👤 Usuario Regular
- ✅ Crear y gestionar habilidades
- ✅ Buscar y filtrar habilidades
- ✅ Proponer y gestionar trueques
- ✅ Enviar y recibir mensajes
- ✅ Valorar intercambios
- ✅ Ver y editar perfil
- ✅ Ganar puntos Runa
- ✅ Desbloquear logros
- ✅ Subir de nivel

### 👑 Administrador
- ✅ **Todo lo anterior, más**:
- ✅ Ver panel de administración
- ✅ Gestionar usuarios
- ✅ Moderar habilidades
- ✅ Ver estadísticas globales
- ✅ Configurar categorías
- ✅ Gestionar denuncias

---

## 📊 Modelo de Base de Datos

### Tablas Principales
- **users**: Usuarios del sistema
- **habilidades**: Habilidades publicadas
- **categorias**: Categorías de habilidades
- **trueques**: Intercambios entre usuarios
- **mensajes**: Mensajería del sistema
- **valoraciones**: Calificaciones
- **logros**: Logros disponibles
- **usuarios_logros**: Logros desbloqueados
- **transacciones_puntos**: Historial de Runas

---

## 🔄 Flujo Principal de Uso

1. **Registro/Login** → Usuario crea cuenta o inicia sesión
2. **Crear Habilidad** → Publica una habilidad que domina
3. **Buscar Habilidades** → Encuentra habilidades de interés
4. **Proponer Trueque** → Ofrece intercambio de habilidades
5. **Negociación** → El otro usuario acepta o rechaza
6. **Intercambio** → Se realiza el intercambio de conocimientos
7. **Completar** → Se marca como completado
8. **Valorar** → Ambos se califican mutuamente
9. **Ganar Reputación** → Aumenta el nivel y se ganan logros

---

## 🚀 Mejoras Futuras

- [ ] Notificaciones en tiempo real (WebSockets)
- [ ] Integración con redes sociales
- [ ] Sistema de reportes avanzado
- [ ] Chat en vivo
- [ ] Aplicación móvil
- [ ] Sistema de recomendaciones IA
- [ ] Videollamadas integradas
- [ ] Calendario de disponibilidad

---

## 👨‍💻 Desarrollo

### Desarrollado por
**[Tu Nombre]**  
Universidad: [Nombre de tu Universidad]  
Proyecto: Trabajo Final / Tesis  
Año: 2025

---

## 📝 Licencia

Este proyecto fue desarrollado con fines educativos para la universidad.

---

## 🙏 Agradecimientos

- Laravel Framework
- Tailwind CSS
- Comunidad de código abierto
- Profesores y mentores

---

**Runa Maki** - Intercambia conocimientos, crece en comunidad 🌱
