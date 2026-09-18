<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // MySQL exige recriar o enum inteiro pra adicionar um valor
public function up(): void
{
    DB::statement("ALTER TABLE password_reset_attempts MODIFY status ENUM(
        'success',
        'failed_not_found',
        'failed_status_invalid',
        'failed_cpf_mismatch',
        'failed_birthdate_mismatch',
        'failed_rate_limited',
        'failed_ldap_not_found',
        'failed_ldap_error',
        'failed_network'
    ) DEFAULT 'failed_not_found'");
}

public function down(): void
{
    DB::statement("ALTER TABLE password_reset_attempts MODIFY status ENUM(
        'success',
        'failed_not_found',
        'failed_status_invalid',
        'failed_cpf_mismatch',
        'failed_rate_limited',
        'failed_ldap_not_found',
        'failed_ldap_error',
        'failed_network'
    ) DEFAULT 'failed_not_found'");
}
};