<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends Factory<\App\Models\Person>
 */
class PersonFactory extends Factory
{
    public function definition(): array
    {
        $faker = \Faker\Factory::create('pt_BR');

        return [
            'nome'         => $faker->name(),
            'cpf'          => preg_replace('/\D/', '', $faker->cpf(false)),
            'rg'           => $faker->numerify('########') . '/' . $faker->randomElement(['PA', 'AP', 'AM']),
            'data_nascimento'=> $faker->dateTimeBetween('1950-01-01'),
            'naturalidade' => $faker->randomElement(['PA', 'AP', 'AM']),
            'endereco_correspondencia'=> $faker->streetAddress(),
            'bairro'       => 'Bairro Teste',
            'cep'          => '12345-678',
            'nome_mae'     => $faker->name(),
            'endereco'     => $faker->streetAddress(),
            'cidade'    => $faker->city(),
            'uf'           => $faker->randomElement(['PA', 'AP', 'AM']),
            'email'        => $faker->unique()->safeEmail(),
            'telefone'     => $faker->phoneNumber(),
            'estado_civil' => $faker->randomElement(['solteiro', 'casado', 'divorciado', 'viuvo']),
            'created_by'   => User::inRandomOrder()->first()?->id ?? 1,
        ];
    }
}
