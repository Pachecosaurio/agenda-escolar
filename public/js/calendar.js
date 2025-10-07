// Scripts específicos del calendario - FullCalendar v5
let calendar;

// Esperar a que FullCalendar esté completamente cargado
document.addEventListener('fullcalendarReady', function() {
    console.log('🎯 Evento fullcalendarReady recibido, inicializando calendario...');
    initCalendar();
});

// Fallback: si el evento no se dispara, intentar después de DOM loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('📄 DOM cargado, verificando FullCalendar...');
    
    // Esperar un poco y verificar si FullCalendar está disponible
    setTimeout(function() {
        if (typeof FullCalendar !== 'undefined') {
            console.log('✅ FullCalendar detectado después de DOM loaded');
            initCalendar();
        } else {
            console.log('⏳ FullCalendar no disponible, esperando...');
            // Intentar cada 500ms hasta que esté disponible
            const checkInterval = setInterval(function() {
                if (typeof FullCalendar !== 'undefined') {
                    console.log('✅ FullCalendar finalmente disponible');
                    clearInterval(checkInterval);
                    initCalendar();
                }
            }, 500);
            
            // Timeout después de 10 segundos
            setTimeout(function() {
                clearInterval(checkInterval);
                if (typeof FullCalendar === 'undefined') {
                    console.error('❌ Timeout: FullCalendar no se cargó en 10 segundos');
                }
            }, 10000);
        }
    }, 100);
});

function initCalendar() {
    // Evitar inicialización múltiple
    if (calendar) {
        console.log('⚠️ Calendario ya inicializado');
        return;
    }

    // Verificar que FullCalendar esté disponible
    if (typeof FullCalendar === 'undefined') {
        console.error('❌ FullCalendar no está cargado');
        return;
    }

    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) {
        console.error('❌ Elemento calendario no encontrado');
        return;
    }
    
    console.log('🚀 Inicializando FullCalendar...');
    
    // FullCalendar v5 API
    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        height: 'auto',
        eventSources: [
            {
                url: window.routes ? window.routes.calendarEvents : '/calendar/events',
                method: 'GET',
                color: '#4facfe', // Azul para eventos
                textColor: '#ffffff',
                failure: function() {
                    console.error('Error loading events');
                }
            },
            {
                url: window.routes ? window.routes.paymentsCalendarEvents : '/payments-calendar-events',
                method: 'GET',
                color: '#f093fb', // Rosa para pagos
                textColor: '#ffffff',
                failure: function() {
                    console.error('Error loading tuition payments');
                }
            }
        ],
        eventClick: function(info) {
            showEventDetails(info.event);
        },
        eventDidMount: function(info) {
            // Añadir tooltip
            info.el.setAttribute('title', info.event.title);
            
            // Asignar tipo de evento para CSS específico
            const eventType = info.event.extendedProps?.type || 'event';
            info.el.setAttribute('data-type', eventType);
            
            // Asegurar que el texto sea visible
            const titleEl = info.el.querySelector('.fc-event-title');
            if (titleEl) {
                titleEl.style.color = '#ffffff';
                titleEl.style.fontWeight = '600';
                titleEl.style.textShadow = '0 1px 2px rgba(0, 0, 0, 0.5)';
            }
            
            // Asegurar que el tiempo sea visible
            const timeEl = info.el.querySelector('.fc-event-time');
            if (timeEl) {
                timeEl.style.color = 'rgba(255, 255, 255, 0.9)';
                timeEl.style.fontWeight = '500';
            }
        },
        datesSet: function() {
            updateStats();
        }
    });
    
    calendar.render();
    console.log('✅ Calendario renderizado correctamente');
    
    // Event listeners para filtros
    const showEventsBtn = document.getElementById('showEvents');
    const showTasksBtn = document.getElementById('showTasks');
    const showPaymentsBtn = document.getElementById('showPayments');
    
    if (showEventsBtn) showEventsBtn.addEventListener('change', toggleEventSource);
    if (showTasksBtn) showTasksBtn.addEventListener('change', toggleEventSource);
    if (showPaymentsBtn) showPaymentsBtn.addEventListener('change', toggleEventSource);
    
    // Animaciones de entrada para botones
    animateButtons();
    
    console.log('🎉 Calendario completamente inicializado');
}

function changeCalendarView(view) {
    if (!calendar) return;
    
    calendar.changeView(view);
    
    // Actualizar botones activos
    document.querySelectorAll('[data-view]').forEach(btn => {
        btn.classList.remove('btn-primary');
        btn.classList.add('btn-outline-primary');
    });
    
    const activeBtn = document.querySelector(`[data-view="${view}"]`);
    if (activeBtn) {
        activeBtn.classList.remove('btn-outline-primary');
        activeBtn.classList.add('btn-primary');
    }
}

function toggleEventSource() {
    if (!calendar) return;
    
    const showEvents = document.getElementById('showEvents')?.checked ?? true;
    const showTasks = document.getElementById('showTasks')?.checked ?? true;
    const showPayments = document.getElementById('showPayments')?.checked ?? true;
    
    // Remover todas las fuentes de eventos
    calendar.removeAllEventSources();
    
    // Agregar fuentes según los filtros con colores específicos
    if (showEvents) {
        calendar.addEventSource({
            url: window.routes ? window.routes.calendarEvents : '/calendar/events',
            method: 'GET',
            color: '#4facfe',
            textColor: '#ffffff',
            borderColor: '#4facfe'
        });
    }
    
    if (showPayments) {
        calendar.addEventSource({
            url: window.routes ? window.routes.paymentsCalendarEvents : '/payments-calendar-events',
            method: 'GET',
            color: '#f093fb',
            textColor: '#ffffff',
            borderColor: '#f093fb'
        });
    }
    
    // Si hay tasks (para futuro)
    if (showTasks) {
        // Agregar fuente de tasks cuando esté disponible
        /*
        calendar.addEventSource({
            url: '/tasks-calendar-events',
            method: 'GET',
            color: '#43e97b',
            textColor: '#ffffff',
            borderColor: '#43e97b'
        });
        */
    }
}

function showEventDetails(event) {
    const modal = document.getElementById('eventModal');
    if (!modal) return;
    
    document.getElementById('eventTitle').textContent = event.title || 'Sin título';
    document.getElementById('eventDescription').textContent = event.extendedProps?.description || 'Sin descripción';
    document.getElementById('eventDate').textContent = event.start ? event.start.toLocaleDateString('es-ES') : 'Sin fecha';
    
    // Mostrar modal con Bootstrap
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
}

function updateStats() {
    // Actualizar estadísticas del calendario
    console.log('Actualizando estadísticas del calendario');
}

function animateButtons() {
    // Animaciones para botones
    const buttons = document.querySelectorAll('.animate-btn');
    buttons.forEach((btn, index) => {
        setTimeout(() => {
            btn.style.opacity = '1';
            btn.style.transform = 'translateY(0)';
        }, index * 100);
    });
}

// Exportar funciones globalmente para uso en el HTML
window.changeCalendarView = changeCalendarView;
window.toggleEventSource = toggleEventSource;
window.showEventDetails = showEventDetails;