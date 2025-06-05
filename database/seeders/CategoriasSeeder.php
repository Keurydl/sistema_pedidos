<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Categoria::create([
            'nombre' => 'Electrónicos',
            'descripcion' => 'Productos electrónicos y gadgets'
        ]);

        Categoria::create([
            'nombre' => 'Ropa',
            'descripcion' => 'Ropa y accesorios de moda'
        ]);

        Categoria::create([
            'nombre' => 'Hogar',
            'descripcion' => 'Artículos para el hogar'
        ]);
    }
}