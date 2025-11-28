<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Categoría</th>
            <th>Usuario</th>
            <th>Email Usuario</th>
            <th>Estado</th>
            <th>Tipo</th>
            <th>Créditos</th>
            <th>Fecha Creación</th>
        </tr>
    </thead>
    <tbody>
        @foreach($habilidades as $habilidad)
        <tr>
            <td>{{ $habilidad->id }}</td>
            <td>{{ $habilidad->titulo }}</td>
            <td>{{ $habilidad->categoria->nombre ?? 'Sin categoría' }}</td>
            <td>{{ $habilidad->usuario->name }}</td>
            <td>{{ $habilidad->usuario->email }}</td>
            <td>{{ ucfirst($habilidad->estado) }}</td>
            <td>{{ ucfirst($habilidad->tipo) }}</td>
            <td>{{ $habilidad->creditos }}</td>
            <td>{{ $habilidad->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
