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
        Schema::create('triajes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
            $table->foreignId('usuario_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('hora_triaje')->nullable();
            $table->integer('tension_sistolica')->nullable();
            $table->integer('tension_diastolica')->nullable();
            $table->integer('frecuencia_cardiaca')->nullable();
            $table->integer('frecuencia_respiratoria')->nullable();
            $table->decimal('temperatura', 4, 1)->nullable();
            $table->integer('saturacion_oxigeno')->nullable();
            $table->integer('glasgow')->nullable();
            $table->integer('eva')->nullable();
            $table->decimal('glucemia', 6, 2)->nullable();
            $table->boolean('vomitos')->default(false);
            $table->boolean('deposiciones')->default(false);
            $table->boolean('diuresis')->default(false);
            $table->decimal('peso', 5, 2)->nullable();
            $table->decimal('talla', 5, 2)->nullable();
            $table->string('categoria')->nullable();
            $table->string('flujo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('triajes');
    }
};
