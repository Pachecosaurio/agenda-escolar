# 📚 Sistema de Agenda Escolar

Sistema de gestión escolar moderno desarrollado con Laravel, que incluye gestión de tareas, eventos, calendario y pagos de colegiatura con un diseño UI/UX contemporáneo.

## ✨ Características Principales

### 📝 Gestión de Tareas
- Crear, editar y eliminar tareas
- Asignación de prioridades y fechas de vencimiento
- Exportación a PDF y Excel
- Filtrado y búsqueda avanzada

### 📅 Sistema de Calendario
- Vista de calendario interactiva con FullCalendar
- Gestión de eventos y citas
- Vista mensual, semanal y diaria
- Integración con tareas y pagos

### 🎉 Gestión de Eventos
- Creación y administración de eventos escolares
- Eventos recurrentes
- Notificaciones automáticas
- Exportación de reportes

### 💳 Sistema de Pagos
- Gestión de colegiaturas y pagos escolares
- Múltiples métodos de pago
- Generación de comprobantes
- Reportes financieros

### 🎨 Diseño Moderno
- Interface con efectos glass y gradientes
- Modo oscuro/claro
- Responsive design
- Animaciones y transiciones suaves

## 🛠️ Tecnologías Utilizadas

- **Backend**: Laravel 11
- **Frontend**: Blade Templates + Vue.js
- **Styling**: CSS3 con efectos modernos, Bootstrap 5
- **Base de datos**: SQLite/MySQL
- **Calendar**: FullCalendar v5.11.5
- **Exportación**: Laravel Excel, DomPDF
- **Icons**: FontAwesome

## 📋 Requisitos del Sistema

- PHP >= 8.1
- Composer
- Node.js & NPM
- SQLite o MySQL

## 🚀 Instalación

1. **Clonar el repositorio**
```bash
git clone https://github.com/Pachecosaurio/agenda-escolar.git
cd agenda-escolar
```

2. **Instalar dependencias de PHP**
```bash
composer install
```

3. **Instalar dependencias de Node.js**
```bash
npm install
```

4. **Configurar el archivo de entorno**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Configurar la base de datos**
Editar `.env` con los datos de tu base de datos

6. **Ejecutar migraciones**
```bash
php artisan migrate
```

7. **Compilar assets**
```bash
npm run dev
# o para producción
npm run build
```

8. **Iniciar el servidor**
```bash
php artisan serve
```

## 📁 Estructura del Proyecto

```
agenda_escolar/
├── app/
│   ├── Http/Controllers/     # Controladores
│   ├── Models/              # Modelos Eloquent
│   ├── Exports/             # Clases para exportación
│   └── Policies/            # Políticas de autorización
├── resources/
│   ├── views/               # Templates Blade
│   ├── css/                 # Estilos CSS
│   └── js/                  # JavaScript/Vue.js
├── database/
│   ├── migrations/          # Migraciones de BD
│   └── seeders/            # Seeders
└── public/
    ├── css/                # CSS compilado
    └── js/                 # JavaScript compilado
```

## 🎯 Funcionalidades Destacadas

### Sistema de Exportación
- PDF con diseño profesional
- Excel con formato avanzado
- Reportes personalizables

### Interface de Usuario
- Efectos glass con `backdrop-filter`
- Gradientes CSS modernos
- Animaciones fluidas
- Sistema de notificaciones

### Seguridad
- Autenticación Laravel
- Políticas de autorización
- Validación de formularios
- Protección CSRF

## 🔧 Configuración Avanzada

### Variables CSS Personalizadas
El sistema utiliza variables CSS para fácil personalización:

```css
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --glass-bg: rgba(255, 255, 255, 0.1);
    --shadow-xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}
```

### Configuración del Calendario
FullCalendar configurado con:
- Vista responsive
- Eventos dinámicos
- Integración con base de datos
- Soporte para múltiples idiomas

## 🤝 Contribución

1. Fork el proyecto
2. Crea una rama feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver `LICENSE` para más detalles.

## 👨‍💻 Autor

**Pachecosaurio**
- GitHub: [@Pachecosaurio](https://github.com/Pachecosaurio)
- Email: Jafetpd.md24@universidadupp.edu.mx

## 🙏 Agradecimientos

- Laravel Framework
- FullCalendar.io
- Bootstrap Team
- FontAwesome
- Comunidad de desarrolladores

---

⭐ **¡Si te gusta este proyecto, dale una estrella!** ⭐

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
