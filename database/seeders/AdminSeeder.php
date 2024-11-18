<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'pcr-administracion@paseocomerciallasrosas.com',
            'password' => bcrypt('paseolasrosas24'), // Encripta la contraseña
            'is_admin' => true, // Especificar que es administrador
        ]);
    }
}
