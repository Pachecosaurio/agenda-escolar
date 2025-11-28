{{-- Layout principal de la aplicación: navbar, estilos globales, notificaciones y carga de FullCalendar con fallback. --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- DNS Prefetch -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
    
    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- FullCalendar ahora cargado vía Vite (imports en resources/js/calendar.js) -->

    <!-- Estilos layout y footer migrados a parciales Sass: resources/sass/layout/_layout.scss y _footer.scss -->
    </style>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
    <!-- Estilos adicionales de vistas específicas -->
    @stack('styles')
</head>
<body class="@if(request()->is('payments*')) payments-body @endif @if(request()->routeIs('tasks.*')) tasks-body @endif">
    <div id="app">
        <div id="vue-root"></div>
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                    <i class="fa-solid fa-calendar-check me-2 text-primary"></i>
                    <span class="fw-bold">Agenda Escolar</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link menu-tab" href="{{ route('tasks.index') }}"><i class="fa-solid fa-list-check me-1"></i> Tareas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link menu-tab" href="{{ route('events.index') }}"><i class="fa-solid fa-calendar-days me-1"></i> Eventos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link menu-tab" href="{{ route('calendar') }}"><i class="fa-solid fa-calendar me-1"></i> Calendario</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link menu-tab {{ request()->routeIs('payments.*') ? 'active' : '' }}" href="{{ route('payments.index') }}"><i class="fa-solid fa-money-bill-wave me-1"></i> Pagos</a>
                            </li>
                        @endauth
                    </ul>
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link menu-tab" href="{{ route('login') }}"><i class="fa-solid fa-right-to-bracket me-1"></i> Iniciar Sesión</a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link menu-tab" href="{{ route('register') }}"><i class="fa-solid fa-user-plus me-1"></i> Registrarse</a>
                                </li>
                            @endif
                        @else
                            <!-- Notificaciones -->
                            <li class="nav-item dropdown">
                                <a id="notificationDropdown" class="nav-link menu-tab position-relative dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa-solid fa-bell text-warning"></i>
                                    @php
                                        $userNotifications = Auth::user()->notifications()->latest()->limit(10)->get();
                                        $unreadCount = $userNotifications->where('read_at', null)->count();
                                    @endphp
                                    @if($unreadCount > 0)
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:0.6rem;">{{ $unreadCount }}</span>
                                    @endif
                                </a>
                                <div class="dropdown-menu dropdown-menu-end notifications-dropdown" aria-labelledby="notificationDropdown" style="min-width:320px; max-width:400px;">
                                    <h6 class="dropdown-header">Notificaciones</h6>
                                    @forelse($userNotifications as $notification)
                                        <div class="dropdown-item d-flex align-items-start {{ $notification->read_at ? '' : 'fw-bold bg-light' }}" style="white-space:normal;">
                                            <i class="fa-solid fa-circle-info me-2 text-{{ $notification->read_at ? 'secondary' : 'primary' }}"></i>
                                            <div style="flex:1;">
                                                {{ $notification->data['message'] ?? $notification->data['title'] ?? 'Notificación' }}
                                                <div class="small text-muted mt-1">{{ $notification->created_at->diffForHumans() }}</div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="dropdown-item text-muted text-center">No tienes notificaciones</div>
                                    @endforelse
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-center" href="{{ route('notifications.index') }}">
                                        <i class="fa-solid fa-bell me-1"></i> Ver todas
                                    </a>
                                </div>
                            </li>
                            <!-- Usuario -->
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle menu-tab" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa-solid fa-user-circle me-1 text-primary"></i> {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item text-danger fw-bold" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fa-solid fa-circle-xmark me-1"></i> {{ __('Cerrar sesión') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <footer class="modern-footer">
        <div class="footer-inner container py-4 d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
            <div class="footer-brand d-flex align-items-center gap-3">
                <div class="brand-icon d-flex align-items-center justify-content-center">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <div class="brand-text">
                    <h6 class="brand-name mb-0">Agenda Escolar</h6>
                    <p class="brand-tagline mb-0">Tu compañero educativo digital</p>
                </div>
            </div>
            <div class="footer-copy text-md-end text-center">
                <span class="d-block">&copy; {{ date('Y') }} Agenda Escolar</span>
                <span class="made-with">Hecho con <i class="fa-solid fa-heart heart-pulse"></i> para estudiantes</span>
            </div>
        </div>
    </footer>
    @stack('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const notifIcon = document.getElementById('notificationDropdown');
        if (notifIcon) {
            notifIcon.addEventListener('click', e => {
                e.preventDefault();
                const menu = document.querySelector('.notifications-dropdown');
                if (!menu) return;
                const isVisible = menu.classList.contains('show');
                document.querySelectorAll('.dropdown-menu.show').forEach(m => { if (m !== menu) m.classList.remove('show'); });
                menu.classList.toggle('show', !isVisible);
                if (!isVisible) {
                    const closeHandler = ev => { if (!menu.contains(ev.target) && ev.target !== notifIcon) { menu.classList.remove('show'); document.removeEventListener('click', closeHandler); } };
                    setTimeout(() => document.addEventListener('click', closeHandler), 10);
                }
            });
        }
    });
    </script>
</body>
</html>
    
    <!-- Script de inicialización del navbar -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('🔧 Inicializando Bootstrap navbar...');
        
        try {
            // Verificar que Bootstrap esté disponible
            if (typeof window.bootstrap === 'undefined') {
                console.error('❌ Bootstrap no está disponible');
                return;
            }
            
            console.log('✅ Bootstrap detectado:', typeof window.bootstrap);
            
            // Inicializar navbar collapse con configuración explícita
            const navbarToggler = document.querySelector('.navbar-toggler');
            const navbarCollapse = document.querySelector('.navbar-collapse');
            
            if (navbarToggler && navbarCollapse) {
                console.log('🎯 Elementos navbar encontrados');
                
                // Inicializar Collapse con configuración personalizada
                const collapseInstance = new bootstrap.Collapse(navbarCollapse, {
                    toggle: false,
                    parent: false
                });
                
                console.log('✅ Collapse inicializado:', collapseInstance);
                
                // Event listeners para dropdown
                const dropdownElements = document.querySelectorAll('.navbar .dropdown-toggle');
                dropdownElements.forEach(dropdown => {
                    const dropdownInstance = new bootstrap.Dropdown(dropdown);
                    console.log('✅ Dropdown inicializado para:', dropdown.textContent.trim());
                });
                
                // Event listener para toggler
                navbarToggler.addEventListener('click', function(e) {
                    console.log('🔄 Toggle navbar clicked');
                    e.preventDefault();
                    collapseInstance.toggle();
                });
                
                // Manejo de responsive
                window.addEventListener('resize', function() {
                    if (window.innerWidth >= 992) { // Bootstrap lg breakpoint
                        if (navbarCollapse.classList.contains('show')) {
                            collapseInstance.hide();
                        }
                    }
                });
                
                console.log('🎉 Navbar completamente inicializado');
            } else {
                console.warn('⚠️ Elementos navbar no encontrados');
            }
            
        } catch (error) {
            console.error('❌ Error inicializando navbar:', error);
            
            // Fallback básico
            const navbarToggler = document.querySelector('.navbar-toggler');
            const navbarCollapse = document.querySelector('.navbar-collapse');
            
            if (navbarToggler && navbarCollapse) {
                navbarToggler.addEventListener('click', function() {
                    navbarCollapse.classList.toggle('show');
                });
            }
        }
        
        // � Animaciones mejoradas del navbar
        const navbar = document.querySelector('.navbar');
        const navLinks = document.querySelectorAll('.nav-link.menu-tab');
        
        // Efecto de scroll en navbar
        let lastScrollTop = 0;
        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            if (scrollTop > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
            
            lastScrollTop = scrollTop;
        });
        
        // Animaciones de hover mejoradas para nav links
        navLinks.forEach(link => {
            link.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });
            
            link.addEventListener('mouseleave', function() {
                if (!this.classList.contains('active')) {
                    this.style.transform = 'translateY(0)';
                }
            });
        });
        
        // Manejo del dropdown de usuario con nombre largo
        const userDropdown = document.querySelector('.user-dropdown .nav-link');
        if (userDropdown) {
            const userName = userDropdown.querySelector('.user-name');
            if (userName && userName.scrollWidth > userName.clientWidth) {
                // Agregar tooltip para nombres largos
                userName.setAttribute('data-bs-toggle', 'tooltip');
                userName.setAttribute('data-bs-placement', 'bottom');
                
                // Inicializar tooltip si Bootstrap está disponible
                if (typeof bootstrap !== 'undefined') {
                    new bootstrap.Tooltip(userName);
                }
            }
        }
        
        // Animación suave para el toggle del navbar en móviles
        const navbarToggler = document.querySelector('.navbar-toggler');
        
        if (navbarToggler) {
            navbarToggler.addEventListener('click', function() {
                // Animación del icono del toggler
                const icon = this.querySelector('.navbar-toggler-icon');
                if (icon) {
                    setTimeout(() => {
                        icon.style.transform = this.getAttribute('aria-expanded') === 'true' 
                            ? 'rotate(90deg)' 
                            : 'rotate(0deg)';
                    }, 10);
                }
            });
        }
        
        // �🔔 Script para notificaciones
        console.log('🔔 Inicializando notificaciones...');
        const notifIcon = document.getElementById('notificationDropdown');
        if (notifIcon) {
            console.log('✅ Icono de notificaciones encontrado');
            notifIcon.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('🔔 Click en notificaciones');
                const notifMenu = document.querySelector('.notifications-dropdown');
                if (notifMenu) {
                    // Toggle del menú
                    const isVisible = notifMenu.classList.contains('show');
                    
                    // Cerrar otros dropdowns
                    document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                        if (menu !== notifMenu) {
                            menu.classList.remove('show');
                        }
                    });
                    
                    // Toggle del menú de notificaciones
                    notifMenu.classList.toggle('show', !isVisible);
                    console.log('🔔 Menú notificaciones:', !isVisible ? 'ABIERTO' : 'CERRADO');
                    
                    // Manejar click fuera del menú
                    if (!isVisible) {
                        const handleOutsideClick = function(ev) {
                            if (!notifMenu.contains(ev.target) && ev.target !== notifIcon && !notifIcon.contains(ev.target)) {
                                notifMenu.classList.remove('show');
                                document.removeEventListener('click', handleOutsideClick);
                            }
                        };
                        setTimeout(() => document.addEventListener('click', handleOutsideClick), 10);
                    }
                } else {
                    console.error('❌ No se encontró el menú de notificaciones');
                }
            });
        } else {
            console.error('❌ No se encontró el icono de notificaciones');
        }
    });
    </script>

    <!-- Loader CDN de FullCalendar removido: ahora se usa versión empaquetada con Vite -->
</body>
</html>
