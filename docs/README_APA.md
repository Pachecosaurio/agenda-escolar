# Agenda Escolar — Documento en Formato APA (Guía y Contenido)

Este documento describe el proyecto Agenda Escolar en tercera persona y proporciona una guía práctica para presentar el informe en formato APA, incluyendo requisitos mínimos, librerías, framework, funciones, resumen/introducción, puntos de mejora y visión futura.

## Portada e Índice
- La portada y el índice no se numeran ni se contabilizan en la paginación.
- La numeración de páginas inicia después del índice, en la primera página de contenido.

## Normas de Formato (APA)
- Márgenes: $2.5\,\text{cm}$ en los cuatro lados.
- Interlineado: $1.5$ en todo el documento (incluyendo referencias).
- Tipografía sugerida: Times New Roman o Arial, 12 pt.
- Paginación: número de página en el pie de página, alineado a la izquierda.
- Estructura de numeración: arábiga (1, 2, 3, …), iniciando en la primera página de contenido.
- Portada e índice: excluidos del conteo y sin número visible.
- Puntuación y estilo: uso correcto de comas, puntos, mayúsculas y minúsculas; redacción en tercera persona y voz impersonal.

Sugerencia práctica (Microsoft Word):
- Diseño > Márgenes > Personalizar márgenes > 2.5 cm en todos los lados.
- Inicio > Interlineado > 1.5.
- Insertar > Número de página > Parte inferior > Izquierda.
- Insertar > Salto de sección (Página siguiente) para separar portada/índice del contenido; en el pie, desactivar "Vincular al anterior" y comenzar numeración en 1.

## Resumen e Introducción
Agenda Escolar es una aplicación web para gestionar tareas, eventos (con recurrencia), calendario, pagos escolares, exportaciones (Excel/PDF) y notificaciones. La interfaz emplea un diseño claro con componentes de Bootstrap y un calendario basado en FullCalendar. Las ocurrencias recurrentes de eventos se expanden bajo demanda según el rango visible en el calendario, sin materializar registros hijos en base de datos. Se ofrecen filtros rápidos, estadísticas sobre elementos visibles y exportaciones consolidadas. El objetivo es facilitar la organización académica con énfasis en claridad visual, estabilidad del calendario y flujo de trabajo eficiente.

## Requisitos Mínimos para que Corra
- Software:
  - PHP ≥ 8.1 (en desarrollo se ha validado con PHP 8.3 en Laragon).
  - Composer.
  - Node.js y npm (Vite para assets).
  - Base de datos: SQLite (recomendada para desarrollo) o MySQL/MariaDB (recomendada para producción).
- Hardware:
  - CPU x64, 4 GB de RAM mínimo.
  - 500 MB de almacenamiento para dependencias y cachés.

## Prioridad Online (Despliegue)
- Producción recomendada en entorno web con servidor HTTP (Nginx/Apache), PHP-FPM y base de datos MySQL/MariaDB.
- Configuración de `APP_URL` y drivers de caché/cola según infraestructura.
- Assets compilados con Vite (modo producción) y cache busting.
- Para entornos con recursos limitados, se prioriza SQLite y despliegue monolítico.

## Framework y Librerías
- Framework backend: Laravel (versión instalada en el proyecto: 12.x).
- Vistas y assets: Blade, Bootstrap 5, Vite; uso de Vue 3 para componentes puntuales.
- Calendario: FullCalendar 6 (módulos daygrid, timegrid, list; integración Bootstrap 5).
- Exportación: Laravel Excel (PhpSpreadsheet) y DomPDF.
- Notificaciones: canal database de Laravel.

## Funciones Principales
- Gestión de tareas (CRUD) y exportación a Excel/PDF.
- Gestión de eventos con reglas de recurrencia; expansión on-the-fly dentro del rango visible del calendario.
- Calendario con filtros instantáneos y estadísticas sobre elementos visibles; persistencia de preferencias en `localStorage`.
- Gestión de pagos con tarjetas de estado, estadísticas y eventos de calendario derivados.
- Exportaciones unificadas (tareas, eventos, calendario) a Excel/PDF mediante una UI modal.
- Notificaciones: listado, marcar leído(s) y borrar; acceso desde el navbar.

## Instalación Rápida (Desarrollo en Windows con Laragon)
1) Dependencias
```powershell
composer install
npm.cmd install
```
2) Entorno
```powershell
copy .env.example .env  # Si no existe, crear .env con DB sqlite
php artisan key:generate
```
3) Base de datos (opción rápida con SQLite)
```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```
Crear archivo vacío en `database/database.sqlite` si no existe.
4) Migraciones y seeders
```powershell
php artisan migrate --seed
```
5) Servidores de desarrollo
```powershell
npm.cmd run dev
php artisan serve
```

## Despliegue (Producción)
- Configurar MySQL/MariaDB y variables de entorno (`APP_ENV=production`, `APP_KEY`, `APP_URL`, `DB_*`).
- Compilar assets:
```powershell
npm.cmd run build
```
- Configurar el servidor (Nginx/Apache) apuntando a `public/`.
- Habilitar cachés:
```powershell
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Identidad Visual (Logotipo)
- Función: identificar la aplicación en la interfaz (navbar, portada del documento y material de apoyo) y reforzar el reconocimiento de marca.
- Uso recomendado: colocar el logotipo en la portada a 300 dpi, respetando proporción y área de protección; tamaño suficiente para legibilidad sin invadir márgenes APA.
- Ubicaciones: portada (centrado o superior), navbar de la aplicación, exportaciones a PDF opcionales.

## Puntos a Mejorar y Visión a Futuro
- Autenticación/roles avanzados y permisos granulares para módulos específicos.
- API pública con autenticación por token para integraciones externas.
- Recordatorios por correo y/o push notifications.
- Editor enriquecido de descripciones y adjuntos en tareas/eventos.
- Soporte de i18n completo (ES/EN) y accesibilidad (WCAG).
- Reportes analíticos avanzados (tendencias, asistencia, cumplimiento).
- CI/CD con pruebas y despliegue automatizado.

---

Nota: Este archivo guía el contenido y formato; para obtener el estilo APA exacto (márgenes, interlineado y paginación en pie izquierdo), se sugiere usar el template LaTeX incluido en `docs/apa_template.tex` o aplicar la configuración indicada en Microsoft Word.