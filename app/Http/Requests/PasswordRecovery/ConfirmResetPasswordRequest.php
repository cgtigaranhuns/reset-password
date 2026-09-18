<?php
namespace App\Http\Requests\PasswordRecovery;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use NjoguAmos\Turnstile\Rules\TurnstileRule;

class ConfirmResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'matricula' => ['required', 'string', 'max:50'],

            'cpf' => ['required', 'string', 'regex:/^\d{3}\.?\d{3}\.?\d{3}-?\d{2}$/'],

            'data_nascimento' => ['required', 'date_format:Y-m-d'],

            'password' => [
                'required',
                'confirmed',
                Password::min(8)->mixedCase()->numbers()->symbols(),
            ],

            'cf-turnstile-response' => ['required', new TurnstileRule()],
        ];
    }

    public function messages(): array
    {
        return [
            'matricula.required' => 'Informe sua matrícula.',
            'cpf.regex' => 'Informe um CPF válido.',
            'data_nascimento.required' => 'Informe sua data de nascimento.',
            'data_nascimento.date_format' => 'Data de nascimento inválida.',
            'password.confirmed' => 'A confirmação de senha não confere.',
            'cf-turnstile-response.required' => 'Confirme que você não é um robô.',
        ];
    }

    public function cpfDigits(): string
    {
        return preg_replace('/\D/', '', $this->input('cpf'));
    }
}