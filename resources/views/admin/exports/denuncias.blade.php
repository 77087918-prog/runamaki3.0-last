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
            <th>Comentario Admin</th>
            <th>Fecha Denuncia</th>
            <th>Fecha Procesamiento</th>
        </tr>
    </thead>
    <tbody>
        @foreach($denuncias as $denuncia)
        <tr>
            <td>{{ $denuncia->id }}</td>
            <td>{{ $denuncia->denunciante->name }} ({{ $denuncia->denunciante->email }})</td>
            <td>{{ $denuncia->denunciado->name }} ({{ $denuncia->denunciado->email }})</td>
            <td>{{ ucfirst($denuncia->tipo) }}</td>
            <td>{{ $denuncia->motivo }}</td>
            <td>{{ ucfirst($denuncia->estado) }}</td>
            <td>{{ $denuncia->procesadoPor->name ?? 'Sin procesar' }}</td>
            <td>{{ $denuncia->comentario_admin ?? 'N/A' }}</td>
            <td>{{ $denuncia->created_at->format('d/m/Y H:i') }}</td>
            <td>{{ $denuncia->procesada_at ? $denuncia->procesada_at->format('d/m/Y H:i') : 'Pendiente' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
