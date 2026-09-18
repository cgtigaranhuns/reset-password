<?php

// app/Console/Commands/SyncDiscentesFromApi.php

namespace App\Console\Commands;

use App\Models\Discente;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncDiscentesFromApi extends Command
{
    protected $signature = 'discentes:sync {--page-size=200}';
    protected $description = 'Sincroniza a tabela local de discentes a partir da API acadêmica';

    public function handle(): int
    {
        $this->info('Iniciando sincronização de discentes...');

        $pageSize = (int) $this->option('page-size');
        $page = 1;
        $total = 0;
        $now = now();

        do {
            $response = Http::withToken(config('services.academic_api.token'))
                ->timeout(30)
                ->get(rtrim(config('services.academic_api.url'), '/') . '/enrollments', [
                    'page' => $page,
                    'per_page' => $pageSize,
                ]);

            if (!$response->successful()) {
                $this->error("Falha na página {$page}: HTTP {$response->status()}");
                Log::error('Falha ao sincronizar discentes', [
                    'page' => $page,
                    'status' => $response->status(),
                ]);
                return self::FAILURE;
            }

            $items = $response->json('data', $response->json() ?? []);

            foreach ($items as $item) {
                $this->upsertDiscente($item, $now);
                $total++;
            }

            $this->line("Página {$page}: " . count($items) . ' registros processados.');
            $page++;
        } while (count($items) === $pageSize);

        $this->info("Sincronização concluída: {$total} discentes atualizados.");

        return self::SUCCESS;
    }

    private function upsertDiscente(array $item, \Illuminate\Support\Carbon $syncedAt): void
    {
        $cpfDigits = preg_replace('/\D/', '', $item['brCPF'] ?? '');

        Discente::updateOrCreate(
            ['enrollment_id' => $item['enrollmentId']],
            [
                'enrollment' => $item['enrollment'] ?? null,
                'campus_id' => $item['campusId'] ?? null,
                'course_syllabus_id' => $item['courseSyllabusId'] ?? null,
                'enrollment_status' => $item['enrollmentStatus'] ?? null,
                'enrollment_status_code' => $item['enrollmentStatusCode'] ?? null,
                'period_status' => $item['periodStatus'] ?? null,
                'period_status_code' => $item['periodStatusCode'] ?? null,
                'current_period' => $item['currentPeriod'] ?? null,
                'full_name' => $item['fullName'] ?? '',
                'email' => $item['email'] ?? null,
                'cpf_encrypted' => $cpfDigits,
                'cpf_hash' => Discente::hashCpf($cpfDigits),
                'shift' => $item['shift'] ?? null,
                'quota' => $item['quota'] ?? null,
                'synced_at' => $syncedAt,
            ]
        );
    }
}