<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();

            // Dados pessoais básicos
            $table->string('nome_completo');
            $table->string('cpf', 14)->unique();
            $table->string('rg', 20)->nullable();
            $table->string('orgao_expedidor', 10)->nullable();
            $table->date('data_nascimento')->nullable();
            $table->string('local_nascimento')->nullable();
            $table->enum('estado_civil', ['solteiro', 'casado', 'união estável', 'divorciado', 'viúvo'])->default('solteiro')->nullable();
            $table->string('nome_mae')->nullable();
            $table->string('apelido', 50)->nullable();
            $table->enum('cor_raca', ['branco', 'pardo', 'preto', 'amarelo', 'indígena'])->nullable();
            $table->enum('sexo', ['M', 'F']);
            $table->string('escolaridade')->nullable();

            // Contato
            $table->string('telefone', 15)->nullable();
            $table->string('email')->nullable();

            // Endereço residencial
            $table->string('endereco')->nullable();
            $table->string('numero', 10)->nullable();
            $table->string('complemento')->nullable();
            $table->string('bairro')->nullable();
            $table->string('municipio')->nullable();
            $table->string('uf', 2)->nullable();
            $table->string('cep', 9)->nullable();

            // Endereço para correspondência (se diferente)
            $table->boolean('endereco_correspondencia_diferente')->default(false)->nullable();
            $table->string('endereco_correspondencia')->nullable();
            $table->string('numero_correspondencia', 10)->nullable();
            $table->string('complemento_correspondencia')->nullable();
            $table->string('bairro_correspondencia')->nullable();
            $table->string('municipio_correspondencia')->nullable();
            $table->string('uf_correspondencia', 2)->nullable();
            $table->string('cep_correspondencia', 9)->nullable();

            // Dados do cônjuge (se casado)
            $table->string('nome_conjuge')->nullable();
            $table->string('cpf_conjuge', 14)->nullable();
            $table->string('rg_conjuge', 20)->nullable();
            $table->date('data_nascimento_conjuge')->nullable();
            $table->string('local_nascimento_conjuge')->nullable();
            $table->string('apelido_conjuge', 50)->nullable();
            $table->enum('cor_raca_conjuge', ['branco', 'pardo', 'preto', 'amarelo', 'indígena'])->nullable();
            $table->string('escolaridade_conjuge')->nullable();

            // Dados do projeto/assentamento
            $table->string('projeto_assentamento')->nullable()->nullable();
            $table->string('codigo_sipra', 20)->nullable()->nullable();
            $table->boolean('beneficiario_credito_fundiario')->default(false)->nullable();
            $table->boolean('gestor_ufpa')->default(false)->nullable();
            $table->boolean('mao_obra_ufpa')->default(false)->nullable();
            $table->boolean('assentado_reforma_agraria')->default(false)->nullable();

            // Localização da propriedade
            $table->boolean('area_indigena_quilombola')->default(false)->nullable();
            $table->boolean('pescador_artesanal')->default(true)->nullable();
            $table->enum('tipo_area', ['Terra', 'Lâmina de água', 'Tanque'])->default('Lâmina de água')->nullable();
            $table->string('medida_area', 10)->default('ha')->nullable();
            $table->decimal('area_total', 10, 2)->nullable();
            $table->decimal('area_explorada', 10, 2)->nullable();
            $table->string('coordenada_latitude', 20)->nullable();
            $table->string('coordenada_longitude', 20)->nullable();

            // Informações de renda
            $table->decimal('renda_bruta_estabelecimento', 10, 2)->default(0)->nullable();
            $table->decimal('renda_fora_estabelecimento', 10, 2)->default(0)->nullable();
            $table->decimal('beneficios_sociais', 10, 2)->default(0)->nullable();

            // Informações de mão de obra
            $table->integer('pessoas_familia_residentes')->default(0)->nullable();
            $table->integer('pessoas_familia_trabalham')->default(0)->nullable();
            $table->integer('empregados_permanentes')->default(0)->nullable();

            // Atividade rural
            $table->string('atividade_principal')->default('Pesca artesanal')->nullable();
            $table->text('outras_atividades')->nullable();
            $table->date('periodo_atividade_inicio')->nullable();
            $table->date('periodo_atividade_fim')->nullable();
            $table->enum('condicao_imovel', [
                'Proprietário', 'Possuidor', 'Comodatário', 'Arrendatário',
                'Parceiro', 'Meeiro', 'Usufrutuário', 'Condômino',
                'Posseiro', 'Assentado', 'Acampado'
            ])->default('Posseiro')->nullable();
            $table->enum('situacao_atividade', ['Individualmente', 'Regime de economia familiar'])->default('Regime de economia familiar')->nullable();

            // Informações bancárias
            $table->string('banco_agencia', 50)->nullable();
            $table->string('conta_corrente', 20)->nullable();

            // Informações adicionais
            $table->boolean('pessoa_exposta_politicamente')->default(false)->nullable();
            $table->text('observacoes')->nullable();

            // Controle
            $table->boolean('ativo')->default(true)->nullable();
            $table->timestamps();

            // Índices
            $table->index(['municipio', 'uf']);
            $table->index('estado_civil');
            $table->index('projeto_assentamento');
            $table->index(['ativo', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};

