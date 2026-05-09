@extends('layouts.app')
@section('content')
<div class="container mt-4">
    @if(Session::has('mensaje'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ Session::get('mensaje') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <h2>📞 Solicitudes para ser Conductor</h2>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Filtrar por estado</label>
                    <select name="estado" class="form-control">
                        <option value="">Todas</option>
                        <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendientes</option>
                        <option value="contactado" {{ request('estado') == 'contactado' ? 'selected' : '' }}>Contactadas</option>
                        <option value="registrado" {{ request('estado') == 'registrado' ? 'selected' : '' }}>Registradas</option>
                        <option value="rechazado" {{ request('estado') == 'rechazado' ? 'selected' : '' }}>Rechazadas</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">🔍 Filtrar</button>
                    <a href="{{ url('/solicitudes-conductor') }}" class="btn btn-link">Limpiar</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Mensaje</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($solicitudes as $s)
                    <tr>
                        <td>{{ $s->id_solicitud }}</td>
                        <td><strong>{{ $s->nombre_completo }}</strong></td>
                        <td><a href="tel:{{ $s->telefono }}">{{ $s->telefono }}</a></td>
                        <td>{{ $s->email ?? '—' }}</td>
                        <td><small>{{ Str::limit($s->mensaje, 60) }}</small></td>
                        <td><small>{{ \Carbon\Carbon::parse($s->fecha_solicitud)->format('d/m/Y H:i') }}</small></td>
                        <td>
                            @php $colors = ['pendiente'=>'warning','contactado'=>'info','registrado'=>'success','rechazado'=>'danger']; @endphp
                            <span class="badge bg-{{ $colors[$s->estado] ?? 'secondary' }}">{{ ucfirst($s->estado) }}</span>
                        </td>
                        <td>
                            @if($s->estado == 'pendiente')
                                <a href="{{ url('/solicitudes-conductor/'.$s->id_solicitud) }}" class="btn btn-sm btn-success">✓ Aprobar</a>
                                <form action="{{ url('/solicitudes-conductor/'.$s->id_solicitud.'/rechazar') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('¿Rechazar?')">❌</button>
                                </form>
                            @else
                                <form action="{{ url('/solicitudes-conductor/'.$s->id_solicitud) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-secondary" onclick="return confirm('¿Eliminar?')">🗑️</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-3">Sin solicitudes</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $solicitudes->withQueryString()->links() }}</div>
</div>
@endsection
