{{-- PDF simple de listado de Tareas. --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Tareas - {{ Auth::user()->name }}</title>
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
            font-size: 28px;
            font-weight: bold;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; border-radius: 0 0 12px 12px; overflow: hidden; }
        th {
            background: #1565c0;
            color: #fff;
            font-weight: bold;
            font-size: 1.1rem;
            padding: 10px;
            border: none;
        }
        td {
            padding: 8px;
            border: none;
            text-align: center;
        }
        tr:nth-child(even) { background: #e3f0ff; }
        tr:nth-child(odd) { background: #fffbe6; }
        .completed { color: #ffd600; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">Agenda Escolar - Lista de Tareas</div>
    <table>
        <thead>
            <tr>
                <th>Título</th>
                <th>Descripción</th>
                <th>Fecha límite</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tasks as $task)
            <tr>
                <td>{{ $task->title }}</td>
                <td>{{ $task->description }}</td>
                <td>{{ $task->due_date ? date('d/m/Y H:i', strtotime($task->due_date)) : '-' }}</td>
                <td class="completed">{{ $task->completed ? 'Completada' : 'Pendiente' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
