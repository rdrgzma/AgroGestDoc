<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('producoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ufpa_id')->constrained()->cascadeOnDelete();
            $table->string('produto');
            $table->string('categoria')->nullable(); // exemplo: extrativismo, lavoura
            $table->decimal('valor_anual', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('producoes');
    }
};

