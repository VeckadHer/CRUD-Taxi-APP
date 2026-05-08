@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">🚕 Iguala App - Iniciar Sesión</h4>
                </div>
                <div class="card-body p-4">
                    @if(Session::has('mensaje'))
                    <div class="alert alert-success">{{ Session::get('mensaje') }}</div>
                    @endif

                    @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
                    </div>
                    @endif

                    <form method="POST" action="{{ url('/login') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contraseña</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">Recordarme</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 btn-lg">Ingresar</button>
                    </form>

                    <hr class="my-4">

                    <div class="row text-center">
                        <div class="col-6">
                            <p class="mb-2"><strong>¿Eres usuario?</strong></p>
                            <a href="{{ url('/register') }}" class="btn btn-success">📝 Crear Cuenta</a>
                        </div>
                        <div class="col-6">
                            <p class="mb-2"><strong>¿Quieres ser conductor?</strong></p>
                            <a href="{{ url('/solicitud-conductor') }}" class="btn btn-warning">🚖 Postularme</a>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light">
                    <p class="mb-2 text-center"><strong>📞 Empresas afiliadas</strong></p>
                    <div class="row text-center small">
                        <div class="col-3">TAXIS Tigres<br>733-100-1001</div>
                        <div class="col-3">TAXIS Tupi<br>733-100-2002</div>
                        <div class="col-3">TAXIS Alfa<br>733-100-3003</div>
                        <div class="col-3">TAXIS Serti<br>733-100-4004</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
