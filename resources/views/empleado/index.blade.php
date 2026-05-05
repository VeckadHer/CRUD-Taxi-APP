@extends('layouts.app')
@section('content')
<div class="container">

@if(Session::has('mensaje'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ Session::get('mensaje') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<<a href="{{ url('empleado/create') }}" class="btn btn-success">
    <i class="bi bi-plus-circle"></i> Registrar nuevo empleado
</a>

<table class="table thead-light">
    <thead class="thead-light">
        <tr>
            <th>#</th>
            <th>Foto</th>
            <th>Nombre</th>
            <th>Apellido Paterno</th>
            <th>Apellido Materno</th>
            <th>Correo</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($empleados as $empleado)
        <tr>
            <td>{{ $empleado->id }}</td>
            <td>
                @if($empleado->Foto)
                <img class="img-thumbnail" src="{{ asset('storage/'.$empleado->Foto) }}" width="100" alt="">
                @else
                Sin foto
                @endif
            </td>
            <td>{{ $empleado->Nombre }}</td>
            <td>{{ $empleado->Apellido_Paterno }}</td>
            <td>{{ $empleado->Apellido_Materno }}</td>
            <td>{{ $empleado->Correo }}</td>
            <td>
                <a href="{{ url('/empleado/'.$empleado->id.'/edit') }}" class="btn btn-warning">Editar</a>

                <form action="{{ url('/empleado/'.$empleado->id) }}" class="d-inline" method="POST">
                    @csrf
                    @method('DELETE')
                    <input class="btn btn-danger" type="submit" onclick="return confirm('¿Quieres borrar?')" value="Borrar">
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{!! $empleados->links()    !!}
</div>
@endsection