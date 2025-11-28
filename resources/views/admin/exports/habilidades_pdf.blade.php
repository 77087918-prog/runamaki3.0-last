<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Habilidades</title>
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
            font-size: 9px;
        }
        td {
            border: 1px solid #ddd;
            padding: 6px;
            font-size: 9px;
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
    <h1>Reporte de Habilidades - Runamaki</h1>
    <div class="meta">
        Generado: {{ now()->format('d/m/Y H:i:s') }}
    </div>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Categoría</th>
                <th>Usuario</th>
                <th>Estado</th>
                <th>Tipo</th>
                <th>Créditos</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach($habilidades as $habilidad)
            <tr>
                <td>{{ $habilidad->id }}</td>
                <td>{{ $habilidad->titulo }}</td>
                <td>{{ $habilidad->categoria->nombre ?? 'N/A' }}</td>
                <td>{{ $habilidad->usuario->name }}</td>
                <td>{{ ucfirst($habilidad->estado) }}</td>
                <td>{{ ucfirst($habilidad->tipo) }}</td>
                <td>{{ $habilidad->creditos }}</td>
                <td>{{ $habilidad->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div style="margin-top: 20px; text-align: center; color: #666; font-size: 8px;">
        Total de habilidades: {{ $habilidades->count() }}
    </div>
</body>
</html>
