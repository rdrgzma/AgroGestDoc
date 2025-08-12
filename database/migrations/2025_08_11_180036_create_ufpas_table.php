<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ufpas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('people')->onDelete('cascade');

            $table->string('nome_propriedade');
            $table->decimal('area_total', 10, 2)->nullable();
            $table->string('localizacao')->nullable();
            $table->string('matricula')->nullable();
            $table->string('nirf')->nullable();
            $table->string('ccir')->nullable();
            $table->string('car')->nullable();
            $table->string('tipo_posse')->nullable(); // própria, arrendada, etc.

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ufpas');
    }
};


