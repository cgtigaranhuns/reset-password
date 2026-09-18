<?php

// app/Models/Discente.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Config;

class Discente extends Model
{
    protected $fillable = [
        'enrollment_id', 'enrollment', 'campus_id', 'course_syllabus_id',
        'enrollment_status', 'enrollment_status_code',
        'period_status', 'period_status_code', 'current_period',
        'full_name', 'email', 'cpf_encrypted', 'cpf_hash',
        'shift', 'quota', 'synced_at',
    ];

    protected $casts = [
        'cpf_encrypted' => 'encrypted',
        'synced_at' => 'datetime',
    ];

    protected $hidden = ['cpf_encrypted', 'cpf_hash'];

    public const ALLOWED_STATUSES = ['Matriculado'];

    public function isEnrollmentActive(): bool
    {
        return in_array($this->enrollment_status, self::ALLOWED_STATUSES, true);
    }

    public function cpfMatches(string $cpfDigits): bool
    {
        return hash_equals($this->cpf_hash, self::hashCpf($cpfDigits));
    }

    public static function hashCpf(string $cpfDigits): string
    {
        return hash('sha256', $cpfDigits . config('app.key'));
    }

    public function scopeByEnrollment($query, string $enrollment)
    {
        return $query->where('enrollment', $enrollment);
    }
}