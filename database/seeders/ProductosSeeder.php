<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Categoria;

class ProductosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asegúrate de que existe la categoría de smartphones
        $categoria = Categoria::firstOrCreate(
            ['slug' => 'smartphones'],
            [
                'nombre' => 'Smartphones',
                'descripcion' => 'Teléfonos inteligentes de diversas marcas y modelos'
            ]
        );

        // Crear el iPhone 15 Pro
        Producto::create([
            'nombre' => 'iPhone 15 Pro',
            'descripcion' => 'El iPhone 15 Pro es el smartphone más avanzado de Apple. Cuenta con un potente chip A17 Pro, pantalla Super Retina XDR de 6.1 pulgadas, sistema de cámara Pro de 48MP, y está fabricado en titanio para mayor durabilidad.',
            'precio' => 999.99,
            'stock' => 10,
            'imagen' => '/img/productos/iphone15pro.jpg',
            'categoria_id' => $categoria->id
        ]);

        // Crear el iPhone 15 Pro Max
        Producto::create([
            'nombre' => 'iPhone 15 Pro Max',
            'descripcion' => 'El iPhone 15 Pro Max ofrece la pantalla más grande de Apple con 6.7 pulgadas Super Retina XDR, chip A17 Pro, sistema de cámara Pro de 48MP con capacidades de zoom mejoradas, y construcción en titanio. La mejor experiencia iPhone para los usuarios más exigentes.',
            'precio' => 1199.99,
            'stock' => 8,
            'imagen' => '/img/productos/iphone15promax.jpg',
            'categoria_id' => $categoria->id
        ]);
    }
}