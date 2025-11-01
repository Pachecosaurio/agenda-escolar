# app/Http/Controllers/

Controladores HTTP (capa de entrega) que orquestan solicitudes web.

Buenas prácticas en este proyecto
- Validación: usar `FormRequest` cuando el flujo sea complejo.
- Autenticación/Autorización: `Auth` facade para usuario actual; aplicar `authorizeResource`/Policies donde corresponda.
- Respuestas: retornar vistas Blade para HTML y `response()->json()` para APIs.
- Nombres de rutas consistentes y con alias (`name('...')`).

Controladores relevantes
- HomeController: Home público (`/`) y dashboard autenticado.
- EventController: CRUD + API de calendario. Expande recurrencias en tiempo real.
- TaskController: CRUD de tareas, filtros, adjuntos, ownership por usuario.
- PaymentController: Listados con métricas y exportación, y fuente de eventos para calendario.
- NotificationsController: Listado/paginación de notificaciones (canal database).

Errores comunes evitados
- Reemplazar helpers `auth()` por `Auth::...` (facade) para limpiar warnings.
- Excluir hijos generados (recurrencias) en listados: `whereNull('parent_event_id')`.
