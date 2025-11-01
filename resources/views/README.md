# resources/views/

Vistas Blade del proyecto.

Puntos clave
- `layouts/app.blade.php`: layout base con navbar (enlace a `route('home')`), dropdown de notificaciones y carga de FullCalendar por CDN (con fallback).
- `payments/index.blade.php`: UI moderna con filtros instantáneos, estadísticas, badges de estado y paginación.
- Calendario: hoja y scripts asociados en el layout y `public/js/calendar.js`.

Convenciones de Blade
- Usa secciones `@section`, `@yield` y componentes cuando apliquen.
- Comentarios Blade: `{{-- ... --}}`.
- Bootstrap 5 para estilos; clases utilitarias preferidas sobre CSS custom.

Localización
- Textos en español; formateo de fechas con helpers/Carbon.
