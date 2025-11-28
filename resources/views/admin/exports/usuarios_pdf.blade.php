<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Usuarios</title>
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
    <h1>Reporte de Usuarios - Runamaki</h1>
    <div class="meta">
        Generado: {{ now()->format('d/m/Y H:i:s') }}
    </div>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Estado</th>
                <th>Rol</th>
                <th>Puntos</th>
                <th>Habilidades</th>
                <th>Valoración</th>
                <th>Registro</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usuarios as $usuario)
            <tr>
                <td>{{ $usuario->id }}</td>
                <td>{{ $usuario->name }}</td>
                <td>{{ $usuario->email }}</td>
                <td>{{ ucfirst($usuario->estado) }}</td>
                <td>{{ ucfirst($usuario->rol) }}</td>
                <td>{{ $usuario->puntos }}</td>
                <td>{{ $usuario->habilidades_count ?? 0 }}</td>
                <td>N/A</td>
                <td>{{ $usuario->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div style="margin-top: 20px; text-align: center; color: #666; font-size: 8px;">
        Total de usuarios: {{ $usuarios->count() }}
    </div>
</body>
</html>
