# Prompt para ChatGPT - Sección de Pagos Completa

## Contexto del Proyecto
Estoy trabajando en una aplicación Laravel 12 llamada "Agenda Escolar" que gestiona tareas, eventos y pagos escolares. La aplicación utiliza un framework CSS personalizado llamado `modern-ui.css` que implementa efectos glass morphism y un diseño moderno con gradientes.

## Estructura Actual del Proyecto
```
app/
├── Models/
│   ├── Event.php
│   ├── Task.php
│   └── User.php
├── Http/Controllers/
└── Policies/

resources/
├── views/
│   ├── layouts/app.blade.php
│   ├── events/ (sección completa funcionando)
│   ├── tasks/ (sección completa funcionando)
│   └── payments/ (necesita implementación completa)
└── css/modern-ui.css

routes/web.php
```

## Framework CSS Disponible (modern-ui.css)
El proyecto ya cuenta con estas clases CSS predefinidas que DEBEN ser utilizadas:

### Componentes Principales:
- `.hero-section` - Sección principal con gradiente y elementos flotantes
- `.floating-elements` - Elementos decorativos animados
- `.stats-card` - Tarjetas para estadísticas con efectos glass
- `.glass-card` - Contenedores principales con efecto cristal
- `.glass-effect` - Efecto cristal para elementos internos
- `.action-btn` - Botones de acción con gradiente
- `.icon-wrapper` - Contenedores para iconos con animaciones

### Variables CSS Disponibles:
- `var(--glass-bg)` - Fondo con efecto cristal
- `var(--glass-blur)` - Desenfoque para backdrop-filter
- `var(--primary-gradient)` - Gradiente principal de la aplicación

### Animaciones:
- `.fade-in` con `data-delay` - Animaciones de entrada escalonadas
- `.with-ring` - Anillos pulsantes en iconos

## Requerimientos Específicos

### 1. Estructura MVC Completa
Necesito que implementes:
- **Modelo**: `Payment.php` con relaciones y mutators
- **Controlador**: `PaymentController.php` con métodos CRUD completos
- **Vistas**: Todas las vistas Blade siguiendo el patrón establecido
- **Rutas**: Resource routes en `web.php`
- **Migraciones**: Tabla payments con todos los campos necesarios

### 2. Campos del Modelo Payment
```php
// Campos requeridos para la tabla payments:
- id (primary key)
- title (string, required)
- description (text, nullable)
- amount (decimal, required)
- category (enum: tuition, books, activities, transport, cafeteria, other)
- status (enum: pending, paid, overdue)
- due_date (date, required)
- paid_date (date, nullable)
- payment_method (enum: cash, card, transfer, online, nullable)
- notes (text, nullable)
- user_id (foreign key)
- timestamps
```

### 3. Diseño Visual - CRUCIAL
**USAR EXCLUSIVAMENTE las clases de modern-ui.css. NO crear CSS personalizado.**

#### Patrón de Diseño a Seguir:
```blade
@extends('layouts.app')

@section('content')
<div class="hero-section">
    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>
    
    <div class="container-fluid hero-content">
        <!-- Contenido usando stats-card, glass-card, glass-effect, action-btn -->
    </div>
</div>
@endsection
```

#### Iconos por Categoría:
- Colegiatura: `fas fa-graduation-cap`
- Libros: `fas fa-book`
- Actividades: `fas fa-running`
- Transporte: `fas fa-bus`
- Cafetería: `fas fa-utensils`
- Otros: `fas fa-receipt`

### 4. Vistas Requeridas

#### index.blade.php
- Hero section con estadísticas usando `.stats-card`
- Tarjetas de categorías con `.glass-effect`
- Navegación con `.action-btn`
- Animaciones `.fade-in` con delays escalonados

#### create.blade.php
- Formulario con `.glass-card` y `.glass-effect`
- Campos dinámicos según el tipo de pago
- Validación visual inline
- Botones con `.action-btn`

#### show.blade.php
- Información detallada con `.stats-card` para datos principales
- Secciones de detalles con `.glass-effect`
- Estado visual del pago con badges coloridos
- Botones de acción (editar, eliminar, imprimir)

#### edit.blade.php
- Similar a create pero pre-poblado
- Campos condicionales para pagos completados
- JavaScript para mostrar/ocultar campos según estado

### 5. Funcionalidades Específicas

#### Controlador:
- Middleware de autenticación
- Validación completa en store/update
- Filtros por categoría y estado
- Cálculos automáticos de totales
- Soft deletes

#### JavaScript:
- Campos condicionales en formularios
- Animaciones de entrada
- Validación en tiempo real
- Formateo de moneda

### 6. Integración con Sistema Existente
- Seguir el patrón de nomenclatura de rutas: `payments.index`, `payments.create`, etc.
- Integrar con navbar existente (enlace ya configurado)
- Usar el mismo layout base (`layouts.app`)
- Mantener consistencia con secciones de events y tasks

### 7. Validación y Seguridad
```php
// Reglas de validación requeridas:
'title' => 'required|string|max:255',
'amount' => 'required|numeric|min:0',
'category' => 'required|in:tuition,books,activities,transport,cafeteria,other',
'status' => 'required|in:pending,paid,overdue',
'due_date' => 'required|date',
'paid_date' => 'nullable|date|after_or_equal:created_at',
```

## Entregables Esperados

1. **Migración completa** con todos los campos
2. **Modelo Payment** con relaciones, mutators y scopes
3. **PaymentController** con todos los métodos CRUD
4. **4 vistas Blade** (index, create, show, edit) usando modern-ui.css
5. **Rutas resource** configuradas
6. **JavaScript necesario** para interactividad
7. **Validación completa** frontend y backend

## Restricciones Importantes
- ❌ NO crear CSS personalizado
- ❌ NO usar Bootstrap sin modern-ui.css
- ❌ NO duplicar estilos existentes
- ✅ USAR exclusivamente clases de modern-ui.css
- ✅ SEGUIR el patrón exacto de events/tasks
- ✅ MANTENER consistencia visual
- ✅ IMPLEMENTAR todas las funcionalidades CRUD

## Ejemplo de Resultado Esperado
Al final, debería poder:
1. Ver dashboard de pagos con estadísticas
2. Crear nuevos pagos con formulario dinámico
3. Ver detalles completos de cada pago
4. Editar pagos existentes
5. Eliminar pagos con confirmación
6. Navegar fluidamente entre secciones
7. Ver efectos visuales consistentes con el resto de la app

**¿Puedes implementar toda esta sección de pagos siguiendo exactamente estos requerimientos y usando exclusivamente el framework modern-ui.css existente?**