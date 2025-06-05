<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistema de Pedidos')</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Estilos globales -->
    <style>
        :root {
            --royal-blue: #002349;
            --gold: #957C3D;
            --light-gold: #c4aa6a;
            --very-light-gold: #f5eeda;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Navbar styles */
        .navbar {
            background-color: var(--royal-blue) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: bold;
            color: var(--gold) !important;
        }

        .navbar-dark .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.8);
        }

        .navbar-dark .navbar-nav .nav-link:hover,
        .navbar-dark .navbar-nav .nav-link:focus {
            color: var(--gold);
        }

        .navbar-dark .navbar-nav .active > .nav-link,
        .navbar-dark .navbar-nav .nav-link.active {
            color: var(--gold);
        }

        .dropdown-item:active {
            background-color: var(--gold);
        }

        /* Footer styles */
        footer {
            background-color: var(--royal-blue);
            color: white;
            padding: 40px 0 20px;
            margin-top: auto;
            border-top: 5px solid var(--gold);
        }

        footer h5 {
            color: var(--gold);
            font-weight: 600;
            margin-bottom: 20px;
        }

        footer a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
        }

        footer a:hover {
            color: var(--gold);
            text-decoration: none;
        }

        .footer-copyright {
            background-color: rgba(0, 0, 0, 0.2);
            padding: 15px 0;
            margin-top: 30px;
            font-size: 0.9rem;
        }

        /* Button styles */
        .btn-primary {
            background-color: var(--gold) !important;
            border-color: var(--gold) !important;
            color: white !important;
        }

        .btn-primary:hover {
            background-color: var(--light-gold) !important;
            border-color: var(--light-gold) !important;
        }

        .btn-outline-primary {
            color: var(--gold) !important;
            border-color: var(--gold) !important;
        }

        .btn-outline-primary:hover {
            background-color: var(--gold) !important;
            color: white !important;
        }

        .btn-outline-light:hover {
            color: var(--royal-blue) !important;
        }
    </style>

    @yield('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/">Sistema de Pedidos</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">Inicio</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->is('categorias*') ? 'active' : '' }}" href="#" id="categoriasDropdown" role="button" data-bs-toggle="dropdown">
                            Categorías
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="categoriasDropdown">
                            <li><a class="dropdown-item" href="{{ route('categorias.index') }}"><strong>Ver todas las categorías</strong></a></li>
                            <li><hr class="dropdown-divider"></li>
                            @foreach($categorias ?? [] as $categoria)
                                <li><a class="dropdown-item" href="{{ route('categorias.show', $categoria->slug) }}">{{ $categoria->nombre }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('productos*') ? 'active' : '' }}" href="{{ route('productos.index') }}" class="nav-link">Productos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('pedidos*') ? 'active' : '' }}" href="{{ route('pedidos.index') }}">Pedidos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('pagos*') ? 'active' : '' }}" href="{{ route('pagos.index') }}">Pagos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('contacto*') ? 'active' : '' }}" href="{{ route('contacto') }}">Contacto</a>
                    </li>
                    @if(auth()->check() && auth()->user()->is_admin == 1)
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin*') ? 'active' : '' }} text-danger fw-bold" href="{{ url('/admin') }}">
                            <i class="fas fa-lock"></i> Administración
                        </a>
                    </li>
                    @endif
                </ul>
                <div class="d-flex">
                    @if(auth()->check())
                        <a href="{{ route('carrito') }}" class="btn btn-outline-light me-2">
                            <i class="fas fa-shopping-cart"></i> Carrito
                        </a>
                        @if(auth()->user()->is_admin == 1)
                            <a href="{{ url('/admin') }}" class="btn btn-danger me-2">
                                <i class="fas fa-lock"></i> Admin
                            </a>
                        @endif
                        <div class="dropdown">
                            <button class="btn btn-outline-light dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> {{ auth()->user()->nombre ?? auth()->user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('perfil.index') }}">Mi Perfil</a></li>
                                <li><a class="dropdown-item" href="{{ route('pedidos.index') }}">Mis Pedidos</a></li>
                                @if(auth()->user()->is_admin == 1)
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ url('/admin') }}">Panel de Administración</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('logout') }}">Cerrar Sesión</a></li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-light me-2">Iniciar Sesión</a>
                        <a href="{{ route('registro') }}" class="btn btn-light">Registrarse</a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5>Sistema de Pedidos</h5>
                </div>
                <div class="col-md-3 mb-4">
                    <h5>Contacto</h5>
                </div>
            </div>
        </div>
        <div class="footer-copyright text-center">
            <div class="container">
                Sistema de Pedidos By: Keury David Deschamps Lopez
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
