# app/

Código de aplicación de Laravel (capa de dominio + HTTP).

Estructura relevante:
- Console/: Comandos Artisan personalizados.
- Exports/: Exportadores (Excel/PDF) para datos del sistema.
- Http/: Controladores, Middleware y Requests (validación).
- Models/: Modelos Eloquent y lógica de dominio.
- Notifications/: Canal database y notificaciones del sistema.
- Policies/: Autorización por recursos (PaymentPolicy, TaskPolicy).
- Providers/: Service Providers de la app.

Convenciones
- PHP 8+, Laravel 11.
- Nombrado en singular para modelos (Task, Payment, Event) y plural para tablas.
- Validación: Form Requests en Http/Requests cuando aplique.
- Autorización: Policies + gates. Usa `Auth` facade en controladores.
- Fechas: Carbon; zonas horarias en config/app.php.

Pruebas
- Las pruebas de características viven en `tests/Feature/` y cubren notificaciones, calendario, tareas, pagos y exportaciones.
- Ejecuta: `php artisan test`.

Notas clave
- Eventos recurrentes: no se materializan hijos; se expanden on-the-fly por rango.
- Pagos: exponen eventos para calendario mediante `PaymentController@getCalendarEvents`.
