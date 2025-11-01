// Scripts específicos del calendario - FullCalendar v5
let calendar;
const LS_KEY = 'calendarPreferences_v1';
let calendarProgressTimer = null;
let calendarProgressValue = 0;

// Debug helpers (toggleable)
function isCalendarDebug(){
    try { return window.CALENDAR_DEBUG === true || localStorage.getItem('calendarDebug') === '1'; } catch(e){ return false; }
}
function logCalendarDebug(){
    if(!isCalendarDebug()) return;
    const args = Array.from(arguments);
    console.log.apply(console, ['[CalendarDBG]'].concat(args));
}
window.toggleCalendarDebug = function(val){
    try {
        if(typeof val === 'boolean'){
            window.CALENDAR_DEBUG = val;
            localStorage.setItem('calendarDebug', val ? '1' : '0');
        } else {
            const newVal = !isCalendarDebug();
            window.CALENDAR_DEBUG = newVal;
            localStorage.setItem('calendarDebug', newVal ? '1' : '0');
        }
        console.info('Calendar debug', isCalendarDebug() ? 'ON' : 'OFF');
    } catch(e) {
        window.CALENDAR_DEBUG = !!val;
        console.info('Calendar debug (no LS)', isCalendarDebug() ? 'ON' : 'OFF');
    }
};

function loadPreferences(){
    try { return JSON.parse(localStorage.getItem(LS_KEY)) || {}; } catch(e){ return {}; }
}
function savePreferences(p){ localStorage.setItem(LS_KEY, JSON.stringify(p)); }

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
        loading: function(isLoading){
            if(isLoading){
                startCalendarProgress();
                setCalendarLoading(true, 'Cargando eventos...');
            }
        },
        eventsSet: function(){
            // Debug breakdown before completing
            try {
                const evs = calendar.getEvents();
                let cEv=0,cTask=0,cPay=0;
                evs.forEach(e=>{
                    const t = e.extendedProps?.type || 'event';
                    if(t==='event') cEv++; else if(t==='task') cTask++; else if(t==='payment') cPay++;
                });
                logCalendarDebug('eventsSet loaded', evs.length, { events:cEv, tasks:cTask, payments:cPay });
            } catch(e){ /* noop */ }
            // Finalizar progreso
            completeCalendarProgress(()=>{
                toggleEventSource(); // reaplica filtros y stats
                setCalendarLoading(false, '');
            });
        },
        eventClick: function(info) {
            // Navegar a la vista detallada si hay URL
            const url = info.event.extendedProps?.url;
            if(url){
                info.jsEvent?.preventDefault();
                window.location.href = url;
                return;
            }
            // Fallback: modal interno
            showEventDetails(info.event);
        },
        eventDidMount: function(info) {
            // Tooltip nativo combinando título + descripción breve
            const desc = info.event.extendedProps?.description || info.event.extendedProps?.status || '';
            const shortDesc = desc ? (desc.length > 110 ? desc.substring(0,107)+'…' : desc) : '';
            const tooltip = shortDesc ? `${info.event.title} — ${shortDesc}` : info.event.title;
            info.el.setAttribute('title', tooltip);
            
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
            // Aplicar visibilidad según filtros actuales (sin usar flag persistente)
            const showEvents = document.getElementById('showEvents')?.checked ?? true;
            const showTasks = document.getElementById('showTasks')?.checked ?? true;
            const showPayments = document.getElementById('showPayments')?.checked ?? true;
            const type = info.event.extendedProps?.type || 'event';
            const visible = (type==='event' && showEvents) || (type==='task' && showTasks) || (type==='payment' && showPayments) || (type!=='task' && type!=='payment' && showEvents);
            if(!visible){ info.el.classList.add('fc-event-hidden'); }
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

// Loader helpers
function setCalendarLoading(state, detail){
    const overlay = document.getElementById('calendarLoadingOverlay');
    const detailEl = document.getElementById('calendarLoadingDetail');
    if(!overlay) return;
    if(state){
        if(detailEl && detail) detailEl.textContent = detail;
        overlay.classList.remove('hidden');
    } else {
        if(detailEl && detail) detailEl.textContent = detail;
        // pequeño retraso para evitar parpadeo si hay recargas rápidas
        setTimeout(()=>overlay.classList.add('hidden'), 120);
    }
}

function updateProgressBar(val, phase){
    const bar = document.getElementById('calendarProgressBar');
    const valEl = document.getElementById('calendarProgressValue');
    const phaseEl = document.getElementById('calendarProgressPhase');
    calendarProgressValue = val;
    if(bar){ bar.style.width = val + '%'; }
    if(valEl){ valEl.textContent = val + '%'; }
    if(phaseEl && phase){ phaseEl.textContent = phase; }
}

function startCalendarProgress(){
    stopCalendarProgress();
    updateProgressBar(0,'Inicializando');
    const stages = [15,35,55,75,90];
    const labels = ['Conectando','Obteniendo datos','Procesando','Renderizando','Ajustando'];
    let i=0;
    calendarProgressTimer = setInterval(()=>{
        if(i < stages.length){
            updateProgressBar(stages[i], labels[i]);
            i++;
        } else {
            // Mantenerse en 90% hasta completion
            clearInterval(calendarProgressTimer);
            calendarProgressTimer = null;
        }
    }, 500);
}

function completeCalendarProgress(cb){
    // Llevar a 100% y luego cerrar
    updateProgressBar(100,'Completado');
    setTimeout(()=>{ if(typeof cb==='function') cb(); }, 260);
    stopCalendarProgress();
}

function stopCalendarProgress(){
    if(calendarProgressTimer){ clearInterval(calendarProgressTimer); calendarProgressTimer = null; }
}

function setCalendarError(msg){
    const overlay = document.getElementById('calendarLoadingOverlay');
    const detailEl = document.getElementById('calendarLoadingDetail');
    if(overlay){ overlay.classList.add('error'); }
    if(detailEl){ detailEl.textContent = msg || 'Error al cargar'; }
    stopCalendarProgress();
    updateProgressBar(100,'Error');
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

    saveCurrentPrefs();
}

function toggleEventSource() {
    if (!calendar) return;
    const prefs = loadPreferences();
    // Leer estados actuales de filtros
    const showEvents = document.getElementById('showEvents')?.checked ?? true;
    const showTasks = document.getElementById('showTasks')?.checked ?? true;
    const showPayments = document.getElementById('showPayments')?.checked ?? true;

    // Guardar preferencias de filtros
    prefs.filters = { showEvents, showTasks, showPayments };
    savePreferences(prefs);

    const events = calendar.getEvents();
    calendar.batchRendering(()=>{
        events.forEach(ev => {
            const type = ev.extendedProps?.type || 'event';
            const visible = (type==='event' && showEvents) || (type==='task' && showTasks) || (type==='payment' && showPayments) || (type!=='task' && type!=='payment' && showEvents);
            if(ev.el){
                if(!visible){ ev.el.classList.add('fc-event-hidden'); }
                else { ev.el.classList.remove('fc-event-hidden'); }
            }
        });
    });
    updateStats();
    logCalendarDebug('filters applied', { showEvents, showTasks, showPayments });
}

function saveCurrentPrefs(){
    if(!calendar) return;
    const prefs = loadPreferences();
    prefs.view = calendar.view?.type;
    prefs.date = calendar.getDate()?.toISOString();
    savePreferences(prefs);
}

function restorePreferences(){
    const prefs = loadPreferences();
    if(Object.keys(prefs).length===0) return;
    // Restaurar filtros
    if(prefs.filters){
        const { showEvents=true, showTasks=true, showPayments=true } = prefs.filters;
        const se = document.getElementById('showEvents'); if(se) se.checked = showEvents;
        const st = document.getElementById('showTasks'); if(st) st.checked = showTasks;
        const sp = document.getElementById('showPayments'); if(sp) sp.checked = showPayments;
    }
    // Vista y fecha (se aplican tras render)
    setTimeout(()=>{
        if(calendar){
            if(prefs.view){ calendar.changeView(prefs.view); }
            if(prefs.date){
                try { calendar.gotoDate(new Date(prefs.date)); } catch(e){}
            }
            updateStats();
        }
    }, 350);
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
    if(!calendar){ return; }
    const fShowEvents = document.getElementById('showEvents')?.checked ?? true;
    const fShowTasks = document.getElementById('showTasks')?.checked ?? true;
    const fShowPayments = document.getElementById('showPayments')?.checked ?? true;
    const events = calendar.getEvents();
    let totalEvents = 0, totalTasks = 0, totalPayments = 0, overdue = 0;
    const today = new Date();
    events.forEach(ev => {
        const type = ev.extendedProps?.type || 'event';
        const visible = (type==='event' && fShowEvents) || (type==='task' && fShowTasks) || (type==='payment' && fShowPayments) || (type!=='task' && type!=='payment' && fShowEvents);
        if(!visible) return;
        if(type === 'event') totalEvents++;
        else if(type === 'task') totalTasks++;
        else if(type === 'payment') totalPayments++;
        if((type === 'task' || type === 'payment') && ev.start && ev.start < today) {
            const status = ev.extendedProps?.status;
            if(!status || (status !== 'completed' && status !== 'paid')) overdue++;
        }
    });
    const elTotalEvents = document.getElementById('totalEvents');
    const elTotalTasks = document.getElementById('totalTasks');
    const elTotalPayments = document.getElementById('totalPayments');
    const elOverdue = document.getElementById('overdueItems');
    if(elTotalEvents) elTotalEvents.textContent = totalEvents;
    if(elTotalTasks) elTotalTasks.textContent = totalTasks;
    if(elTotalPayments) elTotalPayments.textContent = totalPayments;
    if(elOverdue) elOverdue.textContent = overdue;

    // Nuevos contadores de la tarjeta filtro
    const fcEvents = document.getElementById('filterCountEvents');
    const fcTasks = document.getElementById('filterCountTasks');
    const fcPayments = document.getElementById('filterCountPayments');
    if(fcEvents) fcEvents.textContent = totalEvents;
    if(fcTasks) fcTasks.textContent = totalTasks;
    if(fcPayments) fcPayments.textContent = totalPayments;

    // Resumen de filtros activos
    const showEvents = fShowEvents;
    const showTasks = fShowTasks;
    const showPayments = fShowPayments;
    const active = [];
    if(showEvents) active.push('Eventos');
    if(showTasks) active.push('Tareas');
    if(showPayments) active.push('Pagos');
    const summaryEl = document.getElementById('filterActiveSummary');
    if(summaryEl){
        summaryEl.textContent = active.length ? 'Mostrando: ' + active.join(', ') : 'Sin fuentes visibles';
    }
    const tsEl = document.getElementById('calendarStatsUpdatedAt');
    if(tsEl){
        const now = new Date();
        tsEl.textContent = now.toLocaleTimeString('es-ES',{hour:'2-digit',minute:'2-digit',second:'2-digit'});
    }

    logCalendarDebug('visible counts', { events: totalEvents, tasks: totalTasks, payments: totalPayments, overdue });
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

function refreshCalendar(){
    if(!calendar){ console.warn('refreshCalendar() llamado antes de inicializar el calendario'); return; }
    try {
        setCalendarLoading(true, 'Actualizando...');
        calendar.refetchEvents();
        console.log('🔄 Refetch solicitado');
    } catch (e) {
        console.error('Error al refrescar calendario', e);
        setCalendarError('Error al refrescar');
    }
}

// Recarga forzada que además reaplica filtros actuales sin perder estado
function forceReloadCalendar(){
    if(!calendar){ return; }
    const statusEl = document.getElementById('forceReloadStatus');
    if(statusEl){ statusEl.textContent = 'Recargando...'; }
    const start = performance.now();
    try {
        setCalendarLoading(true, 'Recargando datos...');
        calendar.refetchEvents();
        const checkDone = () => {
            // Cuando eventsSet ocurra se ocultará el loader; aquí solo medimos tiempo
            const ms = Math.round(performance.now() - start);
            if(statusEl){ statusEl.textContent = 'Actualizado ('+ms+' ms)'; setTimeout(()=>{ statusEl.textContent=''; }, 4000); }
        };
        // Backup timeout para medir aunque eventsSet tarde en llegar
        setTimeout(checkDone, 600);
    } catch(e){
        console.error('Error en recarga forzada', e);
        if(statusEl){ statusEl.textContent = 'Error'; setTimeout(()=>{ statusEl.textContent=''; }, 4000); }
        setCalendarError('Error en recarga');
    }
}

// Hook: después de inicializar, restaurar preferencias
(function waitForInit(){
    if(calendar){ restorePreferences(); return; }
    setTimeout(waitForInit,150);
})();

// Exportar funciones globalmente para uso en el HTML
window.changeCalendarView = changeCalendarView;
window.toggleEventSource = toggleEventSource;
window.showEventDetails = showEventDetails;
window.refreshCalendar = refreshCalendar;
window.forceReloadCalendar = forceReloadCalendar;

// Quick actions filtros
function selectAllFilters(){ ['showEvents','showTasks','showPayments'].forEach(id=>{ const el=document.getElementById(id); if(el){ el.checked=true; }}); toggleEventSource(); }
function deselectAllFilters(){ ['showEvents','showTasks','showPayments'].forEach(id=>{ const el=document.getElementById(id); if(el){ el.checked=false; }}); toggleEventSource(); }
function onlyPaymentsFilter(){ ['showEvents','showTasks'].forEach(id=>{ const el=document.getElementById(id); if(el){ el.checked=false; }}); const p=document.getElementById('showPayments'); if(p){ p.checked=true; } toggleEventSource(); }
function invertFilters(){ ['showEvents','showTasks','showPayments'].forEach(id=>{ const el=document.getElementById(id); if(el){ el.checked=!el.checked; }}); toggleEventSource(); }
window.selectAllFilters = selectAllFilters;
window.deselectAllFilters = deselectAllFilters;
window.onlyPaymentsFilter = onlyPaymentsFilter;
window.invertFilters = invertFilters;