@extends('layouts.app')
@section('content')
<div class="container mt-4">
    @if(Session::has('mensaje'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ Session::get('mensaje') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Conductores</h2>
        <a href="{{ url('conductor/create') }}" class="btn btn-success">+ Nuevo Conductor</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Licencia</th>
                    <th>Calificación</th>
                    <th>Estado</th>
                    <th>Disponible</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($conductores as $c)
                <tr>
                    <td>{{ $c->id_conductor }}</td>
                    <td>{{ $c->usuario->nombre_completo }}</td>
                    <td>{{ $c->licencia_conducir }}</td>
                    <td>{{ $c->calificacion_promedio }}/5</td>
                    <td><span class="badge bg-success">{{ $c->estado }}</span></td>
                    <td>{{ $c->disponible ? '✓' : '✗' }}</td>
                    <td>
                        <a href="{{ url('conductor/'.$c->id_conductor.'/edit') }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ url('conductor/'.$c->id_conductor) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center">No hay conductores</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $conductores->links() }}
</div>
@endsection
