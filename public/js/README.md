# public/js/

JavaScript público (sirve assets estáticos o compilados por Vite).

Archivos relevantes
- `calendar.js`: inicializa FullCalendar v5.11.5; múltiples fuentes de eventos (tareas, pagos, eventos), overlay/loader con progreso, filtros instantáneos, estadísticas sobre elementos visibles y persistencia de preferencias en `localStorage`.

Guías
- Mantén funciones puras cuando sea posible; comenta con JSDoc las funciones públicas.
- Evita dependencias globales no necesarias; usa IIFE o módulos si migras a bundling.
