<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin fixo
        User::create([
            'name'      => 'Administrador',
            'email'     => 'admin@admin.com',
            'password'  => Hash::make('admin123'),
            'role'      => 'admin',
            'is_active' => true,
            'created_by'=> null,
        ]);

        // Funcionários fixos
        $funcionarios = [
            ['Funcionário UM', 'user01@user.com'],
            ['Funcionária dois', 'use02@user.com'],
            ['Funcionário Três', 'user03@user.com'],
        ];

        foreach ($funcionarios as [$nome, $email]) {
            User::create([
                'name'      => $nome,
                'email'     => $email,
                'password'  => Hash::make('user123'),
                'role'      => 'funcionario',
                'is_active' => true,
                'created_by'=> 1, // criado pelo admin
            ]);
        }
    }
}

