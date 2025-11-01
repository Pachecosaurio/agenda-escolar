# app/Models/

Modelos Eloquent y lógica de dominio.

Guías
- Atributos: usa casts para fechas/booleans; agrega accessors cuando necesites presentación (p. ej., estado legible).
- Scopes: prefiera `scope` para filtros reutilizables (Payments con estado/categoría).
- Relaciones: define belongsTo/hasMany explícitas entre User, Task, Payment, Event.
- Fechas: utiliza Carbon; evitar materializar ocurrencias recurrentes.

Casos particulares
- Event: método `occurrencesBetween($start, $end)` expande recurrencias por rango.
- Payment: atributos de presentación y categorías; también expone eventos para el calendario.
