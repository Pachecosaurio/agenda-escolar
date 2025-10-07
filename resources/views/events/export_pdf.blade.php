<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos Escolares - {{ Auth::user()->name }}</title>
    <style>
        @page {
            margin: 20mm;
            size: A4;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .header .subtitle {
            margin-top: 8px;
            font-size: 14px;
            opacity: 0.9;
        }
        .user-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            border-left: 4px solid #28a745;
        }
        .user-info h3 {
            margin: 0 0 8px 0;
            color: #495057;
            font-size: 16px;
        }
        .stats {
            display: flex;
            justify-content: space-around;
            margin-bottom: 30px;
            text-align: center;
        }
        .stat-box {
            background: white;
            padding: 15px;
            border-radius: 10px;
            border: 2px solid #e9ecef;
            flex: 1;
            margin: 0 10px;
        }
        .stat-number {
            font-size: 28px;
            font-weight: bold;
            color: #28a745;
            margin-bottom: 5px;
        }
        .stat-label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        th {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            font-weight: bold;
            font-size: 13px;
            padding: 15px 10px;
            text-align: left;
            border: none;
        }
        td {
            padding: 12px 10px;
            border-bottom: 1px solid #e9ecef;
            vertical-align: top;
        }
        tr:nth-child(even) {
            background: #f8f9fa;
        }
        .event-title {
            font-weight: bold;
            color: #28a745;
            font-size: 14px;
        }
        .event-description {
            color: #6c757d;
            font-style: italic;
            max-width: 200px;
            word-wrap: break-word;
        }
        .date-text {
            font-weight: 600;
            color: #495057;
        }
        .duration-badge {
            background: #e8f5e8;
            color: #28a745;
            padding: 4px 8px;
            border-radius: 15px;
            font-size: 10px;
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            font-size: 11px;
            color: #6c757d;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #6c757d;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📅 Mis Eventos Escolares</h1>
        <div class="subtitle">Lista completa de eventos programados</div>
    </div>

    <div class="user-info">
        <h3>👤 Información del Usuario</h3>
        <strong>Nombre:</strong> {{ Auth::user()->name }}<br>
        <strong>Email:</strong> {{ Auth::user()->email }}<br>
        <strong>Fecha de exportación:</strong> {{ now()->format('d/m/Y H:i:s') }}
    </div>

    @php
        $totalEvents = count($events);
        $upcomingEvents = $events->filter(function($event) {
            return strtotime($event->start) >= strtotime('today');
        })->count();
        $pastEvents = $totalEvents - $upcomingEvents;
        $thisMonth = $events->filter(function($event) {
            return date('Y-m', strtotime($event->start)) == date('Y-m');
        })->count();
    @endphp

    <div class="stats">
        <div class="stat-box">
            <div class="stat-number">{{ $totalEvents }}</div>
            <div class="stat-label">Total Eventos</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $upcomingEvents }}</div>
            <div class="stat-label">Próximos</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $pastEvents }}</div>
            <div class="stat-label">Pasados</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $thisMonth }}</div>
            <div class="stat-label">Este Mes</div>
        </div>
    </div>

    @if($events->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 200px;">Evento</th>
                    <th style="width: 250px;">Descripción</th>
                    <th style="width: 120px;">Fecha Inicio</th>
                    <th style="width: 120px;">Fecha Fin</th>
                    <th style="width: 80px;">Duración</th>
                </tr>
            </thead>
            <tbody>
                @foreach($events->sortBy('start') as $event)
                @php
                    $startDate = $event->start ? \Carbon\Carbon::parse($event->start) : null;
                    $endDate = $event->end ? \Carbon\Carbon::parse($event->end) : null;
                    $duration = $startDate && $endDate ? $startDate->diffInHours($endDate) : 0;
                @endphp
                <tr>
                    <td class="event-title">{{ $event->title }}</td>
                    <td class="event-description">{{ $event->description ?: 'Sin descripción' }}</td>
                    <td class="date-text">
                        {{ $startDate ? $startDate->format('d/m/Y H:i') : '-' }}
                    </td>
                    <td class="date-text">
                        {{ $endDate ? $endDate->format('d/m/Y H:i') : '-' }}
                    </td>
                    <td>
                        @if($duration > 0)
                            <span class="duration-badge">{{ $duration }}h</span>
                        @else
                            <span class="duration-badge">Todo el día</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <h3>📅 No hay eventos registrados</h3>
            <p>Aún no tienes eventos programados en tu calendario.</p>
        </div>
    @endif

    <div class="footer">
        <strong>Agenda Escolar - Eventos</strong> - Generado el {{ now()->format('d/m/Y à\s H:i:s') }}<br>
        Usuario: {{ Auth::user()->name }} ({{ Auth::user()->email }})<br>
        <em>Este documento contiene {{ $events->count() }} eventos de tu agenda personal.</em>
    </div>
</body>
</html>
