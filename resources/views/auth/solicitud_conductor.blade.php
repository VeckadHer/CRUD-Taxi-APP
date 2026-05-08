@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark text-center">
                    <h4 class="mb-0">🚖 Quiero Ser Conductor</h4>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted">
                        ¿Te interesa unirte a nuestro equipo de conductores en Iguala? Deja tus datos y un administrador 
                        te contactará para concretar el registro y entregarte tus credenciales de acceso.
                    </p>

                    @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
                    </div>
                    @endif

                    <form method="POST" action="{{ url('/solicitud-conductor') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nombre Completo *</label>
                            <input type="text" name="nombre_completo" class="form-control" value="{{ old('nombre_completo') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Teléfono *</label>
                            <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}" placeholder="733xxxxxxx" required>
                            <small class="text-muted">Te contactaremos a este número</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Correo Electrónico (opcional)</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mensaje (opcional)</label>
                            <textarea name="mensaje" class="form-control" rows="3" placeholder="Cuéntanos un poco sobre ti, experiencia, empresa de preferencia, etc.">{{ old('mensaje') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-warning btn-lg w-100">📞 Enviar Solicitud</button>
                    </form>

                    <hr>
                    <p class="text-center mb-0"><a href="{{ url('/login') }}">← Volver al login</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
