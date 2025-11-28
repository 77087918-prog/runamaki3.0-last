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
            <th>Fecha Registro</th>
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
            <td>{{ $usuario->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
