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
        Schema::create('reacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mensagem_id')->constrained('mensagens')->cascadeOnDelete();
            $table->foreignId('usuario_id')->constrained('usuarios')->cascadeOnDelete();
            $table->string('emoji', 10);
            $table->timestamps();

            $table->unique(['mensagem_id', 'usuario_id', 'emoji']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reacoes');
    }
};
