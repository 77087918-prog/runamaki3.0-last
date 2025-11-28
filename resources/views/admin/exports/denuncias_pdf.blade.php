<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Denuncias</title>
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
    <h1>Reporte de Denuncias - Runamaki</h1>
    <div class="meta">
        Generado: {{ now()->format('d/m/Y H:i:s') }}
    </div>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Denunciante</th>
                <th>Denunciado</th>
                <th>Tipo</th>
                <th>Motivo</th>
                <th>Estado</th>
                <th>Procesado Por</th>
                <th>Comentario</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach($denuncias as $denuncia)
            <tr>
                <td>{{ $denuncia->id }}</td>
                <td>{{ $denuncia->denunciante->name }}</td>
                <td>{{ $denuncia->denunciado->name }}</td>
                <td>{{ ucfirst($denuncia->tipo) }}</td>
                <td>{{ Str::limit($denuncia->motivo, 50) }}</td>
                <td>{{ ucfirst($denuncia->estado) }}</td>
                <td>{{ $denuncia->procesadoPor->name ?? 'Sin procesar' }}</td>
                <td>{{ $denuncia->comentario_admin ? Str::limit($denuncia->comentario_admin, 40) : 'N/A' }}</td>
                <td>{{ $denuncia->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div style="margin-top: 20px; text-align: center; color: #666; font-size: 8px;">
        Total de denuncias: {{ $denuncias->count() }}
    </div>
</body>
</html>
