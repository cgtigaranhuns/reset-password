<?php

// database/migrations/xxxx_create_discentes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discentes', function (Blueprint $table) {
            $table->id();

            $table->string('matricula')->unique()->index();
            $table->string('nome');
            $table->string('email')->nullable()->index();
            $table->string('telefone')->nullable();
            $table->date('data_nascimento')->nullable();

            // Dados sensíveis: nunca em texto puro
            $table->text('cpf_encrypted')->nullable();
            $table->string('cpf_hash', 64)->nullable()->index(); // SHA-256, usado pra comparação sem decriptar
            $table->text('rg_encrypted')->nullable();

            $table->foreignId('campus_id')->nullable()->constrained('campus')->nullOnDelete();
            $table->foreignId('curso_id')->nullable()->constrained('cursos')->nullOnDelete();

            $table->string('situacao')->nullable()->index(); // enrollmentStatus
            $table->string('periodo')->nullable();
            $table->string('turno')->nullable();

            $table->timestamp('synced_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discentes');
    }
};