# 📚 Diccionario de Archivos del Proyecto - Agenda Escolar

> **Documentación Técnica Completa**: Explicación detallada de cada archivo clave del sistema, sus responsabilidades, conexiones y su importancia en la arquitectura global.

---

## 📂 Estructura General del Proyecto

```
agenda-escolar/
├── app/                    # Lógica de aplicación Laravel
├── bootstrap/              # Inicialización del framework
├── config/                 # Archivos de configuración
├── database/               # Migraciones, seeders y factories
├── public/                 # Punto de entrada web y assets compilados
├── resources/              # Vistas, JS, CSS sin compilar
├── routes/                 # Definición de rutas web
├── storage/                # Logs, cache, archivos subidos
├── tests/                  # Pruebas automatizadas
└── documentos/             # Documentación técnica (este archivo)
```

---

## 🎯 Archivos Raíz de Configuración

### `composer.json`
**¿Qué hace?**  
Define todas las dependencias PHP del proyecto y scripts de automatización. Especifica que el proyecto requiere PHP 8.2+, Laravel 12.0, y librerías adicionales como `barryvdh/laravel-dompdf` para exportación a PDF y `maatwebsite/excel` para exportación a Excel.

**Conexiones:**
- Instalado vía `composer install` 
- Genera autoloading en `vendor/autoload.php`
- Conecta con `app/`, `database/factories/`, `database/seeders/`

**Importancia:**  
Es el corazón de la gestión de dependencias del backend. Sin este archivo, Laravel no podría ejecutarse. Define la versión mínima de PHP y todas las librerías críticas del sistema.

---

### `package.json`
**¿Qué hace?**  
Define las dependencias JavaScript/NPM del frontend: Vue.js 3.2.37, FullCalendar 5.11.5 con sus plugins, Bootstrap 5.3.8, Vite 6.0, Sass, Axios y Tailwind CSS. También define scripts de build (`npm run build`) y desarrollo (`npm run dev`).

**Conexiones:**
- Instalado vía `npm install`
- Genera `node_modules/` y `package-lock.json`
- Vinculado con `vite.config.js` para compilación
- Assets finales en `public/build/`

**Importancia:**  
Sin este archivo no existiría el frontend moderno. Gestiona todas las librerías JavaScript y define cómo se compila la interfaz de usuario.

---

### `vite.config.js`
**¿Qué hace?**  
Configura el bundler Vite para compilar recursos frontend. Define tres puntos de entrada (`resources/sass/app.scss`, `resources/js/app.js`, `resources/js/calendar.js`), habilita el plugin de Vue con transformación de assets, y configura el servidor de desarrollo en puerto 5173 con HMR (Hot Module Replacement).

**Conexiones:**
- Lee archivos de `resources/js/` y `resources/sass/`
- Genera `public/build/manifest.json` con hashes de archivos
- Usado por directiva `@vite()` en vistas Blade
- Plugin `laravel-vite-plugin` conecta con Laravel

**Importancia:**  
Es el cerebro del sistema de build del frontend. Sin él, los archivos JS/CSS no se compilarían ni se optimizarían. Permite desarrollo rápido con recarga en vivo.

---

### `.env`
**¿Qué hace?**  
Archivo de variables de entorno (no versionado en Git). Contiene configuración sensible como credenciales de base de datos, claves de encriptación (`APP_KEY`), modo debug, URL de la aplicación y configuración de mail/cache.

**Conexiones:**
- Leído por `config/*.php` vía función `env()`
- Generado desde `.env.example`
- Usado por todo el sistema Laravel

**Importancia:**  
**CRÍTICO**: Contiene secretos y configuración específica del entorno. Sin `APP_KEY` válida Laravel no puede cifrar sesiones. Cada entorno (dev/staging/prod) tiene su propio `.env`.

---

## 🛣️ Sistema de Rutas

### `routes/web.php`
**¿Qué hace?**  
Define todas las rutas HTTP del sistema. Mapea URLs a controladores específicos: 
- `/tasks` → `TaskController`
- `/events` → `EventController`
- `/payments` → `PaymentController`
- `/calendar` → Vista de calendario
- `/notifications` → `NotificationsController`

Todas las rutas (excepto Auth) están protegidas por middleware `auth`.

**Conexiones:**
- Importa controladores de `app/Http/Controllers/`
- Usado por `Router` de Laravel en cada petición
- Define named routes usadas en vistas con `route('tasks.index')`
- Aplica middleware `auth` a rutas protegidas

**Importancia:**  
Es el **mapa de carreteras** de la aplicación. Define qué controlador maneja cada URL. Sin rutas correctas, ninguna funcionalidad sería accesible.

---

### `routes/console.php`
**¿Qué hace?**  
Define comandos Artisan personalizados ejecutables desde CLI. Actualmente contiene comando de inspiración de ejemplo.

**Conexiones:**
- Ejecutado vía `php artisan <comando>`
- Puede interactuar con modelos y servicios

**Importancia:**  
Permite crear tareas programadas y comandos de mantenimiento (migraciones, seeders, limpieza de cache, etc.).

---

## ⚙️ Controladores (app/Http/Controllers/)

### `TaskController.php`
**¿Qué hace?**  
Maneja CRUD completo de tareas del usuario autenticado:
- `index()`: Lista tareas con búsqueda y filtro por fecha
- `create()`: Muestra formulario de creación
- `store()`: Valida y guarda nueva tarea (soporta adjuntos hasta 10MB)
- `edit()`: Muestra formulario de edición con autorización vía Policy
- `update()`: Actualiza tarea existente
- `destroy()`: Elimina tarea

**Conexiones:**
- **Modelo**: `App\Models\Task`
- **Policy**: `TaskPolicy` (vía `authorize()`)
- **Vistas**: `resources/views/tasks/*.blade.php`
- **Storage**: `storage/app/public/attachments/` para adjuntos
- **Rutas**: `Route::resource('tasks', TaskController::class)`

**Importancia:**  
Es el núcleo de la funcionalidad de tareas. Implementa validación robusta, autorización por usuario, y manejo seguro de archivos subidos.

---

### `EventController.php`
**¿Qué hace?**  
Gestiona eventos del calendario con soporte para recurrencia:
- `index()`: Lista eventos (excluye ocurrencias hijas con `whereNull('parent_event_id')`)
- `store()`: Crea evento con datos de recurrencia (daily/weekly/monthly/yearly)
- `apiEvents()`: **Endpoint crítico** que expande eventos recurrentes en memoria dentro de un rango de fechas, retornando JSON para FullCalendar
- Incluye tareas en el feed del calendario

**Conexiones:**
- **Modelo**: `App\Models\Event` (con método `occurrencesBetween()`)
- **Modelo**: `App\Models\Task` (para mostrar en calendario)
- **Vista**: `resources/views/calendar.blade.php`
- **JavaScript**: `resources/js/calendar.js` consume `/calendar/events`

**Importancia:**  
Implementa lógica compleja de recurrencia sin materializar miles de registros en BD. El método `apiEvents()` es consumido por FullCalendar para renderizar eventos dinámicamente.

---

### `PaymentController.php`
**¿Qué hace?**  
Administra sistema de pagos escolares:
- `index()`: Dashboard con filtros (estado, categoría, rango de fechas), estadísticas agregadas y paginación
- CRUD completo con scopes de Eloquent (`forUser`, `status`, `category`, `betweenDates`)
- `getCalendarEvents()`: Endpoint JSON que retorna pagos formateados para FullCalendar
- Actualiza automáticamente estado `pending` → `overdue` si `due_date` pasó

**Conexiones:**
- **Modelo**: `App\Models\Payment`
- **Policy**: `PaymentPolicy`
- **Vistas**: `resources/views/payments/*.blade.php`
- **JavaScript**: Consumido por `calendar.js` en `/payments-calendar-events`
- **Soft Deletes**: Usa `SoftDeletes` trait

**Importancia:**  
Centro del sistema de gestión financiera. Provee estadísticas en tiempo real, filtrado avanzado y visualización en calendario. Crucial para seguimiento de pagos escolares.

---

### `HomeController.php`
**¿Qué hace?**  
Controlador del dashboard principal. Muestra resumen de actividad del usuario: tareas pendientes, eventos próximos, pagos vencidos.

**Conexiones:**
- **Vista**: `resources/views/home.blade.php`
- **Middleware**: `auth` (solo usuarios autenticados)
- **View Composer**: Recibe datos inyectados por `AppServiceProvider`

**Importancia:**  
Es la página de bienvenida post-login. Punto de entrada central de la aplicación.

---

### `NotificationsController.php`
**¿Qué hace?**  
Gestiona sistema de notificaciones de Laravel:
- `index()`: Lista notificaciones del usuario
- `markRead()`: Marca una notificación como leída
- `markAllRead()`: Marca todas como leídas
- `destroy()`: Elimina una notificación
- `destroyAll()`: Limpia todas las notificaciones

**Conexiones:**
- **Sistema**: Notificaciones nativas de Laravel (`database` channel)
- **Tabla**: `notifications` en BD
- **Vista**: `resources/views/notifications/*.blade.php`

**Importancia:**  
Implementa sistema de alertas y recordatorios para tareas/pagos/eventos próximos.

---

### `CalendarExportController.php`, `TaskExportController.php`, `EventExportController.php`
**¿Qué hacen?**  
Controladores dedicados a exportación de datos:
- **Excel**: Usando `Maatwebsite\Excel` con clases Export personalizadas
- **PDF**: Usando `Barryvdh\DomPDF` con vistas Blade como templates

**Conexiones:**
- **Exports**: `app/Exports/*.php` (GenericArrayExport, TasksExport, etc.)
- **Rutas**: `/tasks/export/excel`, `/calendar/export/pdf`, etc.
- **Vistas PDF**: Templates Blade específicos para PDF

**Importancia:**  
Permiten a usuarios exportar sus datos para reportes, respaldos o impresión. Funcionalidad premium importante.

---

## 📦 Modelos Eloquent (app/Models/)

### `User.php`
**¿Qué hace?**  
Modelo principal de autenticación. Extiende `Authenticatable` de Laravel e implementa:
- Relaciones `hasMany` con `tasks`, `events`, `payments`
- Password hashing automático
- Email verification (preparado pero no forzado)
- Notificaciones vía trait `Notifiable`

**Conexiones:**
- **Tabla**: `users`
- **Relaciones**: Propietario de Tasks, Events, Payments
- **Factory**: `database/factories/UserFactory.php`
- **Middleware**: `Auth::user()` retorna instancia de este modelo

**Importancia:**  
**CRÍTICO**: Base del sistema de autenticación. Toda la seguridad y ownership de datos depende de este modelo.

---

### `Task.php`
**¿Qué hace?**  
Modelo de tareas del usuario. Campos:
- `title`, `description` (texto libre)
- `due_date` (fecha límite opcional)
- `attachment` (ruta a archivo en storage)
- `completed` (boolean)
- `user_id` (FK a users)

**Conexiones:**
- **Tabla**: `tasks`
- **Controlador**: `TaskController`
- **Factory**: `TaskFactory` (genera tareas en español)
- **Política**: `TaskPolicy::update()` verifica que `task->user_id === Auth::id()`

**Importancia:**  
Almacena trabajo académico del usuario. Crucial para seguimiento de deberes y proyectos escolares.

---

### `Event.php`
**¿Qué hace?**  
Modelo de eventos con soporte avanzado de recurrencia:
- Campos básicos: `title`, `description`, `start`, `end`, `user_id`
- Recurrencia: `is_recurring`, `recurrence_type` (daily/weekly/monthly/yearly), `recurrence_interval`, `recurrence_days` (JSON array), `recurrence_end_date`, `recurrence_count`
- `parent_event_id`: Auto-referencia para ocurrencias generadas (actualmente no usado, se expande en memoria)

**Métodos clave:**
- `occurrencesBetween($rangeStart, $rangeEnd)`: Genera ocurrencias virtuales de eventos recurrentes sin crear registros en BD

**Conexiones:**
- **Tabla**: `events`
- **Controlador**: `EventController::apiEvents()` usa `occurrencesBetween()`
- **Cast**: `recurrence_days` automáticamente JSON ↔ array
- **Factory**: `EventFactory`

**Importancia:**  
Implementa lógica sofisticada de eventos recurrentes (ej: "Clase de matemáticas todos los lunes a las 8am"). Evita explosión de registros en BD.

---

### `Payment.php`
**¿Qué hace?**  
Modelo de pagos escolares con estados y categorías:
- **Estados**: `pending`, `paid`, `overdue` (con colores y textos via accessors)
- **Categorías**: `tuition` (colegiatura), `books`, `transport`, `lunch`, `uniform`, `activities`, `other`
- **Campos monetarios**: `amount`, `paid_date`, `payment_method`, `reference`
- **Soft Deletes**: Eliminación lógica, recuperable

**Scopes de consulta:**
- `forUser($userId)`: Filtra por usuario
- `status($status)`: Filtra por estado
- `category($category)`: Filtra por categoría
- `betweenDates($from, $to)`: Rango de fechas
- `search($query)`: Búsqueda en title/description

**Conexiones:**
- **Tabla**: `payments`
- **Controlador**: `PaymentController`
- **Accessors**: `status_color`, `status_text`, `category_text` (computados on-the-fly)
- **Factory**: `PaymentFactory`

**Importancia:**  
Núcleo del sistema financiero escolar. Permite seguimiento de colegiaturas, uniformes, libros, etc. con recordatorios de vencimiento.

---

## 🔐 Políticas y Middleware (app/Policies/, app/Http/Middleware/)

### `TaskPolicy.php`
**¿Qué hace?**  
Define reglas de autorización para tareas:
- `update(User $user, Task $task)`: Solo el propietario puede editar
- `delete(User $user, Task $task)`: Solo el propietario puede eliminar

**Conexiones:**
- Registrada en `AuthServiceProvider`
- Usada vía `$this->authorize('update', $task)` en `TaskController`

**Importancia:**  
Previene que usuarios vean/editen tareas de otros. Seguridad a nivel de fila (row-level security).

---

### `PaymentPolicy.php`
**¿Qué hace?**  
Similar a `TaskPolicy` pero para pagos. Verifica `payment->user_id === Auth::id()`.

**Importancia:**  
Protege información financiera sensible. Crítico para privacidad.

---

## 🗄️ Base de Datos (database/)

### `migrations/`
**¿Qué hacen?**  
Scripts PHP que definen estructura de tablas usando Eloquent Schema Builder. Se ejecutan con `php artisan migrate`.

**Archivos clave:**
- `0001_01_01_000000_create_users_table.php`: Crea tabla `users` con email único, password hasheado
- `2025_09_02_231127_create_tasks_table.php`: Tabla `tasks` con FK a `users`
- `2025_09_04_000002_create_events_table.php`: Tabla `events` con campos de recurrencia
- `2025_09_25_010840_create_payments_table.php`: Tabla `payments` con soft deletes
- Múltiples migraciones de ajuste (`add_calendar_fields`, `add_recurrence_fields`, etc.)

**Conexiones:**
- Ejecutadas por motor de migraciones de Laravel
- Tracked en tabla `migrations`
- Rollback con `php artisan migrate:rollback`

**Importancia:**  
**CRÍTICO**: Define el schema de la BD. Sin migraciones, la BD estaría vacía. Permiten control de versiones del schema.

---

### `seeders/DatabaseSeeder.php`
**¿Qué hace?**  
Seeder principal que orquesta población de datos de prueba:
```php
UserSeeder::run()  // Crea usuario demo@agendaescolar.com
TaskSeeder::run()  // Genera 50 tareas en español
EventSeeder::run() // Genera 30 eventos (algunos recurrentes)
PaymentSeeder::run() // Genera 25 pagos en varias categorías
NotificationSeeder::run() // Genera notificaciones de prueba
```

**Conexiones:**
- Ejecutado con `php artisan db:seed`
- Llama a seeders específicos
- Usa factories para generar datos realistas

**Importancia:**  
Permite resetear rápidamente la BD con datos de prueba en español. Esencial para desarrollo y demos.

---

### `factories/UserFactory.php`, `TaskFactory.php`, `EventFactory.php`, `PaymentFactory.php`
**¿Qué hacen?**  
Blueprints para generar datos de prueba usando Faker. Configurados con datos en **español**:
- `TaskFactory`: Títulos como "Ensayo sobre la Revolución Mexicana", "Proyecto de Ciencias Naturales"
- `EventFactory`: Eventos como "Junta de Padres", "Torneo de Fútbol", "Clase de Música"
- `PaymentFactory`: Conceptos como "Colegiatura Mensual", "Uniformes Deportivos", "Libros de Texto"

**Conexiones:**
- Usados por seeders
- Método `make()` crea instancia sin guardar
- Método `create()` guarda en BD

**Importancia:**  
Permite generar cientos de registros realistas para pruebas de carga y UI testing.

---

## 🎨 Frontend - JavaScript (resources/js/)

### `app.js`
**¿Qué hace?**  
Punto de entrada principal del JavaScript:
1. Importa `bootstrap.js` (configura Axios, CSRF)
2. Crea instancia de Vue 3 con `createApp()`
3. Registra componente `ExampleComponent.vue`
4. Monta Vue en `<div id="vue-root">` (no en `#app` para evitar parsear todo el layout)

**Conexiones:**
- Importado en `resources/views/layouts/app.blade.php` vía `@vite('resources/js/app.js')`
- Depende de `bootstrap.js`
- Compilado por Vite a `public/build/app-[hash].js`

**Importancia:**  
Inicializa todo el framework Vue. Sin él, los componentes interactivos no funcionarían.

---

### `bootstrap.js`
**¿Qué hace?**  
Configuración global de librerías:
- Importa Bootstrap JS (`import 'bootstrap'`)
- Expone `window.bootstrap` para acceso global
- Configura Axios con CSRF token automático (`X-Requested-With: XMLHttpRequest`)
- Prepara Echo para WebSockets (comentado)

**Conexiones:**
- Importado por `app.js`
- Axios usado en componentes Vue y scripts inline
- CSRF token leído de meta tag en layout

**Importancia:**  
Centraliza configuración HTTP. El manejo automático de CSRF evita errores 419 en POST/PUT/DELETE.

---

### `calendar.js`
**¿Qué hace?**  
**Archivo más complejo del frontend**. Inicializa y gestiona FullCalendar:

**Funcionalidades:**
1. **Inicialización robusta**: Polling con fallback si DOM no está listo
2. **Event Sources duales**: 
   - `/calendar/events` → Eventos y tareas
   - `/payments-calendar-events` → Pagos
3. **Sistema de filtros**: Checkboxes para mostrar/ocultar eventos/tareas/pagos
4. **Persistencia**: Guarda preferencias (vista, fecha, filtros) en LocalStorage con clave `calendarPreferences_v1`
5. **Estadísticas**: Contadores de eventos totales, tareas, pagos, items vencidos
6. **Helpers globales**: `window.changeCalendarView()`, `window.refreshCalendar()`, `window.toggleEventSource()`
7. **Loading overlay**: Progress bar animado durante carga de datos

**Importaciones:**
```javascript
import '@fullcalendar/daygrid/main.css';
import '@fullcalendar/timegrid/main.css';
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import esLocale from '@fullcalendar/core/locales/es';
```

**Conexiones:**
- Importado en `resources/views/calendar.blade.php` vía `@vite('resources/js/calendar.js')`
- Consume endpoints: `/calendar/events`, `/payments-calendar-events`
- Renderiza en `<div id="calendar">`
- Usa `window.routes` global definido en vista para URLs dinámicas

**Importancia:**  
**CRÍTICO**: El calendario es la feature estrella del sistema. Este archivo gestiona toda la UX del calendario, incluyendo filtrado avanzado, recurrencia, y sincronización con backend.

---

## 🎨 Frontend - Estilos (resources/sass/)

### `app.scss`
**¿Qué hace?**  
Archivo principal de estilos usando arquitectura modular con `@use`:
```scss
@use 'variables' as *;        // Variables globales primero
@use 'components/tasks';       // Estilos de módulo tareas
@use 'layout/layout';          // Layout general (navbar, hero)
@use 'layout/footer';          // Footer moderno
@import 'bootstrap/scss/bootstrap'; // Bootstrap (aún usa @import interno)
```

**Conexiones:**
- Compilado por Vite a `public/build/app-[hash].css`
- Incluido en layout vía `@vite('resources/sass/app.scss')`
- Parciales en `resources/sass/components/` y `resources/sass/layout/`

**Importancia:**  
Define toda la apariencia visual del sistema. Modularización permite mantenimiento escalable.

---

### `_variables.scss`
**¿Qué hace?**  
Define variables globales Sass:
- Paleta de colores primarios/secundarios
- Fuentes (Nunito)
- Breakpoints responsivos
- Espaciados estándar

**Conexiones:**
- Importado con `@use 'variables' as *;` en `app.scss`
- Usado por todos los parciales

**Importancia:**  
Centraliza theming. Cambiar un color aquí lo aplica en todo el sistema.

---

### `components/_tasks.scss`
**¿Qué hace?**  
Estilos específicos del módulo de tareas:
- Hero con gradiente animado
- Badges de estado (pendiente/completado)
- Animaciones de hover en cards
- Timeline de tareas

**Importancia:**  
Separación de concerns. Los estilos de tareas no contaminan otros módulos.

---

### `layout/_layout.scss`
**¿Qué hace?**  
Estilos globales de layout:
- Navbar con gradiente
- Hero sections generales
- Utilidades flex
- Tablas de pagos con colores por estado

**Importancia:**  
Define estructura visual consistente en todas las páginas.

---

### `layout/_footer.scss`
**¿Qué hace?**  
Footer moderno con gradiente y links sociales (simplificado, sin wave SVG).

**Importancia:**  
Branding y navegación secundaria.

---

## 🌐 Vistas Blade (resources/views/)

### `layouts/app.blade.php`
**¿Qué hace?**  
**Template maestro** de todas las páginas autenticadas:
- `<head>` con meta tags, CSRF token, directivas `@vite`
- Navbar con logo, enlaces a módulos (`tasks.index`, `events.index`, etc.), dropdown de notificaciones, dropdown de usuario
- `@yield('content')` donde se inyectan vistas hijas
- `<div id="vue-root">` para montaje de Vue
- Footer
- Scripts globales (Bootstrap tooltips, alerts auto-hide)

**Conexiones:**
- Extendido por todas las vistas con `@extends('layouts.app')`
- Usa `@vite('resources/js/app.js')` y `@vite('resources/sass/app.scss')`
- Accede a `Auth::user()` para mostrar nombre/email

**Importancia:**  
**CRÍTICO**: Toda la estructura HTML compartida. Un error aquí rompe todas las páginas.

---

### `calendar.blade.php`
**¿Qué hace?**  
Vista del calendario interactivo:
- Panel de filtros (checkboxes para eventos/tareas/pagos)
- Botones de cambio de vista (mes/semana/día)
- `<div id="calendar">` donde FullCalendar se monta
- Estadísticas en tiempo real (total eventos, pagos vencidos, etc.)
- Loading overlay con progress bar
- Script que define `window.routes` con URLs de endpoints

**Conexiones:**
- Carga `@vite('resources/js/calendar.js')`
- Consume datos de `EventController::apiEvents()` y `PaymentController::getCalendarEvents()`
- Usa helpers globales de `calendar.js`

**Importancia:**  
**FEATURE ESTRELLA**: Página más importante para UX. Centraliza visualización de todos los eventos/tareas/pagos.

---

### `tasks/index.blade.php`
**¿Qué hace?**  
Listado de tareas con:
- Buscador por título
- Filtro por fecha
- Cards de tareas con badges (completado/pendiente)
- Botones para editar/eliminar con confirmación
- Link para crear nueva tarea
- Botones de exportación (Excel/PDF)

**Conexiones:**
- Recibe variable `$tasks` desde `TaskController::index()`
- Extiende `layouts.app`
- Forms POST a `tasks.destroy` con método spoofing `@method('DELETE')`

**Importancia:**  
Vista principal del módulo de tareas. UX clara para gestión de deberes escolares.

---

### `events/index.blade.php`, `payments/index.blade.php`
**¿Qué hacen?**  
Similares a `tasks/index.blade.php` pero para eventos y pagos respectivamente:
- `events/index`: Lista eventos con filtros, muestra recurrencia
- `payments/index`: Dashboard financiero con gráficos de estadísticas, filtros por estado/categoría, paginación

**Importancia:**  
Interfaces especializadas para cada módulo. Payments tiene más complejidad por estadísticas agregadas.

---

### `home.blade.php`
**¿Qué hace?**  
Dashboard post-login con widgets:
- Tareas próximas a vencer (inyectadas por `AppServiceProvider`)
- Eventos de esta semana
- Resumen de pagos pendientes
- Links rápidos a módulos

**Conexiones:**
- Recibe `$tasksSoon` y `$eventsSoon` de View Composer
- Cards con animaciones CSS

**Importancia:**  
Primera impresión del usuario. Debe ser clara e informativa.

---

## ⚙️ Configuración (config/)

### `config/app.php`
**¿Qué hace?**  
Configuración general de Laravel:
- `timezone`: 'UTC' (cambiar a 'America/Mexico_City' si es necesario)
- `locale`: 'en' (podría cambiarse a 'es')
- `providers`: Service Providers registrados
- `aliases`: Facades de Laravel

**Importancia:**  
Configuración central de la aplicación. Cambios aquí afectan todo el sistema.

---

### `config/database.php`
**¿Qué hace?**  
Configuración de conexiones a base de datos:
- Default: SQLite (`database/database.sqlite`)
- También soporta MySQL, PostgreSQL, SQL Server
- Lee credenciales de `.env` (`DB_CONNECTION`, `DB_HOST`, etc.)

**Importancia:**  
Define cómo Laravel se conecta a la BD. Cambiar de SQLite a MySQL requiere modificar `.env` y este archivo.

---

### `config/filesystems.php`
**¿Qué hace?**  
Configuración de discos de almacenamiento:
- `local`: `storage/app/`
- `public`: `storage/app/public/` (enlazado a `public/storage` vía `php artisan storage:link`)
- `s3`: Soporte para Amazon S3 (requiere credenciales)

**Conexiones:**
- Usado por `$request->file()->store('attachments', 'public')` en `TaskController`

**Importancia:**  
Permite cambiar de almacenamiento local a cloud (S3) sin cambiar código de controladores.

---

### `config/mail.php`
**¿Qué hace?**  
Configuración de email:
- Mailers: SMTP, Mailgun, SES, etc.
- From address global
- Lee credenciales de `.env` (`MAIL_MAILER`, `MAIL_HOST`, etc.)

**Importancia:**  
Necesario para enviar notificaciones por email (reset password, alertas de pagos, etc.).

---

## 📤 Exportación de Datos (app/Exports/)

### `GenericArrayExport.php`
**¿Qué hace?**  
Clase base para exportar arrays a Excel usando `Maatwebsite\Excel`. Implementa interface `FromArray`.

**Conexiones:**
- Usada por `TaskExportController`, `EventExportController`
- Genera archivos `.xlsx`

**Importancia:**  
Abstracción reutilizable para exportaciones simples.

---

### `StyledArrayExport.php`
**¿Qué hace?**  
Extiende `GenericArrayExport` añadiendo estilos:
- Headers con fondo azul
- Bordes en celdas
- Auto-tamaño de columnas
- Implementa interfaces `WithHeadings`, `WithStyles`, `WithColumnWidths`

**Importancia:**  
Exportaciones con branding profesional.

---

### `TasksExport.php`, `EventsExport.php`, `CalendarExportExcel.php`
**¿Qué hacen?**  
Exportadores específicos que transforman modelos Eloquent a arrays con formato:
```php
['ID', 'Título', 'Descripción', 'Fecha Límite', 'Completado']
```

**Importancia:**  
Permiten a usuarios descargar sus datos en formato editable (Excel).

---

## 🔔 Notificaciones (app/Notifications/)

### `GeneralNotification.php`
**¿Qué hace?**  
Notificación customizable que puede enviarse vía:
- Database (tabla `notifications`)
- Email (requiere configurar MAIL en `.env`)
- Broadcast (WebSockets, requiere Echo)

Constructor acepta título, mensaje y datos adicionales.

**Conexiones:**
- Disparada con `$user->notify(new GeneralNotification(...))`
- Almacenada en `notifications` table
- Leída por `NotificationsController`

**Importancia:**  
Sistema flexible de alertas para recordatorios de tareas/pagos.

---

## 🔧 Providers (app/Providers/)

### `AppServiceProvider.php`
**¿Qué hace?**  
Service Provider principal con dos responsabilidades:

**1. Paginación con Bootstrap:**
```php
Paginator::useBootstrap();
```

**2. View Composer global:**  
Inyecta en TODAS las vistas autenticadas:
- `$tasksSoon`: Tareas que vencen en 3 días
- `$eventsSoon`: Eventos de los próximos 3 días

**Conexiones:**
- Registrado en `config/app.php`
- Query a `Task` y `Event` models
- Variables disponibles en layouts y componentes

**Importancia:**  
Permite mostrar widgets de "próximos a vencer" en navbar sin repetir código en cada controlador.

---

### `AuthServiceProvider.php`
**¿Qué hace?**  
Registra políticas de autorización:
```php
protected $policies = [
    Task::class => TaskPolicy::class,
    Payment::class => PaymentPolicy::class,
];
```

**Importancia:**  
Vincula modelos con sus políticas. Laravel automáticamente las aplica cuando usas `$this->authorize()`.

---

### `RouteServiceProvider.php`
**¿Qué hace?**  
Define dónde redirigir tras login (`/home`), y registra rutas con rate limiting.

**Importancia:**  
Configuración de comportamiento de rutas a nivel global.

---

## 📝 Archivos Especiales

### `artisan`
**¿Qué hace?**  
CLI de Laravel. Script PHP ejecutable que permite comandos como:
- `php artisan migrate`
- `php artisan db:seed`
- `php artisan serve`
- `php artisan tinker`
- `php artisan make:controller`

**Importancia:**  
**CRÍTICO**: Herramienta principal de desarrollo. Sin artisan, no se pueden ejecutar migraciones ni seeders.

---

### `public/index.php`
**¿Qué hace?**  
**Punto de entrada único** de todas las peticiones HTTP. Bootstrapea Laravel:
1. Carga autoloader de Composer (`vendor/autoload.php`)
2. Obtiene instancia de aplicación (`bootstrap/app.php`)
3. Captura Request
4. Envía Request al Kernel
5. Retorna Response

**Importancia:**  
**CRÍTICO**: Sin este archivo, Laravel no se ejecuta. Todos los requests web pasan por aquí.

---

### `public/build/manifest.json`
**¿Qué hace?**  
Generado por `npm run build`. Mapea archivos fuente a archivos compilados con hash:
```json
{
  "resources/js/app.js": {
    "file": "assets/app-d3f4f5e6.js",
    "css": ["assets/app-a1b2c3d4.css"]
  }
}
```

**Conexiones:**
- Leído por directiva `@vite()` en Blade
- Permite cache busting (hashes cambian al modificar archivos)

**Importancia:**  
Esencial para producción. Permite versionado de assets y cache HTTP óptimo.

---

### `storage/logs/laravel.log`
**¿Qué hace?**  
Log de la aplicación. Registra errores, warnings, queries SQL (si se habilita), etc.

**Importancia:**  
**CRÍTICO** para debugging. Siempre revisar logs cuando hay errores en producción.

---

### `database/database.sqlite`
**¿Qué hace?**  
Archivo binario de base de datos SQLite. Contiene todas las tablas y datos.

**Importancia:**  
**CRÍTICO**: Es la base de datos completa. Respaldar este archivo = respaldar todos los datos.

---

## 🧪 Testing (tests/)

### `tests/Feature/`
**¿Qué hace?**  
Tests de integración que prueban flujos completos HTTP (rutas, controladores, vistas).

**Ejemplo:**
```php
public function test_user_can_create_task() {
    $user = User::factory()->create();
    $response = $this->actingAs($user)->post('/tasks', [
        'title' => 'Nueva tarea',
        'description' => 'Descripción',
    ]);
    $response->assertRedirect('/tasks');
    $this->assertDatabaseHas('tasks', ['title' => 'Nueva tarea']);
}
```

**Importancia:**  
Previene regresiones. Tests automatizan validación de funcionalidades.

---

### `tests/Unit/`
**¿Qué hace?**  
Tests unitarios de lógica aislada (métodos de modelos, helpers, etc.).

**Importancia:**  
Valida lógica de negocio compleja (ej: algoritmo de recurrencia de eventos).

---

## 📊 Resumen de Conexiones Críticas

### Flujo de una petición típica (GET /tasks):
```
1. Navegador → public/index.php
2. Index.php → bootstrap/app.php → Kernel
3. Kernel → routes/web.php (encuentra Route::resource)
4. Router → Middleware Auth (verifica sesión)
5. Middleware → TaskController::index()
6. Controller → Task::where('user_id', $userId)->get()
7. Eloquent → database/database.sqlite (SELECT)
8. Database → retorna filas
9. Controller → view('tasks.index', compact('tasks'))
10. Blade → compila template con datos
11. Response → HTML al navegador
```

### Flujo de compilación frontend (npm run build):
```
1. CLI → vite.config.js
2. Vite → lee resources/js/app.js
3. App.js → importa bootstrap.js → importa Axios, Bootstrap
4. App.js → importa componentes Vue
5. Vite → lee resources/sass/app.scss
6. App.scss → @use variables, components, layout
7. Sass → compila a CSS
8. Vite → bundlea todo a public/build/app-[hash].js y .css
9. Vite → genera public/build/manifest.json
10. Blade → @vite('app.js') lee manifest y genera <script src="/build/app-[hash].js">
```

---

## 🎯 Archivos Más Importantes (Top 10)

| Archivo | Impacto | Razón |
|---------|---------|-------|
| `routes/web.php` | **CRÍTICO** | Define todas las rutas; sin él nada es accesible |
| `app/Models/User.php` | **CRÍTICO** | Base de autenticación y ownership |
| `app/Http/Controllers/EventController.php` | **ALTO** | Lógica de recurrencia y API del calendario |
| `resources/js/calendar.js` | **ALTO** | Feature estrella del sistema |
| `resources/views/layouts/app.blade.php` | **CRÍTICO** | Template maestro; error aquí rompe todo |
| `.env` | **CRÍTICO** | Configuración sensible y secretos |
| `database/migrations/*` | **CRÍTICO** | Define estructura de BD |
| `vite.config.js` | **ALTO** | Sin build del frontend, UI no funciona |
| `app/Providers/AppServiceProvider.php` | **MEDIO** | View composers inyectan datos globales |
| `public/index.php` | **CRÍTICO** | Punto de entrada; sin él Laravel no inicia |

---

## 🚀 Próximos Pasos Recomendados

1. **Localización completa**: Cambiar `config/app.php` locale a 'es' y traducir mensajes de validación
2. **Testing**: Escribir tests para EventController::apiEvents() y Payment scopes
3. **Optimización**: Implementar cache de queries frecuentes (estadísticas de pagos)
4. **Seguridad**: Añadir rate limiting a rutas de login/registro
5. **PWA**: Convertir en Progressive Web App con Service Workers para acceso offline
6. **Notificaciones Push**: Implementar recordatorios automáticos de pagos/tareas vencidos

---

**Generado automáticamente por análisis exhaustivo del código fuente**  
_Sistema Agenda Escolar - Laravel 12 + Vue 3 + FullCalendar 5_  
_Documentación actualizada al 28 de noviembre de 2025_
