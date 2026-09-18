<?php

// app/Services/LdapPasswordService.php

namespace App\Services;

use LdapRecord\Models\ActiveDirectory\User as LdapUser;
use LdapRecord\LdapRecordException;
use Illuminate\Support\Facades\Log;

class LdapPasswordService
{
    /**
     * @return string 'success' | 'not_found' | 'error'
     */
    public function resetPassword(string $samAccountName, string $newPassword): string
    {
        try {
            $user = LdapUser::findBy('samaccountname', $samAccountName);

            if (!$user) {
                return 'not_found';
            }

            $user->setUnicodePwd($newPassword);
            $user->setPwdLastSet(0); // força troca no próximo login

            return $user->save() ? 'success' : 'error';
        } catch (LdapRecordException $e) {
            Log::error('Erro ao alterar senha no AD', [
                'sam_account_name' => $samAccountName,
                'error' => $e->getMessage(), // nunca logar a senha nova aqui
            ]);
            return 'error';
        }
    }

    /**
     * Deriva o samAccountName a partir do email institucional.
     * Ajuste essa regra para a convenção real da sua instituição.
     */
    public function resolveSamAccountNameFromEmail(string $email): string
    {
        return strtolower(explode('@', $email)[0]);
    }
}