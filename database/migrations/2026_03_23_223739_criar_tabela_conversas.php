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
        Schema::create('conversas', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['privada', 'grupo'])->default('privada');
            $table->string('nome')->nullable();
            $table->string('avatar')->nullable();
            $table->foreignId('criado_por')->constrained('usuarios')->restrictOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversas');
    }
};
