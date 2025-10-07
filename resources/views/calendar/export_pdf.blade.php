<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exportar Calendario PDF - {{ Auth::user()->name }}</title>
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
    </style>
</head>
<body>
    <div class="header">
        <h1>📅 Calendario Escolar: Eventos y Tareas</h1>
    </div>
    <table>
        <thead>
            <tr>
                <th>Tipo</th>
                <th>Título</th>
                <th>Descripción</th>
                <th>Inicio</th>
                <th>Fin</th>
                <th>Fecha límite</th>
                <th>Creado</th>
                <th>Actualizado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item['Tipo'] }}</td>
                <td>{{ $item['Título'] }}</td>
                <td>{{ $item['Descripción'] }}</td>
                <td>{{ $item['Inicio'] }}</td>
                <td>{{ $item['Fin'] }}</td>
                <td>{{ $item['Fecha límite'] }}</td>
                <td>{{ $item['Creado'] }}</td>
                <td>{{ $item['Actualizado'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <strong>Agenda Escolar - Calendario Completo</strong> - Generado el {{ now()->format('d/m/Y à\s H:i:s') }}<br>
        Usuario: {{ Auth::user()->name }} ({{ Auth::user()->email }})<br>
        <em>Este documento contiene {{ count($items) }} elementos de tu calendario.</em>
    </div>
</body>
</html>
