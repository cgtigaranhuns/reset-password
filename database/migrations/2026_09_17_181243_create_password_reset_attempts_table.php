<?php

// database/migrations/xxxx_xx_xx_create_password_reset_attempts_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('password_reset_attempts', function (Blueprint $table) {
            $table->id();

            $table->string('enrollment')->index(); // matrícula informada
            $table->string('full_name')->nullable();
            $table->string('cpf_masked', 20)->nullable(); // ex: ***.***.789-**
            $table->string('email_masked', 150)->nullable(); // ex: jo***@dominio.com

            $table->string('enrollment_status')->nullable();
            $table->string('enrollment_status_code')->nullable();

            $table->ipAddress('ip_address');
            $table->string('user_agent')->nullable();

            $table->enum('status', [
                'success',
                'failed_not_found',       // matrícula não encontrada na API
                'failed_status_invalid',  // enrollmentStatus não é "Matriculado"
                'failed_cpf_mismatch',    // CPF não confere
                'failed_rate_limited',
                'failed_ldap_not_found',  // não achou usuário correspondente no AD
                'failed_ldap_error',      // erro na troca via LDAP
                'failed_network',         // fora da rede corporativa (bloqueado no middleware, mas se logar aqui)
            ])->default('failed_not_found');

            $table->string('failure_reason')->nullable(); // detalhe técnico, sem dado sensível

            $table->timestamps();

            $table->index(['enrollment', 'created_at']);
            $table->index(['ip_address', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('password_reset_attempts');
    }
};