<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('ufpas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained()->cascadeOnDelete();

            $table->string('assentamento')->nullable();
            $table->string('municipio')->nullable();
            $table->string('estado')->nullable();

            // Coordenadas
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();

            $table->decimal('area_total', 8, 2)->nullable(); // hectares

            // Auditoria
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('ufpas');
    }
};

