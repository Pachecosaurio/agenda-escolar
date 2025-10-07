<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario Escolar - {{ Auth::user()->name }}</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        .header .subtitle {
            margin: 10px 0 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .stats-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            gap: 20px;
        }
        .stat-card {
            background: #f8f9fa;
            border: 2px solid #667eea;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            flex: 1;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 5px;
        }
        .stat-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 10px;
            font-weight: bold;
            font-size: 11px;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        td {
            padding: 12px 10px;
            border-bottom: 1px solid #e9ecef;
            font-size: 10px;
            text-align: center;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        tr:hover {
            background-color: #e3f2fd;
        }
        .badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge-event {
            background: #e8f5e8;
            color: #28a745;
            border: 1px solid #28a745;
        }
        .badge-task {
            background: #fff3cd;
            color: #ffc107;
            border: 1px solid #ffc107;
        }
        .no-data {
            text-align: center;
            padding: 50px 20px;
            background: #f8f9fa;
            border-radius: 12px;
            border: 2px dashed #dee2e6;
        }
        .no-data h3 {
            color: #6c757d;
            margin-bottom: 10px;
            font-size: 18px;
        }
        .no-data p {
            color: #6c757d;
            margin: 0;
            font-size: 12px;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            margin-top: 30px;
            border: 1px solid #dee2e6;
            font-size: 10px;
            color: #6c757d;
            line-height: 1.6;
        }
        .section-title {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            margin: 30px 0 20px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📅 Calendario Escolar</h1>
        <div class="subtitle">Agenda completa de eventos y tareas</div>
    </div>

    @if(isset($events) && $events->count() > 0 || isset($tasks) && $tasks->count() > 0)
        <div class="stats-section">
            <div class="stat-card">
                <div class="stat-number">{{ isset($events) ? $events->count() : 0 }}</div>
                <div class="stat-label">Eventos</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ isset($tasks) ? $tasks->count() : 0 }}</div>
                <div class="stat-label">Tareas</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ (isset($events) ? $events->count() : 0) + (isset($tasks) ? $tasks->count() : 0) }}</div>
                <div class="stat-label">Total</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Estado</th>
                    <th>Creado</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($events))
                    @foreach($events as $event)
                        <tr>
                            <td><span class="badge badge-event">Evento</span></td>
                            <td style="font-weight: bold;">{{ $event->title }}</td>
                            <td>{{ $event->description ?: 'Sin descripción' }}</td>
                            <td>{{ \Carbon\Carbon::parse($event->start)->format('d/m/Y H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($event->end)->format('d/m/Y H:i') }}</td>
                            <td>Programado</td>
                            <td>{{ $event->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @endforeach
                @endif

                @if(isset($tasks))
                    @foreach($tasks as $task)
                        <tr>
                            <td><span class="badge badge-task">Tarea</span></td>
                            <td style="font-weight: bold;">{{ $task->title }}</td>
                            <td>{{ $task->description ?: 'Sin descripción' }}</td>
                            <td>{{ $task->created_at->format('d/m/Y') }}</td>
                            <td>{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d/m/Y H:i') : 'Sin fecha límite' }}</td>
                            <td>{{ $task->completed ? 'Completada' : 'Pendiente' }}</td>
                            <td>{{ $task->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    @else
        <div class="no-data">
            <h3>📅 No hay elementos en el calendario</h3>
            <p>Aún no tienes eventos o tareas registrados en tu calendario.</p>
        </div>
    @endif

    <div class="footer">
        <strong>Agenda Escolar - Calendario Completo</strong> - Generado el {{ now()->format('d/m/Y à\s H:i:s') }}<br>
        Usuario: {{ Auth::user()->name }} ({{ Auth::user()->email }})<br>
        <em>Este documento contiene tu calendario completo con eventos y tareas.</em>
    </div>
</body>
</html>
