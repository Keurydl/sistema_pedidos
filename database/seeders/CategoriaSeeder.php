<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            [
                'nombre' => 'Smartphones',
                'descripcion' => 'Teléfonos inteligentes de diversas marcas y modelos',
                'slug' => 'smartphones',
                'imagen' => 'assets/img/categories/smartphones.png'
            ],
            [
                'nombre' => 'Relojes',
                'descripcion' => 'Relojes inteligentes y tradicionales',
                'slug' => 'relojes',
                'imagen' => 'assets/img/categories/relojes.png'
            ],
            // Add more categories as needed
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}