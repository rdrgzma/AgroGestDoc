<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cpf')->unique();
            $table->string('rg')->nullable();
            $table->date('data_nascimento')->nullable();
            $table->string('naturalidade')->nullable();
            $table->enum('estado_civil', ['solteiro', 'casado', 'uniao_estavel', 'divorciado', 'viuvo'])->nullable();
            $table->string('nome_mae')->nullable();
            $table->string('email')->nullable();
            $table->string('telefone')->nullable();

            // Endereço
            $table->string('endereco')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('uf')->nullable();
            $table->string('cep')->nullable();
            $table->string('endereco_correspondencia')->nullable();

            // Auditoria
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('people');
    }
};

