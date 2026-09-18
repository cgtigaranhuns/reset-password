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

            // Identificadores vindos da API
            $table->unsignedBigInteger('enrollment_id')->unique();
            $table->string('enrollment')->unique()->index(); // matrícula, usada na busca
            $table->unsignedBigInteger('campus_id')->nullable();
            $table->unsignedBigInteger('course_syllabus_id')->nullable();

            $table->string('enrollment_status')->nullable()->index();
            $table->string('enrollment_status_code')->nullable();
            $table->string('period_status')->nullable();
            $table->string('period_status_code')->nullable();
            $table->string('current_period')->nullable();

            $table->string('full_name');
            $table->string('email')->nullable()->index();

            // Dados sensíveis — sempre criptografados em repouso
            $table->text('cpf_encrypted');       // Laravel encrypted cast
            $table->string('cpf_hash', 64)->index(); // SHA-256 do CPF só-números, pra busca/comparação rápida sem decriptar

            $table->string('shift')->nullable();
            $table->string('quota')->nullable();

            $table->timestamp('synced_at')->nullable(); // última vez que veio da API

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discentes');
    }
};