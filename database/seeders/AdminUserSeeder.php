<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin009',
            'email' => 'admin009@sistema.com',
            'password' => Hash::make('1MainPas$'),
            'is_admin' => true,
        ]);
    }
}