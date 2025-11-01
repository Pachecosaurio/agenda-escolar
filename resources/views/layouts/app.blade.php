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

    <!-- FullCalendar CSS - Múltiples CDNs para confiabilidad -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css' rel='stylesheet' />
    <link href='https://unpkg.com/fullcalendar@5.11.5/main.min.css' rel='stylesheet' />

    <!-- CSS crítico para páginas específicas -->
    <style>
    /* CSS CRÍTICO PARA PAYMENTS - Los estilos se manejan con inline styles */
    .payments-page .white-text {
        color: #ffffff !important;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.8) !important;
    }
    .payments-page .glass-card {
        background: rgba(255, 255, 255, 0.15) !important;
        backdrop-filter: blur(20px) !important;
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
        border-radius: 20px !important;
    }
    </style>

    <!-- Estilos personalizados de agenda escolar -->
    <style>
        /* Modo claro: estilos clásicos (sin cambios) */
        body {
            background: linear-gradient(135deg, #e3f0ff 0%, #fffbe6 100%);
            min-height: 100vh;
        }
        
        /* Override para páginas específicas */
        body.payments-body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            min-height: 100vh !important;
        }
        /* CSS Variables - Consistente con calendario/eventos */
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --warning-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --glass-bg: rgba(255, 255, 255, 0.25);
            --glass-border: rgba(255, 255, 255, 0.18);
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.2);
            --shadow-xl: 0 20px 40px rgba(0, 0, 0, 0.3);
            --transition-smooth: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            --border-radius: 20px;
        }
        
        /* Navbar con efectos glass como calendario */
        .navbar {
            background: var(--glass-bg) !important;
            backdrop-filter: blur(20px);
            border: none;
            border-bottom: 1px solid var(--glass-border);
            padding: 1rem 0;
            box-shadow: var(--shadow-lg);
            transition: var(--transition-smooth);
            position: relative;
            z-index: 1030;
        }
        
        .navbar.scrolled {
            padding: 0.75rem 0;
            background: rgba(255, 255, 255, 0.35) !important;
            box-shadow: var(--shadow-xl);
        }
        
        /* Container del navbar */
        .navbar .container {
            padding: 0 1.5rem;
            position: relative;
        }
        
        /* Brand con efectos glass */
        .navbar-brand {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: var(--border-radius);
            padding: 0.75rem 1.5rem;
            color: #1565c0 !important;
            font-weight: 700;
            letter-spacing: 0.5px;
            transition: var(--transition-smooth);
            margin-right: 2rem;
            box-shadow: var(--shadow-sm);
        }
        
        .navbar-brand:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            background: rgba(255, 255, 255, 0.4);
            color: #0d47a1 !important;
        }
        
        /* Nav items con gap */
        .navbar-nav {
            gap: 0.75rem;
        }
        
        /* Nav links con efectos glass como calendario */
        .navbar-nav .nav-link.menu-tab, 
        .navbar-nav .nav-link.dropdown-toggle.menu-tab {
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            border: 1px solid var(--glass-border);
            color: #1565c0 !important;
            border-radius: var(--border-radius);
            margin: 0;
            padding: 0.75rem 1.25rem;
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: var(--shadow-sm);
            transition: var(--transition-smooth);
            position: relative;
            min-width: auto;
            max-width: 160px;
            text-align: center;
            height: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            z-index: 1;
        }
        
        .navbar-nav .nav-link.menu-tab::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--warning-gradient);
            border-radius: var(--border-radius);
            opacity: 0;
            transform: scale(0.95);
            transition: var(--transition-smooth);
            z-index: -1;
        }
        
        .navbar-nav .nav-link.menu-tab.active::before,
        .navbar-nav .nav-link.menu-tab:hover::before {
            opacity: 1;
            transform: scale(1);
        }
        
        .navbar-nav .nav-link.menu-tab.active,
        .navbar-nav .nav-link.menu-tab:focus {
            color: white !important;
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
            border-color: rgba(255, 255, 255, 0.3);
        }
        
        .navbar-nav .nav-link.menu-tab:hover {
            color: white !important;
            text-decoration: none;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        
        /* Dropdown de usuario con glass effect */
        .user-dropdown {
            max-width: 180px;
            position: relative;
        }
        
        .user-dropdown .nav-link {
            padding: 0.75rem 1rem 0.75rem 1.25rem !important;
            display: flex !important;
            align-items: center;
            gap: 0.5rem;
        }
        
        .user-dropdown .user-name {
            max-width: 120px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            display: inline-block;
        }
        
        .user-dropdown .dropdown-toggle::after {
            margin-left: 0.5rem;
            flex-shrink: 0;
        }
        
        /* Dropdowns con superposición y glass effect */
        .dropdown-menu {
            background: var(--glass-bg) !important;
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-xl);
            margin-top: 0.5rem;
            padding: 1rem;
            min-width: 200px;
            position: absolute !important;
            z-index: 9999 !important;
            animation: dropdownSlideIn 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        
        @keyframes dropdownSlideIn {
            from {
                opacity: 0;
                transform: translateY(-15px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        .dropdown-menu .dropdown-header {
            background: var(--primary-gradient);
            color: white;
            border-radius: calc(var(--border-radius) - 5px);
            padding: 0.75rem 1rem;
            font-weight: 600;
            margin: -0.5rem -0.5rem 0.5rem -0.5rem;
            border: none;
        }
        
        .dropdown-menu .dropdown-item {
            padding: 0.75rem 1rem;
            border-radius: calc(var(--border-radius) - 8px);
            margin: 0.25rem 0;
            transition: var(--transition-smooth);
            background: transparent;
            border: none;
        }
        
        .dropdown-menu .dropdown-item:hover {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            transform: translateX(5px);
            box-shadow: var(--shadow-sm);
        }
        
        .dropdown-menu .dropdown-divider {
            border-color: var(--glass-border);
            margin: 0.75rem 0;
        }
        
        /* Dropdown de notificaciones con glass effect */
        .notifications-dropdown {
            max-height: 400px;
            overflow-y: auto;
            background: var(--glass-bg) !important;
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-xl);
            margin-top: 0.5rem;
            padding: 0;
            min-width: 320px;
            max-width: 400px;
            position: absolute !important;
            z-index: 9999 !important;
            animation: dropdownSlideIn 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        
        .notifications-dropdown .dropdown-header {
            background: var(--primary-gradient);
            color: white;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            padding: 1rem 1.5rem;
            font-weight: 600;
            margin: 0;
            border: none;
        }
        
        .notifications-dropdown .dropdown-item {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--glass-border);
            transition: var(--transition-smooth);
            background: transparent;
        }
        
        .notifications-dropdown .dropdown-item:hover {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            transform: translateX(5px);
        }
        
        .notifications-dropdown .dropdown-item.fw-bold {
            background: rgba(102, 126, 234, 0.1);
        }
        
        /* Badge de notificaciones con glass effect */
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--secondary-gradient);
            color: white;
            border-radius: 50%;
            font-size: 0.7rem;
            padding: 3px 7px;
            min-width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: notificationPulse 2s infinite;
            box-shadow: var(--shadow-md);
            border: 2px solid rgba(255, 255, 255, 0.3);
            z-index: 10;
        }
        
        @keyframes notificationPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); box-shadow: var(--shadow-lg); }
            100% { transform: scale(1); }
        }
        
        /* Ensure dropdowns overlay content */
        .dropdown-menu.show {
            position: fixed !important;
            z-index: 9999 !important;
        }
        
        /* Responsive con glass effects */
        @media (max-width: 991.98px) {
            .navbar-nav {
                padding: 1.5rem 0;
                gap: 0.75rem;
                background: var(--glass-bg);
                backdrop-filter: blur(20px);
                border-radius: var(--border-radius);
                margin: 1rem 0;
                box-shadow: var(--shadow-lg);
            }
            
            .navbar-nav .nav-link.menu-tab {
                margin: 0;
                max-width: none;
                justify-content: flex-start;
                border-radius: calc(var(--border-radius) - 5px);
            }
            
            .user-dropdown {
                max-width: none;
            }
            
            .user-dropdown .user-name {
                max-width: none;
            }
        }
        
        /* Navbar toggler con glass effect */
        .navbar-toggler {
            border: 1px solid var(--glass-border);
            padding: 0.5rem;
            border-radius: calc(var(--border-radius) - 5px);
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            transition: var(--transition-smooth);
        }
        
        .navbar-toggler:focus {
            box-shadow: var(--shadow-md);
            background: rgba(255, 255, 255, 0.4);
            border-color: rgba(255, 255, 255, 0.3);
        }
        
        .navbar-toggler-icon {
            transition: transform 0.3s ease;
            filter: invert(0.2) sepia(1) saturate(5) hue-rotate(200deg);
        }
        
        .navbar-toggler[aria-expanded="true"] .navbar-toggler-icon {
            transform: rotate(90deg);
        }
        
        /* CSS adicional para superposición de dropdowns */
        .dropdown-menu.show {
            position: fixed !important;
            z-index: 9999 !important;
            animation: dropdownSlideIn 0.3s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
        }
        
        /* Asegurar que el navbar tenga z-index alto pero menor que dropdowns */
        .navbar {
            z-index: 1030 !important;
        }
        
        /* Overlay para cerrar dropdowns */
        .dropdown-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 9998;
            background: transparent;
        }
        .card {
            border-radius: 18px;
            box-shadow: 0 4px 16px #0001;
            border: none;
        }
        .card-header {
            background: #ffd600;
            color: #1565c0;
            font-weight: bold;
            font-size: 1.2rem;
            border-radius: 18px 18px 0 0;
        }
        .btn-primary {
            background: #1565c0;
            border: none;
        }
        .btn-primary:hover {
            background: #ffd600;
            color: #1565c0;
        }
        .alert-warning {
            background: #fffde7;
            color: #ff6f00;
            border: 1px solid #ffd600;
        }
        .table {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
        }
        .table th {
            background: #e3f0ff;
            color: #1565c0;
        }
        .table td {
            vertical-align: middle;
        }
        .agenda-title {
            font-family: 'Nunito', cursive;
            font-size: 2.2rem;
            color: #1565c0;
            margin-bottom: 0.5em;
            text-shadow: 1px 2px 0 #ffd60044;
        }
        .agenda-icon {
            color: #ffd600;
            font-size: 2.2rem;
            margin-right: 10px;
        }
        
        /* Estilos para notificaciones */
        .notifications-dropdown {
            max-height: 400px;
            overflow-y: auto;
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        }
        
        .notifications-dropdown .dropdown-header {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 10px 20px;
            font-weight: 600;
            margin: 0;
        }
        
        .notifications-dropdown .dropdown-item {
            padding: 12px 20px;
            border-bottom: 1px solid #f1f3f4;
            transition: background-color 0.2s;
        }
        
        .notifications-dropdown .dropdown-item:hover {
            background-color: #f8f9fa;
        }
        
        .notifications-dropdown .dropdown-item.fw-bold {
            background-color: #e3f2fd;
        }
        
        .badge.bg-danger {
            font-size: 0.65rem;
            padding: 0.25em 0.5em;
        }
        
        /* Estilos del logout button */
        .navbar-nav .dropdown-toggle.nav-link {
            border-radius: 2rem !important;
            padding: 0.6rem 1.4rem !important;
            transition: all 0.3s ease !important;
            background: none !important;
            color: #1565c0 !important;
            font-weight: bold !important;
            margin: 0 0.3rem !important;
            min-width: 140px !important;
            text-align: center !important;
        }
        .navbar-nav .dropdown-toggle.nav-link:hover, .navbar-nav .dropdown-toggle.nav-link.show {
            background: #1565c0 !important;
            color: #fff !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 20px rgba(21, 101, 192, 0.3) !important;
        }
        .navbar-nav .dropdown-toggle.nav-link.show::before {
            transform: rotate(180deg) !important;
        }
        .navbar-nav .dropdown-toggle.nav-link::before {
            content: '▼' !important;
            margin-left: 8px !important;
            font-size: 0.8rem !important;
            transition: transform 0.3s ease !important;
        }
        .dropdown-menu {
            border-radius: 15px !important;
            border: none !important;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15) !important;
            margin-top: 8px !important;
        }
        .dropdown-item {
            padding: 12px 20px !important;
            transition: background-color 0.2s ease !important;
        }
        .dropdown-item:hover {
            background-color: #f8f9fa !important;
        }
        .dropdown-divider {
            margin: 0.5rem 0 !important;
        }
        
        /* Home Page Styles */
        .home-background {
            background: linear-gradient(135deg, #e3f0ff 0%, #fffbe6 100%);
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }

        .home-background::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%23667eea" fill-opacity="0.05"><circle cx="30" cy="30" r="2"/></g></svg>') repeat;
            z-index: 1;
        }

        .home-content {
            position: relative;
            z-index: 2;
        }

        /* Hero section con glass effect */
        .hero-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-xl);
            transition: var(--transition-smooth);
            padding: 3rem 2rem;
            text-align: center;
            margin-bottom: 3rem;
        }

        .hero-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
        }

        /* Welcome title con animación */
        .welcome-title {
            font-size: 3.5rem;
            font-weight: 800;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1.5rem;
            text-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
            animation: titleFloat 3s ease-in-out infinite;
        }

        @keyframes titleFloat {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        /* Icon con animación */
        .hero-icon {
            display: inline-block;
            margin-right: 1rem;
            padding: 1rem;
            background: var(--warning-gradient);
            border-radius: 50%;
            animation: iconPulse 2s ease-in-out infinite;
            box-shadow: var(--shadow-lg);
        }

        @keyframes iconPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        /* Cards con glass effect mejorado */
        .feature-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: var(--border-radius);
            padding: 2rem;
            height: 100%;
            transition: var(--transition-smooth);
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
            transform: scaleX(0);
            transition: var(--transition-smooth);
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: var(--shadow-xl);
            border-color: rgba(255, 255, 255, 0.3);
        }

        /* Icon containers con gradientes específicos */
        .card-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: white;
            box-shadow: var(--shadow-md);
            transition: var(--transition-smooth);
        }

        .card-icon.tasks {
            background: linear-gradient(135deg, #ffd600 0%, #ff9500 100%);
        }

        .card-icon.events {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
        }

        .card-icon.calendar {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        }

        .card-icon.payments {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        }

        .card-icon:hover {
            transform: rotate(10deg) scale(1.1);
            box-shadow: var(--shadow-lg);
        }

        /* Buttons con efectos glass */
        .glass-btn {
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            border: 1px solid var(--glass-border);
            border-radius: 25px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition-smooth);
            position: relative;
            overflow: hidden;
            display: inline-block;
            box-shadow: var(--shadow-sm);
            color: #1565c0;
        }

        .glass-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--primary-gradient);
            opacity: 0;
            transition: var(--transition-smooth);
            z-index: -1;
        }

        .glass-btn:hover::before {
            opacity: 1;
        }

        .glass-btn:hover {
            color: white !important;
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
            border-color: rgba(255, 255, 255, 0.3);
            text-decoration: none;
        }

        .glass-btn.primary::before {
            background: var(--primary-gradient);
        }

        .glass-btn.success::before {
            background: var(--success-gradient);
        }

        .glass-btn.warning::before {
            background: var(--warning-gradient);
        }

        .glass-btn.secondary::before {
            background: var(--secondary-gradient);
        }

        /* Stats cards para usuario autenticado */
        .stats-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            text-align: center;
            transition: var(--transition-smooth);
            box-shadow: var(--shadow-md);
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            opacity: 0;
            transition: var(--transition-smooth);
            z-index: -1;
        }

        .stats-card.tasks::before {
            background: linear-gradient(135deg, rgba(255, 214, 0, 0.1) 0%, rgba(255, 149, 0, 0.1) 100%);
        }

        .stats-card.events::before {
            background: linear-gradient(135deg, rgba(155, 89, 182, 0.1) 0%, rgba(142, 68, 173, 0.1) 100%);
        }

        .stats-card.payments::before {
            background: linear-gradient(135deg, rgba(39, 174, 96, 0.1) 0%, rgba(34, 153, 84, 0.1) 100%);
        }

        .stats-card:hover::before {
            opacity: 1;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .stats-number {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        /* Animaciones de entrada */
        .fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
            opacity: 0;
            transform: translateY(30px);
        }

        .fade-in-up:nth-child(1) { animation-delay: 0.1s; }
        .fade-in-up:nth-child(2) { animation-delay: 0.2s; }
        .fade-in-up:nth-child(3) { animation-delay: 0.3s; }
        .fade-in-up:nth-child(4) { animation-delay: 0.4s; }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .welcome-title {
                font-size: 2.5rem;
            }
            
            .hero-card {
                padding: 2rem 1rem;
            }
            
            .feature-card {
                margin-bottom: 2rem;
            }
        }
        
        /* Payments Page Styles */
        .payments-hero-section {
            background: var(--primary-gradient);
            min-height: 100vh;
            position: relative;
            overflow: hidden;
            padding: 0;
        }

        .payments-hero-content {
            position: relative;
            z-index: 10;
            padding: 2rem 0;
        }

        /* Floating Elements for Payments */
        .payments-floating-elements {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        .payments-floating-circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            animation: float 6s ease-in-out infinite;
        }

        .payments-floating-circle:nth-child(1) {
            width: 120px;
            height: 120px;
            top: 15%;
            left: 8%;
            animation-delay: 0s;
        }

        .payments-floating-circle:nth-child(2) {
            width: 200px;
            height: 200px;
            top: 50%;
            right: 10%;
            animation-delay: 2s;
        }

        .payments-floating-circle:nth-child(3) {
            width: 90px;
            height: 90px;
            bottom: 25%;
            left: 25%;
            animation-delay: 4s;
        }

        /* Payment Items */
        .payment-item {
            background: rgba(255, 255, 255, 0.95);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: var(--shadow-md);
            transition: var(--transition-smooth);
            position: relative;
            overflow: hidden;
        }

        .payment-item:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: var(--shadow-xl);
        }

        .payment-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        /* Action Buttons for Payments */
        .action-button {
            position: relative;
            overflow: hidden;
            transition: var(--transition-smooth);
            border: none;
            border-radius: 25px;
            padding: 0.7rem 1.5rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .action-button:hover {
            transform: translateY(-5px) scale(1.05);
        }

        .action-button.hovering {
            animation: pulse 1s infinite;
        }

        .btn-primary-modern {
            background: var(--primary-gradient);
            color: white;
        }

        .btn-success-modern {
            background: var(--success-gradient);
            color: white;
        }

        .btn-warning-modern {
            background: var(--warning-gradient);
            color: white;
        }

        .btn-danger-modern {
            background: var(--secondary-gradient);
            color: white;
        }

        @keyframes pulse {
            0% { transform: translateY(-5px) scale(1.05); }
            50% { transform: translateY(-7px) scale(1.08); }
            100% { transform: translateY(-5px) scale(1.05); }
        }

        /* Amount Display */
        .amount-display {
            font-size: 1.4rem;
            font-weight: bold;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: none;
        }

        /* Text Effects */
        .text-glow {
            text-shadow: 0 0 20px rgba(255, 255, 255, 0.5);
        }

        .text-shadow {
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        /* Filter Card - Igual a eventos */
        .filter-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(30px);
            border: 2px solid rgba(255, 255, 255, 0.4);
            position: relative;
            overflow: hidden;
            border-radius: var(--border-radius);
        }

        .filter-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(45deg, #667eea, #764ba2, #667eea);
            background-size: 200% 100%;
            animation: gradient-shift 3s ease infinite;
        }

        @keyframes gradient-shift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
    <!-- Estilos adicionales de vistas específicas -->
    @stack('styles')
</head>
<body class="@if(request()->is('payments*')) payments-body @endif">
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
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
                                <a class="nav-link menu-tab d-flex align-items-center" href="{{ route('home') }}">
                                    <span class="me-2">
                                        <i class="fa-solid fa-house fa-lg text-primary"></i>
                                    </span>
                                    <span>Inicio</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link menu-tab" href="{{ route('tasks.index') }}"><i class="fa-solid fa-list-check"></i> Tareas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link menu-tab" href="{{ route('events.index') }}"><i class="fa-solid fa-calendar-days"></i> Eventos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link menu-tab" href="{{ route('calendar') }}"><i class="fa-solid fa-calendar"></i> Calendario</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link menu-tab {{ request()->routeIs('payments.index') ? 'active' : '' }}" href="{{ route('payments.index') }}">
                                    <i class="fa-solid fa-money-bill-wave me-1"></i> Pagos
                                </a>
                            </li>
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link menu-tab d-flex align-items-center" href="{{ route('login') }}">
                                        <span class="me-2">
                                            <i class="fa-solid fa-right-to-bracket fa-lg text-primary"></i>
                                        </span>
                                        <span>Iniciar Sesión</span>
                                    </a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link menu-tab d-flex align-items-center" href="{{ route('register') }}">
                                        <span class="me-2">
                                            <i class="fa-solid fa-user-plus fa-lg text-success"></i>
                                        </span>
                                        <span>Registrarse</span>
                                    </a>
                                </li>
                            @endif
                        @else
                            <!-- Notificaciones -->
                            <li class="nav-item dropdown">
                                <a id="notificationDropdown" class="nav-link menu-tab d-flex align-items-center position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="me-2">
                                        <i class="fa-solid fa-bell fa-lg text-warning"></i>
                                    </span>
                                    <span class="visually-hidden">Notificaciones</span>
                                    @php
                                        $userNotifications = Auth::user()->notifications()->latest()->limit(10)->get();
                                        $unreadCount = $userNotifications->where('read_at', null)->count();
                                    @endphp
                                    @if($unreadCount > 0)
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:0.7rem;">
                                            {{ $unreadCount }}
                                            <span class="visually-hidden">nuevas notificaciones</span>
                                        </span>
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
                                        <i class="fa-solid fa-bell me-2"></i>
                                        Ver todas las notificaciones
                                    </a>
                                </div>
                            </li>
                            <!-- Usuario -->
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle menu-tab d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <span class="me-2">
                                        <i class="fa-solid fa-user-circle fa-lg text-primary"></i>
                                    </span>
                                    <span>{{ Auth::user()->name }}</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item text-danger fw-bold" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <span class="me-2">
                                            <i class="fa-solid fa-circle-xmark fa-lg"></i>
                                        </span>
                                        <span>{{ __('Cerrar sesión') }}</span>
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
            <!-- Toast notifications -->
            <div aria-live="polite" aria-atomic="true" class="position-fixed top-0 end-0 p-3" style="z-index: 9999;">
                @if(session('success'))
                    <div class="toast align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="d-flex">
                            <div class="toast-body">
                                <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                    </div>
                @endif
                @if(session('error'))
                    <div class="toast align-items-center text-bg-danger border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="d-flex">
                            <div class="toast-body">
                                <i class="fa-solid fa-circle-xmark me-2"></i> {{ session('error') }}
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                    </div>
                @endif
            </div>
        </main>
    </div>
    
    <!-- Scripts fuera del contenedor Vue.js para evitar conflictos -->
    @stack('scripts')
    
    <footer class="modern-footer">
        <div class="footer-content">
            <div class="footer-wave"></div>
            <div class="footer-main">
                <div class="footer-brand">
                    <div class="brand-icon">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <div class="brand-text">
                        <h6 class="brand-name">Agenda Escolar</h6>
                        <p class="brand-tagline">Tu compañero educativo digital</p>
                    </div>
                </div>
                <div class="footer-copyright">
                    <span class="copyright-text">
                        &copy; {{ date('Y') }} Agenda Escolar
                    </span>
                    <span class="made-with">
                        Hecho con <i class="fa-solid fa-heart heart-pulse"></i> para estudiantes
                    </span>
                </div>
            </div>
        </div>
    </footer>
    
    <style>
        /* Footer Moderno con Glass Effect */
        .modern-footer {
            position: relative;
            background:#fff !important; /* Fondo sólido blanco forzado */
            backdrop-filter: none;
            -webkit-backdrop-filter: none;
            border-top: 1px solid #e2e8f0;
            margin-top: auto;
            overflow: hidden;
            box-shadow: 0 -4px 18px rgba(0,0,0,0.05);
        }

        .footer-wave {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            clip-path: polygon(0 15px, 100% 0, 100% 25px, 0 40px);
            animation: waveMove 6s ease-in-out infinite;
            opacity: .9; /* ligera reducción para integrarse con fondo blanco */
        }

        @keyframes waveMove {
            0%, 100% { clip-path: polygon(0 15px, 100% 0, 100% 25px, 0 40px); }
            50% { clip-path: polygon(0 8px, 100% 10px, 100% 32px, 0 30px); }
        }

        .footer-content {
            position: relative;
            z-index: 2;
            padding: 40px 0 20px;
        }

        .footer-main {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 30px;
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .brand-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            animation: brandPulse 3s ease-in-out infinite;
        }

        @keyframes brandPulse {
            0%, 100% { transform: scale(1); box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3); }
            50% { transform: scale(1.05); box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4); }
        }

        .brand-text {
            flex: 1;
        }

        .brand-name {
            font-size: 18px;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0;
            animation: textShimmer 4s ease-in-out infinite;
        }

        @keyframes textShimmer {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .brand-tagline {
            color: #666;
            font-size: 12px;
            margin: 2px 0 0;
            opacity: 0.8;
        }

        .footer-copyright {
            text-align: right;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .copyright-text {
            color: #666;
            font-size: 14px;
            font-weight: 500;
        }

        .made-with {
            color: #888;
            font-size: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .heart-pulse {
            color: #ff6b6b;
            animation: heartbeat 1.5s ease-in-out infinite;
        }

        @keyframes heartbeat {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .footer-main {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .footer-brand {
                justify-content: center;
            }

            .footer-copyright {
                text-align: center;
            }

            .brand-name {
                font-size: 16px;
            }

            .brand-tagline {
                font-size: 11px;
            }

            .brand-icon {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }

            .footer-content {
                padding: 35px 0 15px;
            }
        }

        /* Layout Flexbox */
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        #app {
            flex: 1 0 auto;
        }
        .modern-footer {
            flex-shrink: 0;
        }
    </style>
    
    <!-- Script unificado de inicialización -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('🔧 Inicializando aplicación...');
        
        try {
            // Verificar que Bootstrap esté disponible
            if (typeof window.bootstrap === 'undefined') {
                console.error('❌ Bootstrap no está disponible');
                return;
            }
            
            console.log('✅ Bootstrap detectado');
            
            // Inicializar dropdowns
            const dropdownElements = document.querySelectorAll('.dropdown-toggle');
            dropdownElements.forEach(dropdown => {
                new bootstrap.Dropdown(dropdown);
            });
            
            // Inicializar navbar collapse
            const navbarToggler = document.querySelector('.navbar-toggler');
            const navbarCollapse = document.querySelector('.navbar-collapse');
            
            if (navbarToggler && navbarCollapse) {
                const collapseInstance = new bootstrap.Collapse(navbarCollapse, {
                    toggle: false
                });
                
                navbarToggler.addEventListener('click', function(e) {
                    e.preventDefault();
                    collapseInstance.toggle();
                });
            }
            
            // Efecto de scroll en navbar
            const navbar = document.querySelector('.navbar');
            if (navbar) {
                window.addEventListener('scroll', function() {
                    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                    
                    if (scrollTop > 50) {
                        navbar.classList.add('scrolled');
                    } else {
                        navbar.classList.remove('scrolled');
                    }
                });
            }
            
            // Animaciones de hover para nav links
            const navLinks = document.querySelectorAll('.nav-link.menu-tab');
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
            
            // Funcionalidad especial para notificaciones
            const notifIcon = document.getElementById('notificationDropdown');
            if (notifIcon) {
                console.log('✅ Icono de notificaciones encontrado');
                notifIcon.addEventListener('click', function(e) {
                    e.preventDefault();
                    const notifMenu = document.querySelector('.notifications-dropdown');
                    if (notifMenu) {
                        const isVisible = notifMenu.classList.contains('show');
                        
                        // Cerrar otros dropdowns
                        document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                            if (menu !== notifMenu) {
                                menu.classList.remove('show');
                            }
                        });
                        
                        // Toggle del menú de notificaciones
                        notifMenu.classList.toggle('show', !isVisible);
                        
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
                    }
                });
            } else {
                console.log('ℹ️ Icono de notificaciones no encontrado (normal si no está autenticado)');
            }
            
            console.log('🎉 Aplicación inicializada correctamente');
            
        } catch (error) {
            console.error('❌ Error inicializando aplicación:', error);
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

    <!-- FullCalendar JS - Múltiples CDNs con detección de fallos -->
    <script>
        // Cargar FullCalendar con fallback y callback (prevenir carga duplicada)
        function loadFullCalendar() {
            // Verificar si FullCalendar ya está cargado
            if (window.FullCalendar) {
                console.log('✅ FullCalendar ya está cargado');
                initializeCalendarWhenReady();
                return;
            }

            // Verificar si ya se está cargando
            if (window.fullCalendarLoading) {
                console.log('⏳ FullCalendar ya se está cargando...');
                return;
            }

            window.fullCalendarLoading = true;
            
            // Intentar JSDelivr primero
            const script1 = document.createElement('script');
            script1.src = 'https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js';
            script1.onload = function() {
                console.log('✅ FullCalendar cargado desde JSDelivr');
                window.fullCalendarLoading = false;
                loadLocale();
            };
            script1.onerror = function() {
                console.warn('⚠️ JSDelivr falló, intentando UNPKG...');
                // Fallback a UNPKG
                const script2 = document.createElement('script');
                script2.src = 'https://unpkg.com/fullcalendar@5.11.5/main.min.js';
                script2.onload = function() {
                    console.log('✅ FullCalendar cargado desde UNPKG');
                    window.fullCalendarLoading = false;
                    loadLocale();
                };
                script2.onerror = function() {
                    console.error('❌ Error: No se pudo cargar FullCalendar desde ningún CDN');
                    window.fullCalendarLoading = false;
                };
                document.head.appendChild(script2);
            };
            document.head.appendChild(script1);
        }
        
        function loadLocale() {
            // Verificar si el locale ya está cargado
            if (window.FullCalendar && window.FullCalendar.globalLocales && window.FullCalendar.globalLocales.es) {
                console.log('✅ Locale español ya está cargado');
                initializeCalendarWhenReady();
                return;
            }

            const localeScript = document.createElement('script');
            localeScript.src = 'https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/locales/es.js';
            localeScript.onload = function() {
                console.log('✅ Locale español cargado');
                // AQUÍ es cuando FullCalendar está completamente listo
                initializeCalendarWhenReady();
            };
            localeScript.onerror = function() {
                // Fallback para locale
                const localeScript2 = document.createElement('script');
                localeScript2.src = 'https://unpkg.com/fullcalendar@5.11.5/locales/es.js';
                localeScript2.onload = function() {
                    console.log('✅ Locale español cargado desde UNPKG');
                    initializeCalendarWhenReady();
                };
                localeScript2.onerror = function() {
                    console.warn('⚠️ No se pudo cargar locale, usando inglés');
                    initializeCalendarWhenReady();
                };
                document.head.appendChild(localeScript2);
            };
            document.head.appendChild(localeScript);
        }
        
        function initializeCalendarWhenReady() {
            // Dispatch custom event para notificar que FullCalendar está listo
            const event = new CustomEvent('fullcalendarReady');
            document.dispatchEvent(event);
            console.log('🎉 FullCalendar completamente inicializado');
        }
        
        loadFullCalendar();
    </script>
</body>
</html>
