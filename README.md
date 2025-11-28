# 📚 Agenda Escolar

> Sistema completo de gestión académica con Laravel 12, Vue.js 3 y FullCalendar 5

Aplicación web moderna para gestionar **tareas**, **eventos**, **calendario interactivo** y **pagos escolares**. Arquitectura profesional con frontend modularizado, sistema de recurrencias avanzado, exportaciones múltiples y documentación técnica exhaustiva.

---

## 🌟 Características Principales

✅ **Gestión de Tareas** - CRUD completo con adjuntos, fechas de vencimiento y búsqueda  
✅ **Calendario Interactivo** - FullCalendar v5 con eventos recurrentes (diario/semanal/mensual/anual)  
✅ **Sistema de Pagos** - Múltiples categorías, estados, estadísticas y recordatorios  
✅ **Notificaciones** - Sistema database-driven con marcado de leído y eliminación  
✅ **Exportaciones** - Excel y PDF para tareas, eventos, calendario y pagos  
✅ **Recurrencias Inteligentes** - Expansión on-the-fly sin materializar en BD  
✅ **Persistencia Local** - Preferencias de calendario guardadas en LocalStorage  
✅ **Datos en Español** - Factories y seeders completamente traducidos  
✅ **Documentación Completa** - Diagramas interactivos y explicación de cada archivo  

---

## 🛠️ Stack Tecnológico

### Backend
- **Framework:** Laravel 12.0 (PHP 8.2+)
- **ORM:** Eloquent con relaciones avanzadas
- **Base de Datos:** SQLite (desarrollo) / MySQL (producción)
- **Autenticación:** Laravel Auth + Policies
- **Exportación:** DomPDF 3.1, Maatwebsite Excel 3.1

### Frontend
- **Framework:** Vue.js 3.2.37
- **Calendario:** FullCalendar 5.11.5 (Core, DayGrid, TimeGrid, Interaction)
- **CSS Framework:** Bootstrap 5.3.8
- **Preprocesador:** Sass 1.56.1 (arquitectura @use modular)
- **HTTP Client:** Axios 1.11.0
- **Build Tool:** Vite 6.0 con HMR

### DevTools
- **Testing:** PHPUnit 11.5.3, Faker 1.23
- **Linting:** Laravel Pint 1.24
- **Docker:** Laravel Sail 1.41

---

## 📋 Requisitos del Sistema

- **PHP** >= 8.2
- **Composer** >= 2.0
- **Node.js** >= 18.x
- **NPM** >= 9.x
- **SQLite** (desarrollo) o **MySQL** 8.0+ (producción)
- **Git** (para clonar el repositorio)

---

## 🚀 Guía de Instalación Completa

### 1️⃣ Clonar el Repositorio

```bash
git clone https://github.com/Pachecosaurio/agenda-escolar.git
cd agenda-escolar
```

### 2️⃣ Instalar Dependencias Backend (PHP/Composer)

```bash
composer install
```

**En caso de errores:**
- Verificar versión de PHP: `php -v` (debe ser >= 8.2)
- Si faltan extensiones: instalar `php-mbstring`, `php-xml`, `php-sqlite3`, `php-curl`

### 3️⃣ Instalar Dependencias Frontend (JavaScript/NPM)

```bash
npm install
```

**Si hay vulnerabilidades:**
```bash
npm audit fix
```

### 4️⃣ Configurar Variables de Entorno

```bash
# Windows PowerShell
copy .env.example .env

# Linux/Mac
cp .env.example .env
```

Editar el archivo `.env` y configurar:

```env
APP_NAME="Agenda Escolar"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Opción 1: SQLite (recomendado para desarrollo)
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

# Opción 2: MySQL (producción)
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=agenda_escolar
# DB_USERNAME=root
# DB_PASSWORD=tu_password
```

### 5️⃣ Generar Clave de Aplicación

```bash
php artisan key:generate
```

Esta clave es **crítica** para encriptar sesiones y datos sensibles.

### 6️⃣ Crear Base de Datos

**Opción A: SQLite (desarrollo rápido)**

```bash
# Windows PowerShell
New-Item -Path database/database.sqlite -ItemType File

# Linux/Mac
touch database/database.sqlite
```

**Opción B: MySQL (producción)**

Crear base de datos manualmente:
```sql
CREATE DATABASE agenda_escolar CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 7️⃣ Ejecutar Migraciones

```bash
php artisan migrate
```

Esto creará todas las tablas: `users`, `tasks`, `events`, `payments`, `notifications`, etc.

### 8️⃣ Poblar con Datos de Prueba (Opcional pero Recomendado)

```bash
php artisan db:seed
```

**Datos generados:**
- 1 usuario demo: `demo@agendaescolar.com` / `password`
- 50 tareas en español
- 30 eventos (algunos recurrentes)
- 25 pagos en diversas categorías
- Notificaciones de prueba

### 9️⃣ Crear Enlace Simbólico para Archivos Subidos

```bash
php artisan storage:link
```

Esto permite acceder a archivos en `storage/app/public` desde `public/storage`.

### 🔟 Compilar Assets Frontend

**Desarrollo (con hot reload):**
```bash
npm run dev
```

**Producción:**
```bash
npm run build
```

### 1️⃣1️⃣ Iniciar Servidor de Desarrollo

```bash
php artisan serve
```

El servidor estará disponible en: **http://127.0.0.1:8000**

---

## 🎯 Acceso Rápido

### Usuario Demo
- **Email:** `demo@agendaescolar.com`
- **Contraseña:** `password`

### Rutas Principales
- **Home:** http://localhost:8000/
- **Tareas:** http://localhost:8000/tasks
- **Eventos:** http://localhost:8000/events
- **Calendario:** http://localhost:8000/calendar
- **Pagos:** http://localhost:8000/payments
- **Notificaciones:** http://localhost:8000/notifications

---

## 🔧 Configuración Avanzada

### Configurar Email (Notificaciones)

Editar `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu_email@gmail.com
MAIL_PASSWORD=tu_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu_email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Configurar Cola de Trabajos (Background Jobs)

```bash
# Configurar en .env
QUEUE_CONNECTION=database

# Ejecutar worker
php artisan queue:work
```

### Programar Tareas Cron (Recordatorios Automáticos)

Agregar al crontab del servidor:

```bash
* * * * * cd /ruta/a/agenda-escolar && php artisan schedule:run >> /dev/null 2>&1
```

---

## 🧪 Ejecutar Pruebas

```bash
# Todas las pruebas
php artisan test

# Solo pruebas feature
php artisan test --testsuite=Feature

# Con cobertura
php artisan test --coverage
```

**22 pruebas / 78 aserciones** cubren:
- Calendario con recurrencias
- CRUD de tareas, eventos, pagos
- Exportaciones Excel/PDF
- Notificaciones
- Autenticación y autorización

---

## 📊 Estructura del Proyecto

```
agenda-escolar/
├── app/
│   ├── Http/Controllers/      # 9 controladores
│   ├── Models/                 # User, Task, Event, Payment
│   ├── Policies/               # TaskPolicy, PaymentPolicy
│   ├── Exports/                # Clases Excel/PDF
│   └── Notifications/          # GeneralNotification
├── database/
│   ├── migrations/             # 14 migraciones
│   ├── seeders/                # Seeders en español
│   └── factories/              # Factories con datos realistas
├── resources/
│   ├── views/                  # Vistas Blade
│   ├── js/                     # app.js, calendar.js, bootstrap.js
│   └── sass/                   # Arquitectura modular (_variables, components/, layout/)
├── routes/
│   └── web.php                 # Todas las rutas del sistema
├── public/
│   └── build/                  # Assets compilados por Vite
├── documentos/                 # 📚 DOCUMENTACIÓN TÉCNICA
│   ├── README.md               # Índice de documentación
│   ├── diagramas_arquitectura.html  # 6 diagramas Mermaid interactivos
│   └── explicacion_archivos.md      # Diccionario completo del proyecto
├── storage/                    # Logs, cache, uploads
├── tests/                      # Tests Feature y Unit
├── .env.example                # Plantilla de configuración
├── composer.json               # Dependencias PHP
├── package.json                # Dependencias JavaScript
└── vite.config.js              # Configuración de build
```

---

## 📖 Documentación Técnica

Este proyecto incluye **documentación exhaustiva** en la carpeta `documentos/`:

### 🎨 Diagramas de Arquitectura ([Ver HTML](documentos/diagramas_arquitectura.html))
- Arquitectura de Alto Nivel (Frontend → Backend → Database)
- Mapa Completo de Tecnologías
- Flujo de Datos (diagrama secuencial)
- Modelo Entidad-Relación
- Arquitectura Frontend (Vite pipeline)
- Ciclo de Vida de Petición Laravel

### 📚 Diccionario de Archivos ([Ver Markdown](documentos/explicacion_archivos.md))
Explicación detallada de:
- Archivos de configuración
- Controladores y modelos
- Sistema de rutas
- Migraciones y seeders
- JavaScript y Sass
- Vistas Blade
- Exportaciones y notificaciones

### 📑 Guía de Referencia ([Ver Índice](documentos/README.md))
- Stack tecnológico completo
- Comandos útiles
- Métricas del proyecto
- Conceptos avanzados implementados

---

## 🔐 Seguridad

✅ **CSRF Protection** en todos los formularios  
✅ **Autenticación** con Laravel Auth  
✅ **Autorización** con Policies (row-level security)  
✅ **Passwords hasheados** con bcrypt  
✅ **SQL Injection Prevention** vía Eloquent parametrizado  
✅ **XSS Protection** con Blade escaping automático  
✅ **Mass Assignment Protection** con `$fillable`  

---

## 🐛 Solución de Problemas

### Error: "No application encryption key has been specified"
```bash
php artisan key:generate
```

### Error: "SQLSTATE[HY000]: General error: 1 no such table"
```bash
php artisan migrate:fresh --seed
```

### Error: "Vite manifest not found"
```bash
npm run build
```

### 🔴 CRÍTICO: El calendario no carga eventos o muestra error "Please import the top-level fullcalendar lib before attempting to import a plugin"

**Causa:** El navegador tiene en caché el build antiguo cuando FullCalendar se cargaba por CDN. Ahora se empaqueta con Vite/npm.

**Solución:**

1. **Asegurarse que Vite dev server esté corriendo:**
   ```bash
   npm run dev
   ```
   Debe mostrar: `VITE v6.4.1 ready in XXX ms` y `Local: http://localhost:5173/` (o 5174)

2. **Limpiar caché del navegador completamente:**
   - **Chrome/Edge:** `Ctrl + Shift + Delete` → Seleccionar "Imágenes y archivos en caché" → Limpiar
   - **Firefox:** `Ctrl + Shift + Delete` → Seleccionar "Caché" → Limpiar ahora
   
   O mejor aún:

3. **Hard reload (Recarga fuerte):**
   ```
   Ctrl + Shift + R  (Windows/Linux)
   Cmd + Shift + R   (Mac)
   ```

4. **Si el problema persiste, abrir en modo incógnito:**
   ```
   Ctrl + Shift + N  (Chrome/Edge)
   Ctrl + Shift + P  (Firefox)
   ```

5. **Verificar que los assets se cargan desde Vite:**
   - Abrir DevTools (F12)
   - Pestaña Network
   - Recargar página
   - Buscar `calendar.js` - debe venir de `http://localhost:5173/@vite/...` o similar
   - Si viene de `/public/js/calendar.js`, significa que está cargando versión vieja

6. **En producción (después de `npm run build`):**
   ```bash
   # Limpiar caché de Laravel
   php artisan cache:clear
   php artisan view:clear
   php artisan config:clear
   
   # Regenerar manifest de Vite
   npm run build
   ```

**¿Por qué pasa esto?**  
El proyecto anteriormente cargaba FullCalendar desde CDN (archivos estáticos en `public/js/calendar.js`). Ahora se empaqueta con Vite y npm para mejor rendimiento y control de versiones. Los navegadores cachean agresivamente JavaScript, por lo que mantienen la versión antigua hasta que se limpia la caché.

### Archivos adjuntos no se visualizan
```bash
php artisan storage:link
```

### Puerto 8000 ya está en uso
```bash
php artisan serve --port=8080
```

---

## 🚢 Despliegue en Producción

### 1. Configurar `.env` para producción

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tudominio.com

DB_CONNECTION=mysql
# ... configuración MySQL producción
```

### 2. Optimizar para producción

```bash
# Compilar assets
npm run build

# Cache de configuración
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimizar autoload
composer install --optimize-autoloader --no-dev
```

### 3. Configurar servidor web

**Nginx ejemplo:**
```nginx
server {
    listen 80;
    server_name tudominio.com;
    root /var/www/agenda-escolar/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### 4. Configurar permisos

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## 🤝 Contribución

1. Fork del proyecto
2. Crear rama feature: `git checkout -b feature/nueva-funcionalidad`
3. Commit cambios: `git commit -m "feat: descripción del cambio"`
4. Push a la rama: `git push origin feature/nueva-funcionalidad`
5. Abrir Pull Request

**Estándares de código:**
- Seguir PSR-12 para PHP
- ESLint para JavaScript
- Comentarios en español

---

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver archivo [LICENSE](LICENSE) para más detalles.

---

## 👤 Autor

**Pachecosaurio**

- GitHub: [@Pachecosaurio](https://github.com/Pachecosaurio)
- Email: Jafetpd.md24@universidadupp.edu.mx
- Repositorio: [agenda-escolar](https://github.com/Pachecosaurio/agenda-escolar)

---

## 🙏 Agradecimientos

- Laravel Community
- FullCalendar.io
- Vue.js Team
- Bootstrap Team
- Todos los contribuidores de las librerías utilizadas

---

## 📈 Roadmap Futuro

- [ ] PWA con Service Workers para acceso offline
- [ ] Notificaciones push en navegador
- [ ] Internacionalización completa (i18n)
- [ ] Modo oscuro
- [ ] Integración con Google Calendar
- [ ] Chat en tiempo real (WebSockets)
- [ ] App móvil (Flutter/React Native)

---

⭐ **Si este proyecto te resulta útil, ¡déjale una estrella en GitHub!** ⭐

---

**Última actualización:** 28 de noviembre de 2025  
**Versión:** 1.0.0  
**Laravel:** 12.0 | **Vue:** 3.2.37 | **FullCalendar:** 5.11.5
