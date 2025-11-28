<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Solicitante</th>
            <th>Proveedor</th>
            <th>Habilidad Solicitada</th>
            <th>Habilidad Ofrecida</th>
            <th>Estado</th>
            <th>Créditos</th>
            <th>Fecha Creación</th>
            <th>Fecha Finalización</th>
        </tr>
    </thead>
    <tbody>
        @foreach($trueques as $trueque)
        <tr>
            <td>{{ $trueque->id }}</td>
            <td>{{ $trueque->solicitante->name }} ({{ $trueque->solicitante->email }})</td>
            <td>{{ $trueque->proveedor->name }} ({{ $trueque->proveedor->email }})</td>
            <td>{{ $trueque->habilidadSolicitada->titulo ?? 'N/A' }}</td>
            <td>{{ $trueque->habilidadOfrecida->titulo ?? 'N/A' }}</td>
            <td>{{ ucfirst($trueque->estado) }}</td>
            <td>{{ $trueque->creditos }}</td>
            <td>{{ $trueque->created_at->format('d/m/Y H:i') }}</td>
            <td>{{ $trueque->fecha_finalizacion ? $trueque->fecha_finalizacion->format('d/m/Y H:i') : 'Pendiente' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
