<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Iguala App') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background: #f5f7fa; }
        .navbar-brand { font-weight: bold; }
        .card { border-radius: 10px; }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow">
            <div class="container">
                <a class="navbar-brand" href="{{ url(auth()->check() ? '/dashboard' : '/login') }}">
                    🚕 {{ config('app.name', 'Iguala App') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto">
                        @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/dashboard') }}">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>

                        @if(Auth::user()->esAdmin())
                            <li class="nav-item"><a class="nav-link" href="{{ url('/conductor') }}">Conductores</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ url('/empresa') }}">Empresas</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ url('/viaje') }}">Viajes</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ url('/tarifa') }}">Tarifas</a></li>
                        @elseif(Auth::user()->esConductor())
                            <li class="nav-item"><a class="nav-link" href="{{ url('/viaje') }}">Mis Viajes</a></li>
                        @elseif(Auth::user()->esPasajero())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/viaje/create') }}">
                                    <i class="bi bi-plus-circle"></i> Solicitar Viaje
                                </a>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="{{ url('/viaje') }}">Mis Viajes</a></li>
                        @endif
                        @endauth
                    </ul>

                    <ul class="navbar-nav ms-auto">
                        @auth
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    {{ Auth::user()->nombre_completo }}
                                    <span class="badge bg-secondary">{{ ucfirst(Auth::user()->rol) }}</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <form method="POST" action="{{ url('/logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Cerrar Sesión</button>
                                    </form>
                                </div>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
