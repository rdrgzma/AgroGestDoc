<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('producoes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ufpa_id')->constrained('ufpas')->onDelete('cascade');

            $table->string('produto');
            $table->decimal('quantidade', 10, 2)->nullable();
            $table->string('unidade')->nullable(); // toneladas, kg, litros, etc.
            $table->year('ano')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('producoes');
    }
};
