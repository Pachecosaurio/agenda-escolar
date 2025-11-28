# 📚 Documentación Técnica Completa - Agenda Escolar

> Sistema de gestión académica desarrollado con **Laravel 12**, **Vue.js 3** y **FullCalendar 5**

---

## 📑 Índice de Documentación

### 🎨 [Diagramas de Arquitectura](./diagramas_arquitectura.html)
**Archivo HTML interactivo** con visualizaciones completas usando Mermaid.js:

- **Diagrama de Arquitectura de Alto Nivel**: Capas Frontend → Backend → Database con todas las conexiones
- **Mapa de Tecnologías**: Todas las librerías y frameworks con sus dependencias
- **Flujo de Datos**: Secuencia completa desde petición HTTP hasta respuesta
- **Modelo Entidad-Relación**: Schema de base de datos con relaciones
- **Arquitectura Frontend**: Estructura de archivos JS/CSS y build pipeline Vite
- **Ciclo de Vida de Petición**: Pipeline completo de Laravel (routing → middleware → controller → response)

**Cómo abrir**: Doble click en el archivo HTML o abrirlo desde el navegador.

---

### 📖 [Diccionario de Archivos](./explicacion_archivos.md)
**Archivo Markdown** con explicación detallada de cada archivo del proyecto:

- Archivos de configuración raíz (`composer.json`, `package.json`, `vite.config.js`, `.env`)
- Sistema de rutas (`routes/web.php`)
- Todos los controladores con sus responsabilidades
- Modelos Eloquent con relaciones y métodos clave
- Políticas de autorización
- Migraciones y seeders
- Archivos JavaScript del frontend (`app.js`, `bootstrap.js`, `calendar.js`)
- Arquitectura de estilos Sass
- Vistas Blade principales
- Archivos de configuración (`config/`)
- Sistema de exportación (Excel/PDF)
- Notificaciones
- Service Providers
- Testing

**Cada sección incluye**:
- ✅ ¿Qué hace este archivo?
- 🔗 ¿Con qué otros archivos se conecta?
- ⚡ ¿Por qué es importante para el proyecto?

---

## 🏗️ Stack Tecnológico

### Backend
- **Framework**: Laravel 12.0 (PHP 8.2+)
- **ORM**: Eloquent
- **Base de Datos**: SQLite (desarrollo), MySQL (producción)
- **Autenticación**: Laravel Auth + Policies
- **Exportación**: DomPDF 3.1, Maatwebsite Excel 3.1

### Frontend
- **Framework SPA**: Vue.js 3.2.37
- **Calendario**: FullCalendar 5.11.5 (Core, DayGrid, TimeGrid, Interaction)
- **CSS Framework**: Bootstrap 5.3.8
- **Preprocesador**: Sass 1.56.1 (arquitectura @use modular)
- **HTTP Client**: Axios 1.11.0
- **Build Tool**: Vite 6.0 con HMR

### DevTools
- **Gestor PHP**: Composer
- **Gestor JS**: NPM
- **Testing**: PHPUnit 11.5.3, Faker 1.23
- **Linting**: Laravel Pint 1.24
- **Docker**: Laravel Sail 1.41

---

## 🗂️ Estructura del Proyecto

```
agenda-escolar/
│
├── app/                          # Lógica de aplicación Laravel
│   ├── Http/Controllers/         # 9 controladores (Task, Event, Payment, etc.)
│   ├── Models/                   # 4 modelos principales (User, Task, Event, Payment)
│   ├── Policies/                 # Autorización (TaskPolicy, PaymentPolicy)
│   ├── Exports/                  # Clases para exportar Excel/PDF
│   ├── Notifications/            # GeneralNotification
│   └── Providers/                # Service Providers (App, Auth, Route)
│
├── database/
│   ├── migrations/               # 14 migraciones de schema
│   ├── seeders/                  # Seeders en español
│   └── factories/                # Factories con datos realistas en español
│
├── resources/
│   ├── views/                    # Vistas Blade (layouts, tasks, events, payments, calendar)
│   ├── js/                       # app.js, bootstrap.js, calendar.js, components/
│   └── sass/                     # Arquitectura modular (_variables, components/, layout/)
│
├── routes/
│   └── web.php                   # Todas las rutas del sistema
│
├── config/                       # Configuración Laravel (app, database, mail, etc.)
├── public/                       # Assets compilados y punto de entrada
├── storage/                      # Logs, cache, uploads
├── tests/                        # Tests Feature y Unit
│
├── documentos/                   # 📚 ESTA DOCUMENTACIÓN
│   ├── README.md                 # Este archivo
│   ├── diagramas_arquitectura.html
│   └── explicacion_archivos.md
│
├── composer.json                 # Dependencias PHP
├── package.json                  # Dependencias JavaScript
├── vite.config.js                # Configuración de build
└── artisan                       # CLI de Laravel
```

---

## 🎯 Módulos Principales del Sistema

### 1. 📝 Gestión de Tareas
- CRUD completo
- Adjuntos (hasta 10MB)
- Fechas de vencimiento
- Estado completado/pendiente
- Búsqueda y filtros
- Exportación Excel/PDF

### 2. 📅 Calendario de Eventos
- Eventos simples y recurrentes (daily/weekly/monthly/yearly)
- Integración con tareas
- Visualización multi-vista (mes/semana/día)
- Filtros en tiempo real
- LocalStorage para preferencias
- Expansión de recurrencias on-the-fly (sin materializar en BD)

### 3. 💰 Sistema de Pagos
- Múltiples categorías (colegiatura, libros, transporte, etc.)
- Estados: pending, paid, overdue
- Estadísticas agregadas
- Soft deletes
- Integración con calendario
- Filtros avanzados y paginación

### 4. 🔔 Notificaciones
- Sistema database-driven
- Marcar como leído
- Eliminar individual o masivo
- Extensible a email/broadcast

---

## 🚀 Comandos Útiles

### Desarrollo
```bash
# Backend (terminal 1)
php artisan serve

# Frontend (terminal 2)
npm run dev

# Acceder a http://127.0.0.1:8000
```

### Base de Datos
```bash
# Ejecutar migraciones
php artisan migrate

# Ejecutar seeders (datos de prueba en español)
php artisan db:seed

# Resetear BD completa
php artisan migrate:fresh --seed

# Usuario demo:
# Email: demo@agendaescolar.com
# Password: password
```

### Build para Producción
```bash
npm run build
php artisan optimize
```

### Testing
```bash
php artisan test
```

---

## 🔐 Seguridad Implementada

- ✅ CSRF Protection en todos los formularios
- ✅ Autenticación con Laravel Auth
- ✅ Autorización con Policies (row-level security)
- ✅ Passwords hasheados con bcrypt
- ✅ Queries parametrizadas (Eloquent previene SQL injection)
- ✅ XSS protection vía Blade escaping automático
- ✅ Mass assignment protection con `$fillable`

---

## 📊 Características Técnicas Destacadas

### Backend
- **Arquitectura MVC** estricta
- **Service Providers** para inyección de dependencias
- **View Composers** para datos globales
- **Scopes de Eloquent** para queries reutilizables
- **Soft Deletes** en pagos
- **Accessors** para presentación de datos
- **Policies** para autorización granular

### Frontend
- **Componentes Vue 3** con Composition API
- **Build optimizado** con code splitting
- **HMR** para desarrollo rápido
- **LocalStorage** para persistencia del lado cliente
- **Filtrado en memoria** para UX instantánea
- **Progress bars** animados durante carga
- **Arquitectura Sass modular** con `@use`

### Base de Datos
- **Migraciones versionadas** para control de schema
- **Factories** para datos de prueba realistas
- **Seeders** localizados en español
- **Relaciones Eloquent** (hasMany, belongsTo)
- **Auto-incremento** y **UUIDs** donde aplica
- **Índices** en foreign keys y campos de búsqueda

---

## 📈 Métricas del Proyecto

| Métrica | Valor |
|---------|-------|
| Líneas de código PHP | ~8,500 |
| Líneas de código JavaScript | ~2,100 |
| Líneas de código Sass | ~1,800 |
| Archivos Blade | 28 |
| Controladores | 9 |
| Modelos | 4 principales |
| Migraciones | 14 |
| Rutas web | 30+ |
| Dependencias NPM | 21 |
| Dependencias Composer | 12 |

---

## 🎓 Conceptos Avanzados Implementados

1. **Event Recurrence Algorithm**: Generación de ocurrencias en memoria sin materializar registros
2. **View Composers**: Inyección automática de datos en todas las vistas autenticadas
3. **Policy-based Authorization**: Autorización declarativa a nivel de modelo
4. **Soft Deletes**: Eliminación lógica con recuperación
5. **Resource Controllers**: Controladores RESTful completos
6. **Eloquent Scopes**: Queries reutilizables y chainables
7. **Mass Assignment Protection**: Seguridad contra asignación masiva maliciosa
8. **CSRF Token Auto-injection**: Protección automática contra CSRF en AJAX
9. **Asset Versioning**: Cache busting con hashes de Vite
10. **Modular Sass Architecture**: Separación de concerns en estilos

---

## 🤝 Contribución y Mantenimiento

### Añadir un nuevo módulo
1. Crear migración: `php artisan make:migration create_<tabla>_table`
2. Crear modelo: `php artisan make:model <Modelo> -mfsc` (con migration, factory, seeder, controller)
3. Definir rutas en `routes/web.php`
4. Crear vistas en `resources/views/<modulo>/`
5. Añadir estilos en `resources/sass/components/_<modulo>.scss`
6. Importar en `resources/sass/app.scss`

### Debugging
- **Backend**: Revisar `storage/logs/laravel.log`
- **Frontend**: Abrir DevTools del navegador
- **Queries SQL**: Habilitar query log en `AppServiceProvider`
- **Laravel Telescope**: Instalar para profiling avanzado

---

## 📞 Contacto y Soporte

Este proyecto es un sistema académico completo con arquitectura profesional y mejores prácticas de desarrollo web moderno.

---

**Generado automáticamente mediante análisis profundo del código fuente**  
_Actualizado: 28 de noviembre de 2025_
