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
        Schema::create('contas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->nullable()->constrained()->cascadeOnDelete();
            $table->enum('tipo', ['pagar', 'receber'])->default('pagar');
            $table->string('descricao')->nullable();
            $table->decimal('valor', 10, 2);
            $table->date('vencimento');
            $table->enum('status', ['pendente', 'pago', 'atrasado'])->default('pendente');
            $table->string('forma_pagamento')->nullable();

            // Auditoria
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contas');
    }
};
