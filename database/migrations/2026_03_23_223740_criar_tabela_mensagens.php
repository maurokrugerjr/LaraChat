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
        Schema::create('mensagens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversa_id')->constrained('conversas')->cascadeOnDelete();
            $table->foreignId('usuario_id')->constrained('usuarios')->restrictOnDelete();
            $table->foreignId('resposta_para_id')->nullable()->constrained('mensagens')->nullOnDelete();
            $table->enum('tipo', ['texto', 'imagem', 'arquivo', 'audio'])->default('texto');
            $table->text('corpo')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mensagens');
    }
};
