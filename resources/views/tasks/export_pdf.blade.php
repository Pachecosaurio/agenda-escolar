{{-- Export PDF de Tareas (plantilla HTML para DomPDF). --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tareas Escolares - {{ Auth::user()->name }}</title>
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
            background: linear-gradient(135deg, #ffc107 0%, #ff8f00 100%);
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
            border-left: 4px solid #ffc107;
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
            color: #ffc107;
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
            background: linear-gradient(135deg, #ffc107 0%, #ff8f00 100%);
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
        .task-title {
            font-weight: bold;
            color: #ff8f00;
            font-size: 14px;
        }
        .task-description {
            color: #6c757d;
            font-style: italic;
            max-width: 200px;
            word-wrap: break-word;
        }
        .date-text {
            font-weight: 600;
            color: #495057;
        }
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status-completed {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .status-pending {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        .status-overdue {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .priority-high {
            background: #ffebee;
            border-left: 4px solid #f44336;
        }
        .priority-medium {
            background: #fff8e1;
            border-left: 4px solid #ff9800;
        }
        .priority-low {
            background: #f3e5f5;
            border-left: 4px solid #9c27b0;
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
        <h1>📝 Mis Tareas Escolares</h1>
        <div class="subtitle">Lista completa de tareas y asignaciones</div>
    </div>

    <div class="user-info">
        <h3>👤 Información del Usuario</h3>
        <strong>Nombre:</strong> {{ Auth::user()->name }}<br>
        <strong>Email:</strong> {{ Auth::user()->email }}<br>
        <strong>Fecha de exportación:</strong> {{ now()->format('d/m/Y H:i:s') }}
    </div>

    @php
        $totalTasks = count($tasks);
        $completedTasks = $tasks->where('completed', true)->count();
        $pendingTasks = $tasks->where('completed', false)->count();
        $overdueTasks = $tasks->filter(function($task) {
            return $task->due_date && \Carbon\Carbon::parse($task->due_date)->isPast() && !$task->completed;
        })->count();
    @endphp

    <div class="stats">
        <div class="stat-box">
            <div class="stat-number">{{ $totalTasks }}</div>
            <div class="stat-label">Total Tareas</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $completedTasks }}</div>
            <div class="stat-label">Completadas</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $pendingTasks }}</div>
            <div class="stat-label">Pendientes</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $overdueTasks }}</div>
            <div class="stat-label">Vencidas</div>
        </div>
    </div>

    @if($tasks->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 200px;">Tarea</th>
                    <th style="width: 250px;">Descripción</th>
                    <th style="width: 120px;">Fecha Límite</th>
                    <th style="width: 80px;">Estado</th>
                    <th style="width: 100px;">Creada</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks->sortBy('due_date') as $task)
                @php
                    $dueDate = $task->due_date ? \Carbon\Carbon::parse($task->due_date) : null;
                    $isOverdue = $dueDate && $dueDate->isPast() && !$task->completed;
                    $status = $task->completed ? 'completed' : ($isOverdue ? 'overdue' : 'pending');
                    $createdDate = \Carbon\Carbon::parse($task->created_at);
                @endphp
                <tr>
                    <td class="task-title">{{ $task->title }}</td>
                    <td class="task-description">{{ $task->description ?: 'Sin descripción' }}</td>
                    <td class="date-text">
                        {{ $dueDate ? $dueDate->format('d/m/Y H:i') : 'Sin fecha límite' }}
                        @if($dueDate && $dueDate->isToday())
                            <br><small style="color: #dc3545; font-weight: bold;">¡Hoy!</small>
                        @elseif($dueDate && $dueDate->isTomorrow())
                            <br><small style="color: #ffc107; font-weight: bold;">Mañana</small>
                        @endif
                    </td>
                    <td>
                        <span class="status-badge status-{{ $status }}">
                            @if($status == 'completed')
                                ✅ Completada
                            @elseif($status == 'overdue')
                                ⏰ Vencida
                            @else
                                📋 Pendiente
                            @endif
                        </span>
                    </td>
                    <td class="date-text">{{ $createdDate->format('d/m/Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <h3>📝 No hay tareas registradas</h3>
            <p>Aún no tienes tareas asignadas en tu lista.</p>
        </div>
    @endif

    <div class="footer">
        <strong>Agenda Escolar - Tareas</strong> - Generado el {{ now()->format('d/m/Y à\s H:i:s') }}<br>
        Usuario: {{ Auth::user()->name }} ({{ Auth::user()->email }})<br>
        <em>Este documento contiene {{ $tasks->count() }} tareas de tu lista personal.</em>
    </div>
</body>
</html>
</body>
</html>
