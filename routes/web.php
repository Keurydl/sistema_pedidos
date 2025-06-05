<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\AdminProductoController;

// Home route
Route::get('/', function () {
    $productos = \App\Models\Producto::latest()->take(8)->get();
    $categorias = \App\Models\Categoria::all();

    return view('home', compact('productos', 'categorias'));
})->name('home');

// Rutas para productos
Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
Route::get('/productos/{id}', [ProductoController::class, 'show'])->name('productos.show');
Route::get('/producto/{id}', [ProductoController::class, 'show'])->name('producto.detalle');

// Rutas para categorías
Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias.index');
Route::get('/categorias/{slug}', [CategoriaController::class, 'show'])->name('categorias.show');
Route::get('/categoria/{id}', [CategoriaController::class, 'show'])->name('categoria.show');

// Rutas para carrito y checkout
Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito');
Route::post('/carrito/agregar', [CarritoController::class, 'agregar'])->name('carrito.agregar');
Route::post('/carrito/actualizar', [CarritoController::class, 'actualizar'])->name('carrito.actualizar');
Route::post('/carrito/eliminar', [CarritoController::class, 'eliminar'])->name('carrito.eliminar');
Route::post('/carrito/vaciar', [CarritoController::class, 'vaciar'])->name('carrito.vaciar');
Route::get('/carrito/checkout', [CarritoController::class, 'checkout'])->name('carrito.checkout');

// Rutas para pedidos - Apply auth middleware here
Route::middleware(['auth'])->group(function () {
    Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
    Route::get('/pedidos/{id}', [PedidoController::class, 'show'])->name('pedidos.show');
    Route::get('/mis-pedidos', [PedidoController::class, 'misPedidos'])->name('mis.pedidos');
    Route::post('/pedidos', [PedidoController::class, 'store'])->name('pedidos.store');
    Route::delete('/pedidos/{id}', [PedidoController::class, 'destroy'])->name('pedidos.destroy');
});

// Rutas para pagos
Route::middleware(['auth'])->group(function () {
    Route::get('/pagos', [PagoController::class, 'index'])->name('pagos.index');
    Route::get('/pagos/{id}', [PagoController::class, 'show'])->name('pagos.show'); // Re-added this line
    Route::post('/pagos/procesar', [PagoController::class, 'procesarPago'])->name('pagos.procesar');
    Route::delete('/pagos/{id}', [PagoController::class, 'destroy'])->name('pagos.destroy');
});

// Temporary route to make a user admin (remove this in production)
Route::get('/make-admin', function() {
    $user = auth()->user();
    if ($user) {
        $user->is_admin = 1;
        $user->save();
        return redirect('/')->with('success', 'You are now an admin!');
    }
    return redirect('/login')->with('error', 'You need to be logged in.');
})->middleware('auth');

// Add this route after your make-admin route
Route::get('/check-admin', function() {
    if (!auth()->check()) {
        return 'Not logged in. Please log in first.';
    }
    
    $user = auth()->user();
    return 'User: ' . $user->email . ', is_admin value: ' . ($user->is_admin ?? 'null');
})->middleware('auth');

// Ruta para contacto
Route::get('/contacto', [App\Http\Controllers\ContactoController::class, 'index'])->name('contacto');
Route::post('/contacto/enviar', [App\Http\Controllers\ContactoController::class, 'enviar'])->name('contacto.enviar');

// Ruta para about
Route::get('/about', function() {
    return view('about');
})->name('about');

// Ruta para perfil
Route::middleware(['auth'])->group(function () {
    Route::get('/perfil', [App\Http\Controllers\PerfilController::class, 'index'])->name('perfil.index');
    Route::get('/perfil/editar', [App\Http\Controllers\PerfilController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil', [App\Http\Controllers\PerfilController::class, 'update'])->name('perfil.update');
    Route::get('/perfil/cambiar-password', [App\Http\Controllers\PerfilController::class, 'showChangePasswordForm'])->name('perfil.change-password');
    Route::post('/perfil/cambiar-password', [App\Http\Controllers\PerfilController::class, 'changePassword']);
});

// Rutas de autenticación
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/registro', function () {
    return view('auth.registro');
})->name('registro');
Route::post('/registro', [AuthController::class, 'registro'])->name('registro.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas para recuperación de contraseña
Route::get('/password/reset', function() {
    return view('auth.passwords.email');
})->name('password.request');
Route::post('/password/email', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [AuthController::class, 'reset'])->name('password.update');

// Admin routes
Route::prefix('admin')->middleware(['auth'])->group(function () {
    // Admin dashboard
    Route::get('/', function() {
        // Check if user is admin - Fix the condition
        if (auth()->user()->is_admin != 1) {
            // Add debugging to see what's happening
            dd('User is not admin. is_admin value: ' . auth()->user()->is_admin);
            return redirect('/')->with('error', 'No tienes permisos para acceder a esta sección.');
        }
        
        $totalProductos = \App\Models\Producto::count();
        $totalCategorias = \App\Models\Categoria::count();
        $totalUsuarios = \App\Models\User::count();
        
        return view('admin.dashboard', compact(
            'totalProductos',
            'totalCategorias',
            'totalUsuarios'
        ));
    })->name('admin.dashboard');
    
    // Products management routes
    Route::get('/productos', function() {
        // Check if user is admin
        if (auth()->user()->is_admin != 1) {
            return redirect('/')->with('error', 'No tienes permisos para acceder a esta sección.');
        }
        
        $productos = \App\Models\Producto::with('categoria')->get();
        return view('admin.productos.index', compact('productos'));
    })->name('admin.productos.index');
    
    // Add product form
    Route::get('/productos/crear', function() {
        // Check if user is admin
        if (auth()->user()->is_admin != 1) {
            return redirect('/')->with('error', 'No tienes permisos para acceder a esta sección.');
        }
        
        $categorias = \App\Models\Categoria::all();
        return view('admin.productos.create', compact('categorias'));
    })->name('admin.productos.create');
    
    // Store new product
    Route::post('/productos', function(\Illuminate\Http\Request $request) {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $producto = new \App\Models\Producto();
        $producto->nombre = $request->nombre;
        $producto->descripcion = $request->descripcion;
        $producto->precio = $request->precio;
        $producto->stock = $request->stock;
        $producto->categoria_id = $request->categoria_id;
        $producto->slug = \Illuminate\Support\Str::slug($request->nombre);

        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
            $imagen->move(public_path('img/productos'), $nombreImagen);
            $producto->imagen = 'img/productos/' . $nombreImagen;
        } else {
            $producto->imagen = 'img/productos/default.jpg';
        }

        $producto->save();

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto creado correctamente.');
    })->name('admin.productos.store');
    
    // Edit product form
    Route::get('/productos/{id}/editar', function($id) {
        // Check if user is admin
        if (auth()->user()->is_admin != 1) {
            return redirect('/')->with('error', 'No tienes permisos para acceder a esta sección.');
        }
        
        $producto = \App\Models\Producto::findOrFail($id);
        $categorias = \App\Models\Categoria::all();
        return view('admin.productos.edit', compact('producto', 'categorias'));
    })->name('admin.productos.edit');
    
    // Update product
    Route::put('/productos/{id}', function(\Illuminate\Http\Request $request, $id) {
        $producto = \App\Models\Producto::findOrFail($id);
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $producto->nombre = $request->nombre;
        $producto->descripcion = $request->descripcion;
        $producto->precio = $request->precio;
        $producto->stock = $request->stock;
        $producto->categoria_id = $request->categoria_id;
        $producto->slug = \Illuminate\Support\Str::slug($request->nombre);

        if ($request->hasFile('imagen')) {
            // Delete old image if it's not the default
            if ($producto->imagen && $producto->imagen != 'img/productos/default.jpg' && file_exists(public_path($producto->imagen))) {
                unlink(public_path($producto->imagen));
            }
            
            $imagen = $request->file('imagen');
            $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
            $imagen->move(public_path('img/productos'), $nombreImagen);
            $producto->imagen = 'img/productos/' . $nombreImagen;
        }

        $producto->save();

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    })->name('admin.productos.update');
    
    // Delete product
    Route::delete('/productos/{id}', function($id) {
        $producto = \App\Models\Producto::findOrFail($id);
        
        // Check if product is in any order
        $enPedidos = \DB::table('detalle_pedidos')->where('producto_id', $id)->count();
        if ($enPedidos > 0) {
            return redirect()->route('admin.productos.index')
                ->with('error', 'No se puede eliminar este producto porque está en pedidos.');
        }
        
        // Delete product image if it's not the default
        if ($producto->imagen && $producto->imagen != 'img/productos/default.jpg' && file_exists(public_path($producto->imagen))) {
            unlink(public_path($producto->imagen));
        }
        
        $producto->delete();

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto eliminado correctamente.');
    })->name('admin.productos.destroy');
    
    // User management routes
    Route::get('/usuarios', function() {
        // Check if user is admin
        if (auth()->user()->is_admin != 1) {
            return redirect('/')->with('error', 'No tienes permisos para acceder a esta sección.');
        }
        
        $usuarios = \App\Models\User::all();
        return view('admin.usuarios.index', compact('usuarios'));
    })->name('admin.usuarios.index');
    
    // Edit user form
    Route::get('/usuarios/{id}/editar', function($id) {
        // Check if user is admin
        if (auth()->user()->is_admin != 1) {
            return redirect('/')->with('error', 'No tienes permisos para acceder a esta sección.');
        }
        
        $usuario = \App\Models\User::findOrFail($id);
        return view('admin.usuarios.edit', compact('usuario'));
    })->name('admin.usuarios.edit');
    
    // Update user
    Route::put('/usuarios/{id}', function(\Illuminate\Http\Request $request, $id) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$id,
            'is_admin' => 'required|boolean',
        ]);

        $usuario = \App\Models\User::findOrFail($id);
        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->is_admin = $request->is_admin;
        $usuario->save();

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    })->name('admin.usuarios.update');
    
    // Delete user
    Route::delete('/usuarios/{id}', function($id) {
        // Prevent deleting yourself
        if (auth()->id() == $id) {
            return redirect()->route('admin.usuarios.index')
                ->with('error', 'No puedes eliminar tu propio usuario.');
        }
        
        $usuario = \App\Models\User::findOrFail($id);
        
        // Check if user has orders
        $pedidos = \DB::table('pedidos')->where('user_id', $id)->count();
        if ($pedidos > 0) {
            // Option 1: Prevent deletion
            return redirect()->route('admin.usuarios.index')
                ->with('error', 'No se puede eliminar este usuario porque tiene pedidos asociados.');
            
            // Option 2: Delete related records (uncomment if you want to allow deletion)
            // \DB::table('detalle_pedidos')
            //     ->whereIn('pedido_id', function($query) use ($id) {
            //         $query->select('id')->from('pedidos')->where('user_id', $id);
            //     })
            //     ->delete();
            // \DB::table('pedidos')->where('user_id', $id)->delete();
        }
        
        $usuario->delete();

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario eliminado correctamente.');
    })->name('admin.usuarios.destroy');

    // Category management routes
    Route::get('/categorias', function() {
        // Check if user is admin
        if (auth()->user()->is_admin != 1) {
            return redirect('/')->with('error', 'No tienes permisos para acceder a esta sección.');
        }
        
        $categorias = \App\Models\Categoria::all();
        return view('admin.categorias.index', compact('categorias'));
    })->name('admin.categorias.index');
    
    // Add category form
    Route::get('/categorias/crear', function() {
        // Check if user is admin
        if (auth()->user()->is_admin != 1) {
            return redirect('/')->with('error', 'No tienes permisos para acceder a esta sección.');
        }
        
        return view('admin.categorias.create');
    })->name('admin.categorias.create');
    
    // Store new category
    Route::post('/categorias', function(\Illuminate\Http\Request $request) {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:categorias,nombre',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $categoria = new \App\Models\Categoria();
        $categoria->nombre = $request->nombre;
        $categoria->descripcion = $request->descripcion;
        $categoria->slug = \Illuminate\Support\Str::slug($request->nombre);

        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
            $imagen->move(public_path('img/categorias'), $nombreImagen);
            $categoria->imagen = 'img/categorias/' . $nombreImagen;
        } else {
            $categoria->imagen = 'img/categorias/default.jpg';
        }

        $categoria->save();

        return redirect()->route('admin.categorias.index')
            ->with('success', 'Categoría creada correctamente.');
    })->name('admin.categorias.store');
    
    // Edit category form
    Route::get('/categorias/{id}/editar', function($id) {
        // Check if user is admin
        if (auth()->user()->is_admin != 1) {
            return redirect('/')->with('error', 'No tienes permisos para acceder a esta sección.');
        }
        
        $categoria = \App\Models\Categoria::findOrFail($id);
        return view('admin.categorias.edit', compact('categoria'));
    })->name('admin.categorias.edit');
    
    // Update category
    Route::put('/categorias/{id}', function(\Illuminate\Http\Request $request, $id) {
        $categoria = \App\Models\Categoria::findOrFail($id);
        
        $request->validate([
            'nombre' => 'required|string|max:255|unique:categorias,nombre,'.$id,
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $categoria->nombre = $request->nombre;
        $categoria->descripcion = $request->descripcion;
        $categoria->slug = \Illuminate\Support\Str::slug($request->nombre);

        if ($request->hasFile('imagen')) {
            // Delete old image if it's not the default
            if ($categoria->imagen && $categoria->imagen != 'img/categorias/default.jpg' && file_exists(public_path($categoria->imagen))) {
                unlink(public_path($categoria->imagen));
            }
            
            $imagen = $request->file('imagen');
            $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
            $imagen->move(public_path('img/categorias'), $nombreImagen);
            $categoria->imagen = 'img/categorias/' . $nombreImagen;
        }

        $categoria->save();

        return redirect()->route('admin.categorias.index')
            ->with('success', 'Categoría actualizada correctamente.');
    })->name('admin.categorias.update');
    
    // Delete category
    Route::delete('/categorias/{id}', function($id) {
        $categoria = \App\Models\Categoria::findOrFail($id);
        
        // Check if category has products
        $productosCount = \App\Models\Producto::where('categoria_id', $id)->count();
        if ($productosCount > 0) {
            return redirect()->route('admin.categorias.index')
                ->with('error', 'No se puede eliminar esta categoría porque tiene productos asociados.');
        }
        
        // Delete category image if it's not the default
        if ($categoria->imagen && $categoria->imagen != 'img/categorias/default.jpg' && file_exists(public_path($categoria->imagen))) {
            unlink(public_path($categoria->imagen));
        }
        
        $categoria->delete();

        return redirect()->route('admin.categorias.index')
            ->with('success', 'Categoría eliminada correctamente.');
    })->name('admin.categorias.destroy');
}); // End of admin routes

// Public routes for categories
Route::get('/categorias', function() {
    $categorias = \App\Models\Categoria::all();
    return view('categorias.index', compact('categorias'));
})->name('categorias.index');
