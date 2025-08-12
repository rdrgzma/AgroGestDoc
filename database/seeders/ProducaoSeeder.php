<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producao;
use App\Models\Ufpa;

class ProducaoSeeder extends Seeder
{
    public function run(): void
    {
        $produtos = [
            'Milho',
            'Feijão',
            'Mandioca',
            'Açaí',
            'Cacau',
            'Pecuária Leiteira',
            'Avicultura',
            'Hortaliças',
        ];

        $unidades = ['toneladas', 'sacas', 'litros', 'kg'];

        foreach (Ufpa::all() as $ufpa) {
            // Cada UFPA terá entre 1 e 3 produções
            $qtd = rand(1, 3);
            for ($i = 0; $i < $qtd; $i++) {
                Producao::create([
                    'ufpa_id'    => $ufpa->id,
                    'produto'    => $produtos[array_rand($produtos)],
                    'quantidade' => rand(1, 50),
                    'unidade'    => $unidades[array_rand($unidades)],
                    'ano'        => now()->year - rand(0, 3),
                    'created_by' => 1,
                ]);
            }
        }
    }
}

