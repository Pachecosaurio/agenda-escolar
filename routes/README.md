# routes/

Definiciones de rutas.

Archivos
- `web.php`: rutas web con middleware de sesión/CSRF. Incluye `Route::get('/', HomeController@index)->name('home')`.
- `console.php`: comandos programados/Artisan.

Guías
- Usa alias `name('...')` para navegación estable en Blade.
- Agrupa por middleware y prefijos cuando sea oportuno.
