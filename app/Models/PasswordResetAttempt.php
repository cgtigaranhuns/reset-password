<?php

// app/Models/PasswordResetAttempt.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResetAttempt extends Model
{
    protected $fillable = [
        'enrollment',
        'full_name',
        'cpf_masked',
        'email_masked',
        'enrollment_status',
        'enrollment_status_code',
        'ip_address',
        'user_agent',
        'status',
        'failure_reason',
    ];

    /**
     * Recebe o CPF cru e retorna já mascarado.
     * Nunca persista o CPF completo — use este helper antes de criar o registro.
     */
    public static function maskCpf(string $cpf): string
    {
        $digits = preg_replace('/\D/', '', $cpf);
        if (strlen($digits) !== 11) {
            return '***.***.***-**';
        }
        // mantém só os 3 últimos dígitos visíveis
        return '***.***.' . substr($digits, 6, 3) . '-**';
    }

    public static function maskEmail(string $email): string
    {
        [$user, $domain] = array_pad(explode('@', $email, 2), 2, '');
        $visible = substr($user, 0, 2);
        return $visible . str_repeat('*', max(strlen($user) - 2, 1)) . '@' . $domain;
    }

    public function scopeRecentByEnrollment($query, string $enrollment, int $minutes = 60)
    {
        return $query->where('enrollment', $enrollment)
            ->where('created_at', '>=', now()->subMinutes($minutes));
    }

    public function scopeRecentByIp($query, string $ip, int $minutes = 60)
    {
        return $query->where('ip_address', $ip)
            ->where('created_at', '>=', now()->subMinutes($minutes));
    }
}