@extends('layouts.app')
@section('content')
<div class="container mt-4">
    @if(Session::has('mensaje'))
    <div class="alert alert-success">{{ Session::get('mensaje') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>🏢 Empresas de Taxi</h2>
        <a href="{{ url('/empresa/create') }}" class="btn btn-success">+ Nueva Empresa</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr><th>ID</th><th>Nombre</th><th>Razón Social</th><th>Teléfono</th><th>Conductores</th><th>Estado</th><th>Acciones</th></tr>
                </thead>
                <tbody>
                    @foreach($empresas as $e)
                    <tr>
                        <td>{{ $e->id_empresa }}</td>
                        <td><strong>{{ $e->nombre }}</strong></td>
                        <td><small>{{ $e->razon_social }}</small></td>
                        <td>{{ $e->telefono }}</td>
                        <td><span class="badge bg-info">{{ $e->conductores_count }} conductores</span></td>
                        <td><span class="badge bg-{{ $e->activa ? 'success' : 'secondary' }}">{{ $e->activa ? 'Activa' : 'Inactiva' }}</span></td>
                        <td>
                            <a href="{{ url('/empresa/'.$e->id_empresa.'/edit') }}" class="btn btn-sm btn-warning">✏️</a>
                            <form action="{{ url('/empresa/'.$e->id_empresa) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar?')">🗑️</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $empresas->links() }}</div>
</div>
@endsection
