# 📚 Agenda Escolar (Laravel 11)

Aplicación moderna para gestionar tareas, eventos, calendario y pagos escolares. UI clara con tema luminoso, gradientes y “glass cards”. Calendario estable con ocurrencias recurrentes generadas on‑the‑fly, exportaciones a Excel/PDF y batería de pruebas automatizadas.

## ✨ Novedades clave (última actualización)

- Ruta raíz con alias `home` para compatibilidad del navbar y pruebas.
- Calendario FullCalendar v5.11.5 estable:
    - Expansión de eventos recurrentes “bajo demanda” en el rango visible (no se materializan hijos en BD).
    - Filtros instantáneos (sin refetch) y estadísticas calculadas sobre elementos visibles.
    - Overlay/loader fiable con progreso y acción “Forzar recarga”.
    - Preferencias persistidas en `localStorage` (vista/fecha/filtros).
- Pagos: UI modernizada, badges de estado con contraste y endpoint JSON para eventos de calendario.
- Exportaciones: migradas a un modal Bootstrap estable (Excel/PDF) para tareas, eventos y calendario.
- Notificaciones: listadas desde canal database, acciones de marcar leído(s) y borrar; acceso desde el navbar.
- Seeders y factories completas para usuarios, tareas, eventos, pagos y notificaciones.
- Pruebas feature en verde (22/22) cubriendo calendario (incluye recurrencias), pagos, notificaciones, tareas y export.

## 🛠️ Stack técnico

- Backend: Laravel 11
- Vistas: Blade + Bootstrap 5 + JS (FullCalendar vía CDN)
- Base de datos: SQLite/MySQL
- Exportación: Laravel Excel, DomPDF
- Iconos: FontAwesome

## 📋 Requisitos

- PHP >= 8.1
- Composer
- Node.js + npm
- SQLite o MySQL

## 🚀 Instalación y arranque

1) Clonar e instalar dependencias

```powershell
git clone https://github.com/Pachecosaurio/agenda-escolar.git
cd agenda-escolar
composer install
npm install
```

2) Configurar entorno y clave

```powershell
copy .env.example .env
php artisan key:generate
```

3) Configurar BD (opciones)

- SQLite rápido: en `.env` define

```
DB_CONNECTION=sqlite
DB_DATABASE="database/database.sqlite"
```

    y crea el archivo vacío `database/database.sqlite` si no existe.

- MySQL: ajusta `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.

4) Migraciones y datos de prueba

```powershell
php artisan migrate --seed
```

5) Compilar assets y correr servidor

```powershell
npm run dev
php artisan serve
```

## 📅 Calendario y recurrencias

- Endpoint: `GET` `route('calendar.events')` devuelve eventos (incluidas ocurrencias recurrentes dentro del rango solicitado) y tareas con fecha en rango.
- Reglas de recurrencia: se expanden en memoria según el rango visible; no se crean registros hijos en BD; las “ocurrencias” no aparecen en listados ni exportaciones si no están en rango.
- Preferencias del usuario (vista, fecha, filtros) se guardan en `localStorage`.

## 💳 Pagos

- Página de pagos con tarjetas “glass”, estadísticas y filtros.
- Badges de estado con clases Bootstrap para máximo contraste.
- Endpoint JSON para alimentar eventos de calendario desde pagos.

## 📤 Exportaciones

- Tareas: Excel/PDF
- Eventos: Excel/PDF
- Calendario: Excel/PDF
- UI de export se muestra en un modal Bootstrap para evitar cierres involuntarios.

## 🔔 Notificaciones

- Canal database (Laravel). Vista `/notifications` con listado, marcar leído(s) y borrar.
- Acceso directo desde el navbar con contador de no leídas.

## 🧪 Pruebas

- Suite feature y unit con 22 pruebas / 78 aserciones (en verde).
- Ejecutar:

```powershell
php artisan test
```

## 🗺️ Rutas útiles

- Home: `/` (alias `home`)
- Tareas: `tasks.*` (CRUD y export)
- Eventos: `events.*` (CRUD y export)
- Calendario: `calendar` (vista) / `calendar.events` (API) / `calendar.export*` (export)
- Pagos: `payments.*` (CRUD) / `payments.calendar-events` (API)
- Notificaciones: `notifications.*`

## 📁 Estructura del proyecto (resumen)

```
app/
    Http/Controllers/   # Controladores (Tasks, Events, Payments, Notifications, Exports)
    Models/             # Modelos (Task, Event, Payment, User, ...)
resources/
    views/              # Blade (calendario, pagos, notifs, tareas, eventos, layout)
    js/, sass/, css/    # Assets con Vite
database/
    migrations/, seeders/, factories/
public/
    css/, js/, build/
```

## 🔧 Personalización rápida (CSS)

```css
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    --glass-bg: rgba(255, 255, 255, 0.25);
    --glass-border: rgba(255, 255, 255, 0.18);
}
```

## 🤝 Contribución

1. Haz fork
2. Crea rama: `git checkout -b feature/mi-mejora`
3. Commit: `git commit -m "feat: describe tu mejora"`
4. Push: `git push origin feature/mi-mejora`
5. Abre un Pull Request

## 📄 Licencia

MIT. Ver `LICENSE`.

## 👤 Autor

**Pachecosaurio**

- GitHub: [@Pachecosaurio](https://github.com/Pachecosaurio)
- Email: Jafetpd.md24@universidadupp.edu.mx

---

⭐ Si este proyecto te resulta útil, ¡déjale una estrella! ⭐
