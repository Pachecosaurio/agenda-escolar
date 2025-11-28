// Importar CSS primero
import '@fullcalendar/daygrid/main.css';
import '@fullcalendar/timegrid/main.css';

// Importar core de FullCalendar ANTES que los plugins
import { Calendar } from '@fullcalendar/core';
import esLocale from '@fullcalendar/core/locales/es';

// Importar plugins DESPUÉS del core
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';

// Persistencia
const LS_KEY = 'calendarPreferences_v1';
let calendar = null;
let calendarProgressTimer = null;
let calendarProgressValue = 0;

function loadPreferences(){ try { return JSON.parse(localStorage.getItem(LS_KEY)) || {}; } catch(e){ return {}; } }
function savePreferences(p){ localStorage.setItem(LS_KEY, JSON.stringify(p)); }

function setCalendarLoading(state, detail){
  const overlay = document.getElementById('calendarLoadingOverlay');
  const detailEl = document.getElementById('calendarLoadingDetail');
  if(!overlay) return;
  if(state){ if(detailEl && detail) detailEl.textContent = detail; overlay.classList.remove('hidden'); }
  else { if(detailEl && detail) detailEl.textContent = detail; setTimeout(()=>overlay.classList.add('hidden'),120); }
}
function updateProgressBar(val, phase){
  const bar = document.getElementById('calendarProgressBar');
  const valEl = document.getElementById('calendarProgressValue');
  const phaseEl = document.getElementById('calendarProgressPhase');
  calendarProgressValue = val;
  if(bar) bar.style.width = val + '%';
  if(valEl) valEl.textContent = val + '%';
  if(phaseEl && phase) phaseEl.textContent = phase;
}
function startCalendarProgress(){
  stopCalendarProgress();
  updateProgressBar(0,'Inicializando');
  const stages=[15,35,55,75,90];
  const labels=['Conectando','Obteniendo datos','Procesando','Renderizando','Ajustando'];
  let i=0;
  calendarProgressTimer = setInterval(()=>{ if(i<stages.length){ updateProgressBar(stages[i],labels[i]); i++; } else { clearInterval(calendarProgressTimer); calendarProgressTimer=null; } },500);
}
function completeCalendarProgress(cb){ updateProgressBar(100,'Completado'); setTimeout(()=>{ if(typeof cb==='function') cb(); },260); stopCalendarProgress(); }
function stopCalendarProgress(){ if(calendarProgressTimer){ clearInterval(calendarProgressTimer); calendarProgressTimer=null; } }
function setCalendarError(msg){ const overlay=document.getElementById('calendarLoadingOverlay'); const detailEl=document.getElementById('calendarLoadingDetail'); if(overlay) overlay.classList.add('error'); if(detailEl) detailEl.textContent = msg||'Error al cargar'; stopCalendarProgress(); updateProgressBar(100,'Error'); }

function initCalendar(){
  if(calendar) return;
  const calendarEl = document.getElementById('calendar');
  if(!calendarEl){ console.error('Elemento #calendar no encontrado'); return; }
  calendar = new Calendar(calendarEl, {
    plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
    initialView: 'dayGridMonth',
    locale: esLocale,
    headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,timeGridDay' },
    height: 'auto',
    eventSources: [
      { url: window.routes ? window.routes.calendarEvents : '/calendar/events', method:'GET', color:'#4facfe', textColor:'#ffffff', failure:()=>console.error('Error cargando eventos') },
      { url: window.routes ? window.routes.paymentsCalendarEvents : '/payments-calendar-events', method:'GET', color:'#f093fb', textColor:'#ffffff', failure:()=>console.error('Error cargando pagos') }
    ],
    loading: function(isLoading){ if(isLoading){ startCalendarProgress(); setCalendarLoading(true,'Cargando eventos...'); } },
    eventsSet: function(){ completeCalendarProgress(()=>{ toggleEventSource(); setCalendarLoading(false,''); }); },
    eventClick: function(info){ const url = info.event.extendedProps?.url; if(url){ info.jsEvent?.preventDefault(); window.location.href = url; return; } showEventDetails(info.event); },
    eventDidMount: function(info){ const desc = info.event.extendedProps?.description || info.event.extendedProps?.status || ''; const shortDesc = desc ? (desc.length>110?desc.substring(0,107)+'…':desc):''; const tooltip = shortDesc ? `${info.event.title} — ${shortDesc}` : info.event.title; info.el.setAttribute('title', tooltip); const eventType = info.event.extendedProps?.type || 'event'; info.el.setAttribute('data-type', eventType); const showEvents = document.getElementById('showEvents')?.checked ?? true; const showTasks = document.getElementById('showTasks')?.checked ?? true; const showPayments = document.getElementById('showPayments')?.checked ?? true; const type = eventType; const visible = (type==='event' && showEvents) || (type==='task' && showTasks) || (type==='payment' && showPayments) || (type!=='task' && type!=='payment' && showEvents); if(!visible){ info.el.classList.add('fc-event-hidden'); } },
    datesSet: function(){ updateStats(); }
  });
  calendar.render();
  bindFilterListeners();
  animateButtons();
  restorePreferences();
}

function bindFilterListeners(){ ['showEvents','showTasks','showPayments'].forEach(id=>{ const el=document.getElementById(id); if(el) el.addEventListener('change', toggleEventSource); }); }
function toggleEventSource(){ if(!calendar) return; const prefs=loadPreferences(); const showEvents=document.getElementById('showEvents')?.checked ?? true; const showTasks=document.getElementById('showTasks')?.checked ?? true; const showPayments=document.getElementById('showPayments')?.checked ?? true; prefs.filters={showEvents,showTasks,showPayments}; savePreferences(prefs); const events=calendar.getEvents(); calendar.batchRendering(()=>{ events.forEach(ev=>{ const type=ev.extendedProps?.type||'event'; const visible=(type==='event'&&showEvents)||(type==='task'&&showTasks)||(type==='payment'&&showPayments)||(type!=='task'&&type!=='payment'&&showEvents); if(ev.el){ if(!visible) ev.el.classList.add('fc-event-hidden'); else ev.el.classList.remove('fc-event-hidden'); } }); }); updateStats(); }
function saveCurrentPrefs(){ if(!calendar) return; const prefs=loadPreferences(); prefs.view=calendar.view?.type; prefs.date=calendar.getDate()?.toISOString(); savePreferences(prefs); }
function restorePreferences(){ const prefs=loadPreferences(); if(Object.keys(prefs).length===0) return; if(prefs.filters){ const {showEvents=true,showTasks=true,showPayments=true}=prefs.filters; const se=document.getElementById('showEvents'); if(se) se.checked=showEvents; const st=document.getElementById('showTasks'); if(st) st.checked=showTasks; const sp=document.getElementById('showPayments'); if(sp) sp.checked=showPayments; } setTimeout(()=>{ if(calendar){ if(prefs.view) calendar.changeView(prefs.view); if(prefs.date){ try{ calendar.gotoDate(new Date(prefs.date)); }catch(e){} } updateStats(); } },350); }

function showEventDetails(event){ const modal=document.getElementById('eventModal'); if(!modal) return; document.getElementById('eventTitle').textContent=event.title||'Sin título'; document.getElementById('eventDescription').textContent=event.extendedProps?.description||'Sin descripción'; document.getElementById('eventDate').textContent=event.start?event.start.toLocaleDateString('es-ES'):'Sin fecha'; const bsModal=new bootstrap.Modal(modal); bsModal.show(); }
function updateStats(){ if(!calendar) return; const fShowEvents=document.getElementById('showEvents')?.checked ?? true; const fShowTasks=document.getElementById('showTasks')?.checked ?? true; const fShowPayments=document.getElementById('showPayments')?.checked ?? true; const events=calendar.getEvents(); let totalEvents=0,totalTasks=0,totalPayments=0,overdue=0; const today=new Date(); events.forEach(ev=>{ const type=ev.extendedProps?.type||'event'; const visible=(type==='event'&&fShowEvents)||(type==='task'&&fShowTasks)||(type==='payment'&&fShowPayments)||(type!=='task'&&type!=='payment'&&fShowEvents); if(!visible) return; if(type==='event') totalEvents++; else if(type==='task') totalTasks++; else if(type==='payment') totalPayments++; if((type==='task'||type==='payment') && ev.start && ev.start<today){ const status=ev.extendedProps?.status; if(!status || (status!=='completed' && status!=='paid')) overdue++; } }); const elTotalEvents=document.getElementById('totalEvents'); if(elTotalEvents) elTotalEvents.textContent=totalEvents; const elTotalTasks=document.getElementById('totalTasks'); if(elTotalTasks) elTotalTasks.textContent=totalTasks; const elTotalPayments=document.getElementById('totalPayments'); if(elTotalPayments) elTotalPayments.textContent=totalPayments; const elOverdue=document.getElementById('overdueItems'); if(elOverdue) elOverdue.textContent=overdue; const fcEvents=document.getElementById('filterCountEvents'); if(fcEvents) fcEvents.textContent=totalEvents; const fcTasks=document.getElementById('filterCountTasks'); if(fcTasks) fcTasks.textContent=totalTasks; const fcPayments=document.getElementById('filterCountPayments'); if(fcPayments) fcPayments.textContent=totalPayments; const summaryEl=document.getElementById('filterActiveSummary'); if(summaryEl){ const active=[]; if(fShowEvents) active.push('Eventos'); if(fShowTasks) active.push('Tareas'); if(fShowPayments) active.push('Pagos'); summaryEl.textContent=active.length? 'Mostrando: '+active.join(', '):'Sin fuentes visibles'; } const tsEl=document.getElementById('calendarStatsUpdatedAt'); if(tsEl){ const now=new Date(); tsEl.textContent=now.toLocaleTimeString('es-ES',{hour:'2-digit',minute:'2-digit',second:'2-digit'}); } }
function animateButtons(){ const buttons=document.querySelectorAll('.animate-btn'); buttons.forEach((btn,index)=>{ setTimeout(()=>{ btn.style.opacity='1'; btn.style.transform='translateY(0)'; }, index*100); }); }
function refreshCalendar(){ if(!calendar){ console.warn('refreshCalendar() antes de init'); return; } try { setCalendarLoading(true,'Actualizando...'); calendar.refetchEvents(); } catch(e){ console.error('Error refetch', e); setCalendarError('Error al refrescar'); } }
function forceReloadCalendar(){ if(!calendar) return; const statusEl=document.getElementById('forceReloadStatus'); if(statusEl) statusEl.textContent='Recargando...'; const start=performance.now(); try { setCalendarLoading(true,'Recargando datos...'); calendar.refetchEvents(); setTimeout(()=>{ const ms=Math.round(performance.now()-start); if(statusEl){ statusEl.textContent='Actualizado ('+ms+' ms)'; setTimeout(()=>{ statusEl.textContent=''; },4000); } },600); } catch(e){ console.error('Error recarga forzada', e); if(statusEl){ statusEl.textContent='Error'; setTimeout(()=>{ statusEl.textContent=''; },4000); } setCalendarError('Error en recarga'); } }

window.changeCalendarView = function(view){ if(!calendar) return; calendar.changeView(view); document.querySelectorAll('[data-view]').forEach(btn=>{ btn.classList.remove('btn-primary'); btn.classList.add('btn-outline-primary'); }); const activeBtn=document.querySelector(`[data-view="${view}"]`); if(activeBtn){ activeBtn.classList.remove('btn-outline-primary'); activeBtn.classList.add('btn-primary'); } saveCurrentPrefs(); };
window.toggleEventSource = toggleEventSource;
window.showEventDetails = showEventDetails;
window.refreshCalendar = refreshCalendar;
window.forceReloadCalendar = forceReloadCalendar;
window.selectAllFilters = function(){ ['showEvents','showTasks','showPayments'].forEach(id=>{ const el=document.getElementById(id); if(el){ el.checked=true; }}); toggleEventSource(); };
window.deselectAllFilters = function(){ ['showEvents','showTasks','showPayments'].forEach(id=>{ const el=document.getElementById(id); if(el){ el.checked=false; }}); toggleEventSource(); };
window.onlyPaymentsFilter = function(){ ['showEvents','showTasks'].forEach(id=>{ const el=document.getElementById(id); if(el){ el.checked=false; }}); const p=document.getElementById('showPayments'); if(p){ p.checked=true; } toggleEventSource(); };
window.invertFilters = function(){ ['showEvents','showTasks','showPayments'].forEach(id=>{ const el=document.getElementById(id); if(el){ el.checked=!el.checked; }}); toggleEventSource(); };

// Inicializar cuando el DOM esté listo
if(document.readyState === 'loading'){ document.addEventListener('DOMContentLoaded', initCalendar); } else { initCalendar(); }
