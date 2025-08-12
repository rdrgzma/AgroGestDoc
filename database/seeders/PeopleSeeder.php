<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Person;

class PeopleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cria 10 registros fixos de exemplo
        Person::factory()->count(10)->create();

        // Adiciona alguns registros manuais que podem ser usados para testes específicos
        $people = [
            [
                'nome'       => 'João da Silva',
                'cpf'        => '59656212',
                'rg'         => '148118/AP',
                'data_nascimento'=> '01/02/1980',
                'naturalidade' => 'AP',
                'nome_mae'=>'Mãe do João da Silva',
                'bairro'=>'Bairro Teste',
                'endereco'   => 'Rio Tambaqui do Vieira/PA Novo Mundo',
                'cidade'  => 'Breves',
                'uf'         => 'PA',
                'email'      => 'joao.silva@example.com',
                'telefone'   => '(91) 99999-1111',
                'estado_civil' => 'solteiro',
                'created_by' => 1,
            ],
            [
                'nome'       => 'Maria Oliveira',
                'cpf'        => '12345678',
                'rg'         => '258741/PA',
                'data_nascimento'=> '01/02/1980',
                'naturalidade' => 'PA',
                'nome_mae'=>'Mãe da Maria Oliveira',
                'bairro'=>'Bairro Teste',
                'endereco'   => 'Comunidade São Pedro, Km 12',
                'cidade'  => 'Anajás',
                'uf'         => 'PA',
                'email'      => 'maria.oliveira@example.com',
                'telefone'   => '(91) 98888-2222',
                'estado_civil' => 'casado',
                'created_by' => 1,
            ],
        ];

        foreach ($people as $pessoa) {
            Person::create($pessoa);
        }
    }
}

