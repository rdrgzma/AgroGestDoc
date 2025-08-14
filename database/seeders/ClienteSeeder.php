<?php

namespace Database\Seeders;

use App\Models\Cliente;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🌱 Populando tabela de clientes...');

        // Criar clientes específicos baseados nos documentos fornecidos
        $this->createSpecificClients();

        // Criar clientes aleatórios
        $this->createRandomClients();

        $this->command->info('✅ Clientes criados com sucesso!');
        $this->command->line('📊 Total de clientes: ' . Cliente::count());
        $this->command->line('💑 Casados: ' . Cliente::casados()->count());
        $this->command->line('👤 Solteiros: ' . Cliente::solteiros()->count());
        $this->command->line('🐟 Pescadores: ' . Cliente::pescadoresArtesanais()->count());
    }

    /**
     * Criar clientes específicos baseados nos documentos
     */
    protected function createSpecificClients(): void
    {
        $this->command->info('📄 Criando clientes baseados nos documentos...');

        // Cliente do CAF Formulário Casal
        Cliente::create([
            'nome_completo' => 'MARIA MARQUES DA SILVA',
            'cpf' => '81708793291',
            'rg' => '112030/PA',
            'orgao_expedidor' => 'SSP',
            'data_nascimento' => '1980-03-15',
            'local_nascimento' => 'Afuá',
            'estado_civil' => 'união estável',
            'nome_mae' => 'JOANA FERREIRA',
            'apelido' => 'Noca',
            'cor_raca' => 'pardo',
            'sexo' => 'F',
            'escolaridade' => 'Ensino Fundamental Completo',
            'telefone' => '96992020538',
            'email' => null,
            'endereco' => 'rio Tambaqui da Conceição',
            'numero' => 's/n',
            'bairro' => 'zona rural',
            'municipio' => 'Afuá',
            'uf' => 'PA',
            'cep' => '68890000',
            'endereco_correspondencia_diferente' => true,
            'endereco_correspondencia' => 'R. Dos Jenipapos, 539',
            'numero_correspondencia' => '539',
            'bairro_correspondencia' => 'Infraero',
            'municipio_correspondencia' => 'Macapá',
            'uf_correspondencia' => 'AP',
            'cep_correspondencia' => '68908843',
            'nome_conjuge' => 'JOSE OTACI GONCALVES',
            'cpf_conjuge' => '20863900259',
            'data_nascimento_conjuge' => '1975-08-20',
            'apelido_conjuge' => 'Paraci',
            'cor_raca_conjuge' => 'pardo',
            'projeto_assentamento' => 'PA0485000 - PAE ILHA CONCEIÇÃO I',
            'beneficiario_credito_fundiario' => true,
            'gestor_ufpa' => true,
            'mao_obra_ufpa' => true,
            'assentado_reforma_agraria' => true,
            'pescador_artesanal' => true,
            'tipo_area' => 'Lâmina de água',
            'area_total' => 15.00,
            'coordenada_latitude' => '-0.311667',
            'coordenada_longitude' => '-50.818806',
            'renda_bruta_estabelecimento' => 31200.00,
            'renda_fora_estabelecimento' => 0.00,
            'beneficios_sociais' => 9000.00,
            'atividade_principal' => 'Pesca artesanal',
            'condicao_imovel' => 'Assentado',
            'situacao_atividade' => 'Regime de economia familiar',
            'ativo' => true,
        ]);

        // Cliente do CAF Formulário Solteiro
        Cliente::create([
            'nome_completo' => 'CARINA MORAES MIRANDA',
            'cpf' => '04042431267',
            'data_nascimento' => '1985-06-12',
            'local_nascimento' => 'Afuá',
            'estado_civil' => 'solteiro',
            'apelido' => 'Carina',
            'cor_raca' => 'pardo',
            'sexo' => 'F',
            'telefone' => '91985472994',
            'email' => 'mirandakry@gmail.com',
            'endereco' => 'rio Serraria Grande',
            'numero' => 's/n',
            'bairro' => 'zona rural',
            'municipio' => 'Afuá',
            'uf' => 'PA',
            'cep' => '68890000',
            'endereco_correspondencia_diferente' => true,
            'endereco_correspondencia' => 'R. Padre Vitório Galiane, 92',
            'numero_correspondencia' => '92',
            'bairro_correspondencia' => 'Nova Esperança',
            'municipio_correspondencia' => 'Macapá',
            'uf_correspondencia' => 'AP',
            'cep_correspondencia' => '68901600',
            'projeto_assentamento' => 'PA0487000 - PAE ILHA QUEIMADA',
            'beneficiario_credito_fundiario' => true,
            'gestor_ufpa' => true,
            'mao_obra_ufpa' => true,
            'pescador_artesanal' => true,
            'tipo_area' => 'Lâmina de água',
            'area_total' => 10.00,
            'coordenada_latitude' => '-0.112738',
            'coordenada_longitude' => '-50.670917',
            'renda_bruta_estabelecimento' => 26000.00,
            'beneficios_sociais' => 9600.00,
            'atividade_principal' => 'Pesca artesanal',
            'condicao_imovel' => 'Assentado',
            'situacao_atividade' => 'Regime de economia familiar',
            'ativo' => true,
        ]);

        // Cliente do exemplo de Declaração de Posse e Renda
        Cliente::create([
            'nome_completo' => 'JOSE EMILSON BARBOSA SOUZA',
            'cpf' => '01446927245',
            'data_nascimento' => '1970-05-20',
            'estado_civil' => 'solteiro',
            'sexo' => 'M',
            'endereco' => 'rio Furo Grande',
            'bairro' => 'zona rural',
            'municipio' => 'Afuá',
            'uf' => 'PA',
            'cep' => '68890000',
            'renda_bruta_estabelecimento' => 18000.00,
            'renda_fora_estabelecimento' => 5648.00,
            'atividade_principal' => 'Açaí',
            'condicao_imovel' => 'Posseiro',
            'situacao_atividade' => 'Regime de economia familiar',
            'pescador_artesanal' => false,
            'ativo' => true,
        ]);

        // Cliente da Autodeclaração do Segurado
        Cliente::create([
            'nome_completo' => 'MARIA EVELLYN DOS SANTOS SILVA',
            'cpf' => '09051858205',
            'rg' => '9653150',
            'data_nascimento' => '2002-03-10',
            'local_nascimento' => 'Breves',
            'estado_civil' => 'solteiro',
            'nome_mae' => 'MARIA DOS SANTOS',
            'apelido' => 'Evellyn',
            'sexo' => 'F',
            'endereco' => 'Posse Santa Maria, M/E Rio Japiim',
            'bairro' => 'zona rural',
            'municipio' => 'Breves',
            'uf' => 'PA',
            'cep' => '68800000',
            'periodo_atividade_inicio' => '2019-08-28',
            'periodo_atividade_fim' => '2025-07-07',
            'condicao_imovel' => 'Posseiro',
            'situacao_atividade' => 'Regime de economia familiar',
            'area_total' => 10.00,
            'area_explorada' => 1.00,
            'atividade_principal' => 'Açaí',
            'pescador_artesanal' => false,
            'ativo' => true,
        ]);

        // Cliente para ficha proposta com cônjuge
        Cliente::create([
            'nome_completo' => 'JOÃO SILVA SANTOS',
            'cpf' => '59656212215',
            'rg' => '148118/AP',
            'data_nascimento' => '1970-01-01',
            'local_nascimento' => 'Macapá',
            'estado_civil' => 'casado',
            'nome_mae' => 'Maria Batista',
            'sexo' => 'M',
            'telefone' => '96988086906',
            'email' => 'miranda.ba@gmail.com',
            'endereco' => 'RIO TAMBAQUI DO VIEIRA',
            'bairro' => 'zona rural',
            'complemento' => 'Assentamento',
            'municipio' => 'BREVES',
            'uf' => 'PA',
            'cep' => '68800000',
            'endereco_correspondencia_diferente' => true,
            'endereco_correspondencia' => 'RUA MALVINAS, 33',
            'bairro_correspondencia' => 'ZONA NORTE',
            'municipio_correspondencia' => 'SANTANA',
            'uf_correspondencia' => 'AP',
            'cep_correspondencia' => '68901261',
            'nome_conjuge' => 'MARIA DARC',
            'cpf_conjuge' => '08696312200',
            'rg_conjuge' => '112030/PA',
            'data_nascimento_conjuge' => '1990-03-01',
            'local_nascimento_conjuge' => 'BELÉM',
            'pescador_artesanal' => true,
            'atividade_principal' => 'FRUTICULTURA - Manejo de açaizal nativo',
            'banco_agencia' => 'Macapá',
            'conta_corrente' => '0320754154',
            'ativo' => true,
        ]);

        $this->command->line('✓ Criados 5 clientes específicos dos documentos');
    }

    /**
     * Criar clientes aleatórios para teste
     */
    protected function createRandomClients(): void
    {
        $this->command->info('🎲 Criando clientes aleatórios...');

        // Clientes solteiros pescadores do Pará
        Cliente::factory()
            ->count(15)
            ->solteiro()
            ->pescadorArtesanal()
            ->doPara()
            ->create();

        // Clientes casados pescadores do Pará
        Cliente::factory()
            ->count(20)
            ->casado()
            ->pescadorArtesanal()
            ->doPara()
            ->create();

        // Clientes do Amapá (mix)
        Cliente::factory()
            ->count(8)
            ->doAmapa()
            ->create();

        // Clientes com renda alta
        Cliente::factory()
            ->count(5)
            ->comRendaAlta()
            ->create();

        // Clientes com renda baixa
        Cliente::factory()
            ->count(10)
            ->comRendaBaixa()
            ->create();

        // Alguns clientes inativos
        Cliente::factory()
            ->count(3)
            ->inativo()
            ->create();

        // Clientes variados
        Cliente::factory()
            ->count(12)
            ->create();

        $this->command->line('✓ Criados 73 clientes aleatórios');
    }
}

/*
Para usar este seeder:

1. Executar apenas este seeder:
   php artisan db:seed --class=ClienteSeeder

2. Executar todos os seeders:
   php artisan db:seed

3. Refresh do banco com seeders:
   php artisan migrate:fresh --seed

4. Adicionar ao DatabaseSeeder principal:

   public function run(): void
   {
       $this->call([
           ClienteSeeder::class,
           // outros seeders...
       ]);
   }

5. Para ambiente de produção (apenas clientes específicos):
   Modifique o método run() para chamar apenas createSpecificClients()
*/
