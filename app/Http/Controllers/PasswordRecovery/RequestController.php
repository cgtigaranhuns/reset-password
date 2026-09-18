<?php

// app/Http/Controllers/PasswordRecovery/RequestController.php

namespace App\Http\Controllers\PasswordRecovery;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordRecovery\ConfirmResetPasswordRequest;
use App\Mail\PasswordChangedConfirmation;
use App\Models\PasswordResetAttempt;
use App\Services\AcademicApiService;
use App\Services\LdapPasswordService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class RequestController extends Controller
{
    public function __construct(
        private readonly AcademicApiService $academicApi,
        private readonly LdapPasswordService $ldapService,
    ) {}

    public function show(): View
    {
        return view('password-recovery.show');
    }

    public function store(ConfirmResetPasswordRequest $request): RedirectResponse
    {
        $enrollment = $request->input('enrollment');
        $cpfDigits = $request->cpfDigits();

        $log = [
            'enrollment' => $enrollment,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 255),
        ];

        // Mensagem genérica — nunca revelar qual etapa falhou (evita enumeração)
        $genericError = 'Não foi possível confirmar seus dados. Verifique as informações e tente novamente.';

        $data = $this->academicApi->findByEnrollment($enrollment);

        if (!$data) {
            $this->logAttempt($log, status: 'failed_not_found');
            return back()->withErrors(['enrollment' => $genericError]);
        }

        $log['full_name'] = $data['fullName'] ?? null;
        $log['cpf_masked'] = PasswordResetAttempt::maskCpf($data['brCPF'] ?? '');
        $log['email_masked'] = PasswordResetAttempt::maskEmail($data['email'] ?? '');
        $log['enrollment_status'] = $data['enrollmentStatus'] ?? null;
        $log['enrollment_status_code'] = $data['enrollmentStatusCode'] ?? null;

        if (!$this->academicApi->isEnrollmentStatusValid($data)) {
            $this->logAttempt($log, status: 'failed_status_invalid');
            return back()->withErrors(['enrollment' => $genericError]);
        }

        if (!$this->academicApi->cpfMatches($data, $cpfDigits)) {
            $this->logAttempt($log, status: 'failed_cpf_mismatch');
            return back()->withErrors(['cpf' => $genericError]);
        }

        $email = $data['email'] ?? null;
        if (!$email) {
            $this->logAttempt($log, status: 'failed_ldap_not_found', reason: 'sem email cadastrado na API');
            return back()->withErrors(['enrollment' => $genericError]);
        }

        $samAccountName = $this->ldapService->resolveSamAccountNameFromEmail($email);
        $result = $this->ldapService->resetPassword($samAccountName, $request->input('password'));

        if ($result === 'not_found') {
            $this->logAttempt($log, status: 'failed_ldap_not_found');
            return back()->withErrors(['enrollment' => $genericError]);
        }

        if ($result === 'error') {
            $this->logAttempt($log, status: 'failed_ldap_error');
            return back()->withErrors(['enrollment' => 'Ocorreu um erro ao processar sua solicitação. Tente novamente ou contate o suporte de TI.']);
        }

        $this->logAttempt($log, status: 'success');

        Mail::to($email)->queue(
            new PasswordChangedConfirmation(fullName: $data['fullName'] ?? '')
        );

        return redirect()
            ->route('password-recovery.show')
            ->with('success', 'Sua senha foi alterada com sucesso! Você já pode fazer login com a nova senha. Um email de confirmação foi enviado.');
    }

    private function logAttempt(array $data, string $status, ?string $reason = null): void
    {
        PasswordResetAttempt::create([
            ...$data,
            'status' => $status,
            'failure_reason' => $reason,
        ]);
    }
}