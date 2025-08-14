<?php

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cliente>
 */
class ClienteFactory extends Factory
{
    protected $model = Cliente::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sexo = $this->faker->randomElement(['M', 'F']);
        $estadoCivil = $this->faker->randomElement(['solteiro', 'casado', 'união estável', 'divorciado', 'viúvo']);
        $isCasado = in_array($estadoCivil, ['casado', 'união estável']);

        return [
            'nome_completo' => $this->faker->name(),
            'cpf' => $this->generateCpf(),
            'rg' => $this->faker->numerify('#######'),
            'orgao_expedidor' => $this->faker->randomElement(['SSP', 'PC', 'PM']),
            'data_nascimento' => $this->faker->dateTimeBetween('-80 years', '-18 years'),
            'local_nascimento' => $this->faker->city(),
            'estado_civil' => $estadoCivil,
            'nome_mae' => $this->faker->name('female'),
            'apelido' => $this->faker->firstName(),
            'cor_raca' => $this->faker->randomElement(['branco', 'pardo', 'preto', 'amarelo', 'indígena']),
            'sexo' => $sexo,
            'escolaridade' => $this->faker->randomElement([
                'Ensino Fundamental Incompleto',
                'Ensino Fundamental Completo',
                'Ensino Médio Incompleto',
                'Ensino Médio Completo',
                'Superior Incompleto',
                'Superior Completo'
            ]),

            // Contato
            'telefone' => $this->faker->cellphone(),
            'email' => $this->faker->email(),

            // Endereço
            'endereco' => $this->faker->streetAddress(),
            'numero' => $this->faker->buildingNumber(),
            'complemento' => $this->faker->optional()->secondaryAddress(),
            'bairro' => $this->faker->randomElement(['Centro', 'Santa Rita', 'Zona Norte', 'Infraero', 'Jesus de Nazaré']),
            'municipio' => $this->faker->randomElement(['Breves', 'Afuá', 'Macapá', 'Santana', 'Chaves']),
            'uf' => $this->faker->randomElement(['PA', 'AP']),
            'cep' => $this->faker->postcode(),

            // Endereço de correspondência (às vezes diferente)
            'endereco_correspondencia_diferente' => $this->faker->boolean(30),
            'endereco_correspondencia' => $this->faker->optional(0.3)->streetAddress(),
            'numero_correspondencia' => $this->faker->optional(0.3)->buildingNumber(),
            'bairro_correspondencia' => $this->faker->optional(0.3)->randomElement(['Centro', 'Santa Rita', 'Zona Norte']),
            'municipio_correspondencia' => $this->faker->optional(0.3)->randomElement(['Macapá', 'Santana']),
            'uf_correspondencia' => $this->faker->optional(0.3)->stateAbbr(),
            'cep_correspondencia' => $this->faker->optional(0.3)->postcode(),

            // Dados do cônjuge (se casado)
            'nome_conjuge' => $isCasado ? $this->faker->name($sexo === 'M' ? 'female' : 'male') : null,
            'cpf_conjuge' => $isCasado ? $this->generateCpf() : null,
            'rg_conjuge' => $isCasado ? $this->faker->numerify('#######') : null,
            'data_nascimento_conjuge' => $isCasado ? $this->faker->dateTimeBetween('-80 years', '-18 years') : null,
            'local_nascimento_conjuge' => $isCasado ? $this->faker->city() : null,
            'apelido_conjuge' => $isCasado ? $this->faker->firstName() : null,
            'cor_raca_conjuge' => $isCasado ? $this->faker->randomElement(['branco', 'pardo', 'preto', 'amarelo', 'indígena']) : null,
            'escolaridade_conjuge' => $isCasado ? $this->faker->randomElement([
                'Ensino Fundamental Incompleto',
                'Ensino Fundamental Completo',
                'Ensino Médio Incompleto',
                'Ensino Médio Completo'
            ]) : null,

            // Projeto/Assentamento
            'projeto_assentamento' => $this->faker->randomElement([
                'PA0485000 - PAE ILHA CONCEIÇÃO I',
                'PA0487000 - PAE ILHA QUEIMADA',
                'PA0486000 - PAE MAPARI',
                'PA0484000 - PAE ANAJÁ-MIRIM'
            ]),
            'codigo_sipra' => $this->faker->numerify('PA####'),
            'beneficiario_credito_fundiario' => $this->faker->boolean(80),
            'gestor_ufpa' => $this->faker->boolean(90),
            'mao_obra_ufpa' => $this->faker->boolean(95),
            'assentado_reforma_agraria' => $this->faker->boolean(85),

            // Localização
            'area_indigena_quilombola' => $this->faker->boolean(10),
            'pescador_artesanal' => $this->faker->boolean(90),
            'tipo_area' => $this->faker->randomElement(['Terra', 'Lâmina de água', 'Tanque']),
            'medida_area' => 'ha',
            'area_total' => $this->faker->randomFloat(2, 1, 50),
            'area_explorada' => $this->faker->randomFloat(2, 0.5, 20),
            'coordenada_latitude' => $this->faker->latitude(-2, 2),
            'coordenada_longitude' => $this->faker->longitude(-55, -45),

            // Renda
            'renda_bruta_estabelecimento' => $this->faker->randomFloat(2, 5000, 80000),
            'renda_fora_estabelecimento' => $this->faker->randomFloat(2, 0, 15000),
            'beneficios_sociais' => $this->faker->randomFloat(2, 0, 12000),

            // Mão de obra
            'pessoas_familia_residentes' => $this->faker->numberBetween(1, 8),
            'pessoas_familia_trabalham' => $this->faker->numberBetween(1, 6),
            'empregados_permanentes' => $this->faker->numberBetween(0, 3),

            // Atividade
            'atividade_principal' => $this->faker->randomElement([
                'Pesca artesanal',
                'Açaí',
                'Mandioca',
                'Criação de búfalos',
                'Fruticultura'
            ]),
            'outras_atividades' => $this->faker->optional()->sentence(),
            'periodo_atividade_inicio' => $this->faker->dateTimeBetween('-10 years', '-1 year'),
            'periodo_atividade_fim' => null, // Atividade atual
            'condicao_imovel' => $this->faker->randomElement([
                'Proprietário', 'Possuidor', 'Posseiro', 'Assentado', 'Arrendatário'
            ]),
            'situacao_atividade' => $this->faker->randomElement([
                'Individualmente',
                'Regime de economia familiar'
            ]),

            // Banco
            'banco_agencia' => $this->faker->randomElement(['Macapá', 'Santana', 'Breves']),
            'conta_corrente' => $this->faker->numerify('########'),

            // Outros
            'pessoa_exposta_politicamente' => $this->faker->boolean(5),
            'observacoes' => $this->faker->optional()->sentence(),
            'ativo' => $this->faker->boolean(95),
        ];
    }

    /**
     * Cliente solteiro
     */
    public function solteiro(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado_civil' => 'solteiro',
            'nome_conjuge' => null,
            'cpf_conjuge' => null,
            'rg_conjuge' => null,
            'data_nascimento_conjuge' => null,
            'local_nascimento_conjuge' => null,
            'apelido_conjuge' => null,
            'cor_raca_conjuge' => null,
            'escolaridade_conjuge' => null,
        ]);
    }

    /**
     * Cliente casado
     */
    public function casado(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado_civil' => $this->faker->randomElement(['casado', 'união estável']),
            'nome_conjuge' => $this->faker->name($attributes['sexo'] === 'M' ? 'female' : 'male'),
            'cpf_conjuge' => $this->generateCpf(),
            'rg_conjuge' => $this->faker->numerify('#######'),
            'data_nascimento_conjuge' => $this->faker->dateTimeBetween('-80 years', '-18 years'),
            'local_nascimento_conjuge' => $this->faker->city(),
            'apelido_conjuge' => $this->faker->firstName(),
            'cor_raca_conjuge' => $this->faker->randomElement(['branco', 'pardo', 'preto', 'amarelo', 'indígena']),
            'escolaridade_conjuge' => $this->faker->randomElement([
                'Ensino Fundamental Incompleto',
                'Ensino Fundamental Completo',
                'Ensino Médio Incompleto',
                'Ensino Médio Completo'
            ]),
        ]);
    }

    /**
     * Cliente pescador artesanal
     */
    public function pescadorArtesanal(): static
    {
        return $this->state(fn (array $attributes) => [
            'pescador_artesanal' => true,
            'atividade_principal' => 'Pesca artesanal',
            'tipo_area' => 'Lâmina de água',
            'condicao_imovel' => 'Posseiro',
            'situacao_atividade' => 'Regime de economia familiar',
        ]);
    }

    /**
     * Cliente com renda alta
     */
    public function comRendaAlta(): static
    {
        return $this->state(fn (array $attributes) => [
            'renda_bruta_estabelecimento' => $this->faker->randomFloat(2, 40000, 150000),
            'renda_fora_estabelecimento' => $this->faker->randomFloat(2, 10000, 30000),
            'beneficios_sociais' => $this->faker->randomFloat(2, 5000, 15000),
        ]);
    }

    /**
     * Cliente com renda baixa
     */
    public function comRendaBaixa(): static
    {
        return $this->state(fn (array $attributes) => [
            'renda_bruta_estabelecimento' => $this->faker->randomFloat(2, 3000, 15000),
            'renda_fora_estabelecimento' => $this->faker->randomFloat(2, 0, 5000),
            'beneficios_sociais' => $this->faker->randomFloat(2, 0, 8000),
        ]);
    }

    /**
     * Cliente do Pará
     */
    public function doPara(): static
    {
        return $this->state(fn (array $attributes) => [
            'uf' => 'PA',
            'municipio' => $this->faker->randomElement(['Breves', 'Afuá', 'Chaves', 'Anajás']),
            'projeto_assentamento' => $this->faker->randomElement([
                'PA0485000 - PAE ILHA CONCEIÇÃO I',
                'PA0487000 - PAE ILHA QUEIMADA',
            ]),
        ]);
    }

    /**
     * Cliente do Amapá
     */
    public function doAmapa(): static
    {
        return $this->state(fn (array $attributes) => [
            'uf' => 'AP',
            'municipio' => $this->faker->randomElement(['Macapá', 'Santana', 'Oiapoque']),
        ]);
    }

    /**
     * Cliente inativo
     */
    public function inativo(): static
    {
        return $this->state(fn (array $attributes) => [
            'ativo' => false,
        ]);
    }

    /**
     * Gerar CPF válido
     */
    protected function generateCpf(): string
    {
        $cpf = '';

        // Gerar os primeiros 9 dígitos
        for ($i = 0; $i < 9; $i++) {
            $cpf .= random_int(0, 9);
        }

        // Calcular primeiro dígito verificador
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += $cpf[$i] * (10 - $i);
        }
        $remainder = $sum % 11;
        $cpf .= ($remainder < 2) ? '0' : (string)(11 - $remainder);

        // Calcular segundo dígito verificador
        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += $cpf[$i] * (11 - $i);
        }
        $remainder = $sum % 11;
        $cpf .= ($remainder < 2) ? '0' : (string)(11 - $remainder);

        return $cpf;
    }
}

/*
Exemplos de uso da Factory:

1. Criar cliente básico:
   Cliente::factory()->create()

2. Criar cliente solteiro:
   Cliente::factory()->solteiro()->create()

3. Criar cliente casado:
   Cliente::factory()->casado()->create()

4. Criar pescador artesanal do Pará:
   Cliente::factory()->pescadorArtesanal()->doPara()->create()

5. Criar cliente com renda alta:
   Cliente::factory()->comRendaAlta()->create()

6. Criar múltiplos clientes:
   Cliente::factory()->count(50)->create()

7. Criar mix de clientes:
   Cliente::factory()->count(10)->solteiro()->create()
   Cliente::factory()->count(15)->casado()->create()
   Cliente::factory()->count(5)->pescadorArtesanal()->create()

8. Para testes:
   $cliente = Cliente::factory()->make() // Não salva no DB
   $cliente = Cliente::factory()->create() // Salva no DB
*/
