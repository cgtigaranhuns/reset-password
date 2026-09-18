<?php

// app/Services/AcademicApiService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AcademicApiService
{
    /**
     * Situações de matrícula aceitas para permitir o reset de senha.
     */
    private const ALLOWED_STATUSES = ['Matriculado'];

    public function findByEnrollment(string $enrollment): ?array
    {
        $cacheKey = "ifpe_api:enrollment:{$enrollment}";

        return Cache::remember($cacheKey, now()->addMinutes(2), function () use ($enrollment) {
            try {
                $response = Http::withToken(config('services.ifpe_api.token'))
                    ->timeout(5)
                    ->get(rtrim(config('services.ifpe_api.url'), '/') . "/enrollments/{$enrollment}");

                if (!$response->successful()) {
                    return null;
                }

                return $response->json();
            } catch (\Throwable $e) {
                Log::error('Falha ao consultar API acadêmica', [
                    'enrollment' => $enrollment,
                    'error' => $e->getMessage(),
                ]);
                return null;
            }
        });
    }

    public function isEnrollmentStatusValid(array $data): bool
    {
        return in_array($data['enrollmentStatus'] ?? null, self::ALLOWED_STATUSES, true);
    }

    public function cpfMatches(array $data, string $cpfDigits): bool
    {
        $apiCpfDigits = preg_replace('/\D/', '', $data['brCPF'] ?? '');
        return $apiCpfDigits !== '' && hash_equals($apiCpfDigits, $cpfDigits);
    }
}