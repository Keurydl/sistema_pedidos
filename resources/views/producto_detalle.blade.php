<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $producto->nombre }} - Sistema de Pedidos</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .product-card {
            transition: transform 0.3s;
            margin-bottom: 20px;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            font-weight: bold;
        }
        footer {
            background-color: #343a40;
            color: white;
            padding: 30px 0;
            margin-top: 50px;
        }
        .product-image {
            max-height: 400px;
            object-fit: contain;
        }
    </style>
</head>
<body>
    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/">Sistema de Pedidos</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Inicio</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            Categorías
                        </a>
                        <ul class="dropdown-menu">
                            @foreach($categorias as $categoria)
                                <li><a class="dropdown-item" href="{{ route('productos', ['categoria_id' => $categoria->id]) }}">{{ $categoria->nombre }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('productos') }}">Productos</a>
                    </li>
                    @if($usuario_logueado)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('mis.pedidos') }}">Mis Pedidos</a>
                    </li>
                    @endif
                    @if($es_admin)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown">
                            Administración
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('admin.productos') }}">Gestionar Productos</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.categorias') }}">Gestionar Categorías</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.pedidos') }}">Gestionar Pedidos</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.usuarios') }}">Gestionar Usuarios</a></li>
                        </ul>
                    </li>
                    @endif
                </ul>
                <div class="d-flex">
                    @if($usuario_logueado)
                        <a href="{{ route('carrito') }}" class="btn btn-outline-light me-2">
                            <i class="fas fa-shopping-cart"></i> Carrito
                        </a>
                        <div class="dropdown">
                            <button class="btn btn-outline-light dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> {{ auth()->user()->nombre }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="/perfil">Mi Perfil</a></li>
                                <li><a class="dropdown-item" href="{{ route('mis.pedidos') }}">Mis Pedidos</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/logout">Cerrar Sesión</a></li>
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
    <div class="container mt-4">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('productos') }}">Productos</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $producto->nombre }}</li>
            </ol>
        </nav>

        <div class="row">
            <!-- Imagen del producto -->
            <div class="col-md-6">
                <div class="card">
                    <img src="{{ !empty($producto->imagen) ? asset('uploads/productos/' . $producto->imagen) : asset('assets/img/no-image.jpg') }}"
                         class="card-img-top product-image" alt="{{ $producto->nombre }}">
                </div>
            </div>

            <!-- Detalles del producto -->
            <div class="col-md-6">
                <h1 class="mb-3">{{ $producto->nombre }}</h1>
                <p class="text-muted">Categoría:
                    @if($producto->categoria)
                        <a href="{{ route('productos', ['categoria_id' => $producto->categoria_id]) }}">{{ $producto->categoria->nombre }}</a>
                    @else
                        <span>Sin categoría</span>
                    @endif
                </p>
                <h3 class="text-primary mb-3">${{ number_format($producto->precio, 2) }}</h3>

                <div class="mb-4">
                    <h5>Descripción:</h5>
                    <p>{{ $producto->descripcion }}</p>
                </div>

                <div class="mb-4">
                    <p class="mb-2">
                        <strong>Disponibilidad:</strong>
                        @if($producto->stock > 0)
                            <span class="text-success">En stock ({{ $producto->stock }} disponibles)</span>
                        @else
                            <span class="text-danger">Agotado</span>
                        @endif
                    </p>
                </div>

                @if($usuario_logueado && $producto->stock > 0)
                <div class="d-flex align-items-center mb-4">
                    <div class="input-group me-3" style="width: 130px;">
                        <button class="btn btn-outline-secondary btn-sm" type="button" id="decrementarCantidad">-</button>
                        <input type="number" class="form-control text-center" id="cantidad" value="1" min="1" max="{{ $producto->stock }}">
                        <button class="btn btn-outline-secondary btn-sm" type="button" id="incrementarCantidad">+</button>
                    </div>
                    <button class="btn btn-primary" id="agregarCarrito" data-id="{{ $producto->id }}">
                        <i class="fas fa-cart-plus me-2"></i> Agregar al carrito
                    </button>
                </div>
                @elseif(!$usuario_logueado && $producto->stock > 0)
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Debes <a href="{{ route('login') }}">iniciar sesión</a> para agregar productos al carrito.
                </div>
                @endif
            </div>
        </div>

        <!-- Productos relacionados -->
        <div class="mt-5">
            <h3 class="mb-4">Productos relacionados</h3>
            <div class="row">
                @forelse($productos_relacionados as $prod_rel)
                    <div class="col-md-3">
                        <div class="card product-card">
                            <img src="{{ !empty($prod_rel->imagen) ? asset('uploads/productos/' . $prod_rel->imagen) : asset('assets/img/no-image.jpg') }}"
                                 class="card-img-top" alt="{{ $prod_rel->nombre }}" style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title">{{ $prod_rel->nombre }}</h5>
                                <p class="card-text text-truncate">{{ $prod_rel->descripcion }}</p>
                                <p class="card-text text-primary fw-bold">${{ number_format($prod_rel->precio, 2) }}</p>
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('producto.detalle', $prod_rel->id) }}" class="btn btn-sm btn-outline-primary">Ver Detalles</a>
                                    @if($usuario_logueado)
                                    <button class="btn btn-sm btn-primary agregar-carrito" data-id="{{ $prod_rel->id }}">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info">
                            No hay productos relacionados disponibles.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>Sistema de Pedidos</h5>
                </div>
                <div class="col-md-4">
                    <h5>Contacto</h5>
                </div>
            </div>
            <hr class="bg-light">
            <div class="text-center">
                <p>Sistema de Pedidos</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script para manejo de cantidad y agregar al carrito -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Manejo de cantidad
            const decrementarBtn = document.getElementById('decrementarCantidad');
            const incrementarBtn = document.getElementById('incrementarCantidad');
            const cantidadInput = document.getElementById('cantidad');
            //const maxStock = {{ $producto->stock ?? 0 }};

            if (decrementarBtn && incrementarBtn && cantidadInput) {
                decrementarBtn.addEventListener('click', function() {
                    let cantidad = parseInt(cantidadInput.value);
                    if (cantidad > 1) {
                        cantidadInput.value = cantidad - 1;
                    }
                });

                incrementarBtn.addEventListener('click', function() {
                    let cantidad = parseInt(cantidadInput.value);
                    if (cantidad < maxStock) {
                        cantidadInput.value = cantidad + 1;
                    }
                });

                cantidadInput.addEventListener('change', function() {
                    let cantidad = parseInt(cantidadInput.value);
                    if (isNaN(cantidad) || cantidad < 1) {
                        cantidadInput.value = 1;
                    } else if (cantidad > maxStock) {
                        cantidadInput.value = maxStock;
                    }
                });
            }

            // Agregar al carrito
            const agregarCarritoBtn = document.getElementById('agregarCarrito');
            if (agregarCarritoBtn) {
                agregarCarritoBtn.addEventListener('click', function() {
                    const productoId = this.getAttribute('data-id');
                    const cantidad = parseInt(cantidadInput.value);


                    fetch('/carrito/agregar', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            producto_id: productoId,
                            cantidad: cantidad
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Producto agregado al carrito');
                        } else {
                            alert('Error al agregar el producto al carrito: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al agregar el producto al carrito');
                    });
                });
            }

            // Botones de agregar al carrito en productos relacionados
            const botonesAgregarCarrito = document.querySelectorAll('.agregar-carrito');
            botonesAgregarCarrito.forEach(boton => {
                boton.addEventListener('click', function() {
                    const productoId = this.getAttribute('data-id');

                    // Enviar solicitud AJAX para agregar al carrito
                    fetch('/carrito/agregar', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            producto_id: productoId,
                            cantidad: 1
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Producto agregado al carrito');
                        } else {
                            alert('Error al agregar el producto al carrito: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al agregar el producto al carrito');
                    });
                });
            });
        });
    </script>
</body>
</html>
