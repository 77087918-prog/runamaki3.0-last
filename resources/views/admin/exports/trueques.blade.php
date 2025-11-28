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
            <th>Fecha Creación</th>
            <th>Fecha Completado</th>
        </tr>
    </thead>
    <tbody>
        @foreach($trueques as $trueque)
        <tr>
            <td>{{ $trueque->id }}</td>
            <td>{{ $trueque->usuarioOfrece->name ?? 'N/A' }} ({{ $trueque->usuarioOfrece->email ?? 'N/A' }})</td>
            <td>{{ $trueque->usuarioRecibe->name ?? 'N/A' }} ({{ $trueque->usuarioRecibe->email ?? 'N/A' }})</td>
            <td>{{ $trueque->habilidadOfrece->titulo ?? 'N/A' }}</td>
            <td>{{ $trueque->habilidadRecibe->titulo ?? 'N/A' }}</td>
            <td>{{ ucfirst($trueque->estado) }}</td>
            <td>{{ $trueque->puntos_intercambio ?? 0 }}</td>
            <td>{{ $trueque->created_at->format('d/m/Y H:i') }}</td>
            <td>{{ $trueque->fecha_completado ? $trueque->fecha_completado->format('d/m/Y H:i') : 'Pendiente' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
