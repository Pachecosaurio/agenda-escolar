<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exportación de Calendario - {{ Auth::user()->name }}</title>
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
        .type-event { color: #1565c0; font-weight: bold; }
        .type-task { color: #ffd600; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">Agenda Escolar - Exportación de Calendario</div>
    <table>
        <thead>
            <tr>
                <th>Tipo</th>
                <th>Título</th>
                <th>Descripción</th>
                <th>Fecha inicio</th>
                <th>Fecha fin</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td class="type-{{ $item['type'] }}">{{ ucfirst($item['type']) }}</td>
                <td>{{ $item['title'] }}</td>
                <td>{{ $item['description'] ?? '-' }}</td>
                <td>{{ $item['start'] ?? '-' }}</td>
                <td>{{ $item['end'] ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
