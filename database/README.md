# database/

Migraciones, factories y seeders.

Uso
- Migrar: `php artisan migrate`
- Poblar datos de ejemplo: `php artisan db:seed`
- Pruebas: la suite crea datos mediante factories para aislar escenarios.

Convenciones
- Migraciones con timestamps y prefijos ordenados cronológicamente.
- Factories en `database/factories/` para User, Payment, Task, Event.
- Seeders configuran un escenario mínimo funcional para demo y tests.
