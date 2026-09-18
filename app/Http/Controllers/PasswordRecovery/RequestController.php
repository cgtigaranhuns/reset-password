<?php

namespace App\Http\Controllers\PasswordRecovery;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordRecovery\ConfirmResetPasswordRequest;
use App\Mail\PasswordChangedConfirmation;
use App\Models\Discente;
use App\Models\PasswordResetAttempt;
use App\Services\LdapPasswordService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class RequestController extends Controller
{
    public function __construct(
        private readonly LdapPasswordService $ldapService,
    ) {}

    public function show(): View
    {
        return view('password-recovery.show');
    }

    public function store(ConfirmResetPasswordRequest $request): RedirectResponse
    {
        $matricula = $request->input('matricula');
        $cpfDigits = $request->cpfDigits();
        $dataNascimento = $request->input('data_nascimento');

        $log = [
            'enrollment' => $matricula,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 255),
            'cpf_masked' => PasswordResetAttempt::maskCpf($cpfDigits),
        ];

        $genericError = 'Não foi possível confirmar seus dados. Verifique as informações e tente novamente.';

        $discente = Discente::byMatricula($matricula)->first();

        if (!$discente) {
            $this->logAttempt($log, status: 'failed_not_found');
            return back()->withErrors(['matricula' => $genericError]);
        }

        $log['full_name'] = $discente->nome;
        $log['email_masked'] = PasswordResetAttempt::maskEmail($discente->email ?? '');
        $log['enrollment_status'] = $discente->situacao;

        if (!$discente->isEnrollmentActive()) {
            $this->logAttempt($log, status: 'failed_status_invalid');
            return back()->withErrors(['matricula' => $genericError]);
        }

        if (!$discente->cpfMatches($cpfDigits)) {
            $this->logAttempt($log, status: 'failed_cpf_mismatch');
            return back()->withErrors(['cpf' => $genericError]);
        }

        if (!$discente->birthDateMatches($dataNascimento)) {
            $this->logAttempt($log, status: 'failed_birthdate_mismatch');
            return back()->withErrors(['data_nascimento' => $genericError]);
        }

        if (!$discente->email) {
            $this->logAttempt($log, status: 'failed_ldap_not_found', reason: 'sem email cadastrado');
            return back()->withErrors(['matricula' => $genericError]);
        }

        //$samAccountName = $this->ldapService->resolveSamAccountNameFromEmail($discente->email);
        $result = $this->ldapService->resetPassword($matricula, $request->input('password'));

        if ($result === 'not_found') {
            $this->logAttempt($log, status: 'failed_ldap_not_found');
            return back()->withErrors(['matricula' => $genericError]);
        }

        if ($result === 'error') {
            $this->logAttempt($log, status: 'failed_ldap_error');
            return back()->withErrors(['matricula' => 'Ocorreu um erro ao processar sua solicitação. Tente novamente ou contate o suporte de TI.']);
        }

        $this->logAttempt($log, status: 'success');

       Mail::to($discente->email)->queue(
            new PasswordChangedConfirmation(
                fullName: $discente->nome
            )
        );

        return redirect()
            ->route('password-recovery.show')
            ->with('success', 'Sua senha foi alterada com sucesso! Um email de confirmação foi enviado.');
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