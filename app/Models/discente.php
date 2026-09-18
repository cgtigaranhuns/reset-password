<?php

// app/Models/Discente.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discente extends Model
{
    protected $fillable = [
        'matricula', 'nome', 'email', 'telefone', 'data_nascimento',
        'cpf_encrypted', 'cpf_hash', 'rg_encrypted',
        'campus_id', 'curso_id', 'situacao', 'periodo', 'turno', 'synced_at',
    ];

    protected $casts = [
        'cpf_encrypted' => 'encrypted',
        'rg_encrypted' => 'encrypted',
        'data_nascimento' => 'date',
        'synced_at' => 'datetime',
    ];

    protected $hidden = ['cpf_encrypted', 'cpf_hash', 'rg_encrypted'];

    public const ALLOWED_STATUSES = ['Matriculado'];

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function isEnrollmentActive(): bool
    {
        return in_array($this->situacao, self::ALLOWED_STATUSES, true);
    }

    public function cpfMatches(string $cpfDigits): bool
    {
        if (!$this->cpf_hash) {
            return false;
        }
        return hash_equals($this->cpf_hash, self::hashCpf($cpfDigits));
    }

    public static function hashCpf(string $cpfDigits): string
    {
        return hash('sha256', $cpfDigits . config('app.key'));
    }

    public function scopeByMatricula($query, string $matricula)
    {
        return $query->where('matricula', $matricula);
    }
    public function birthDateMatches(string $dataNascimento): bool
    {
        if (!$this->data_nascimento) {
            return false;
        }
        return $this->data_nascimento->format('Y-m-d') === $dataNascimento;
    }
}