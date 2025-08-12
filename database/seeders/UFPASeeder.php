<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ufpa;
use App\Models\Person;

class UFPASeeder extends Seeder
{
    public function run(): void
    {
        $pessoas = Person::all();

        foreach ($pessoas as $pessoa) {
            Ufpa::create([
                'person_id'        => $pessoa->id,
                'nome_propriedade' => 'Propriedade de ' . $pessoa->nome,
                'area_total'       => rand(5, 50),
                'localizacao'      => 'Zona Rural - ' . $pessoa->municipio . '/' . $pessoa->uf,
                'matricula'        => str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT),
                'nirf'             => str_pad(rand(1, 999999999), 9, '0', STR_PAD_LEFT),
                'ccir'             => str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT),
                'car'              => strtoupper(bin2hex(random_bytes(4))),
                'tipo_posse'       => ['própria', 'arrendada'][array_rand(['própria', 'arrendada'])],
                'created_by'       => 1,
            ]);
        }
    }
}

