<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Trueques</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }
        h1 {
            text-align: center;
            color: #7c3aed;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #7c3aed;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 8px;
        }
        td {
            border: 1px solid #ddd;
            padding: 6px;
            font-size: 8px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .meta {
            text-align: right;
            color: #666;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>Reporte de Trueques - Runamaki</h1>
    <div class="meta">
        Generado: {{ now()->format('d/m/Y H:i:s') }}
    </div>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario Ofrece</th>
                <th>Usuario Recibe</th>
                <th>Habilidad Ofrece</th>
                <th>Habilidad Recibe</th>
                <th>Estado</th>
                <th>Puntos</th>
                <th>Creación</th>
                <th>Completado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($trueques as $trueque)
            <tr>
                <td>{{ $trueque->id }}</td>
                <td>{{ $trueque->usuarioOfrece->name ?? 'N/A' }}</td>
                <td>{{ $trueque->usuarioRecibe->name ?? 'N/A' }}</td>
                <td>{{ $trueque->habilidadOfrece->titulo ?? 'N/A' }}</td>
                <td>{{ $trueque->habilidadRecibe->titulo ?? 'N/A' }}</td>
                <td>{{ ucfirst($trueque->estado) }}</td>
                <td>{{ $trueque->puntos_intercambio ?? 0 }}</td>
                <td>{{ $trueque->created_at->format('d/m/Y') }}</td>
                <td>{{ $trueque->fecha_completado ? $trueque->fecha_completado->format('d/m/Y') : 'Pendiente' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div style="margin-top: 20px; text-align: center; color: #666; font-size: 8px;">
        Total de trueques: {{ $trueques->count() }}
    </div>
</body>
</html>
