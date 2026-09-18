<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use LdapRecord\LdapRecordException;
use LdapRecord\Models\ActiveDirectory\User as LdapUser;

class LdapPasswordService
{
    /**
     * Altera a senha do usuário no Active Directory.
     *
     * @return string success|not_found|error
     */
    public function resetPassword(string $matricula, string $newPassword): string
    {
        try {
            $user = LdapUser::query()
                ->where('samaccountname', '=', $matricula)
                ->first();

            if (!$user) {
                return 'not_found';
            }

            $user->unicodepwd = $newPassword;
            $user->pwdlastset = -1;
            $user->save();

            return 'success';

        } catch (\Throwable $e) {
            Log::error('Erro ao alterar senha no AD', [
                'matricula' => $matricula,
                'error' => $e->getMessage(),
            ]);

            return 'error';
        }
    }
}