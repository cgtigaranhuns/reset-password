<?php
// app/Http/Requests/PasswordRecovery/ConfirmResetPasswordRequest.php

namespace App\Http\Requests\PasswordRecovery;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use NjoguAmos\Turnstile\Rules\TurnstileRule; // ← nome correto da classe

class ConfirmResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'enrollment' => ['required', 'string', 'max:50'],

            'cpf' => ['required', 'string', 'regex:/^\d{3}\.?\d{3}\.?\d{3}-?\d{2}$/'],

            'password' => [
                'required',
                'confirmed',
                Password::min(8)->mixedCase()->numbers()->symbols(),
            ],

            'token' => ['required', new TurnstileRule()], // ← nome do campo também mudou
        ];
    }

    public function messages(): array
    {
        return [
            'enrollment.required' => 'Informe sua matrícula.',
            'cpf.regex' => 'Informe um CPF válido.',
            'password.confirmed' => 'A confirmação de senha não confere.',
            'token.required' => 'Confirme que você não é um robô.',
        ];
    }

    public function cpfDigits(): string
    {
        return preg_replace('/\D/', '', $this->input('cpf'));
    }
}