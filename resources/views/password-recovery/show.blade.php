{{-- resources/views/password-recovery/show.blade.php --}}
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Recuperação de Senha</title>

    <link rel="icon" type="image/png" href="{{ asset('img/logo-ifpe.png') }}">

    <script
        src="https://challenges.cloudflare.com/turnstile/v0/api.js"
        async
        defer
    ></script>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 2rem 1rem;
            min-height: 100vh;

            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;

            background: #f4f5f7;
            color: #333;
        }

        .card {
            width: 100%;
            max-width: 420px;

            margin: 2rem auto;
            padding: 2rem;

            background: #fff;
            border-radius: 8px;

            box-shadow: 0 1px 4px rgba(0, 0, 0, .1);
        }

        /* Logo */
        .logo {
            display: block;

            max-width: 220px;
            max-height: 90px;

            width: auto;
            height: auto;

            margin: 0 auto 1.5rem;
        }

        /* Cabeçalho */
        h1 {
            margin: 0 0 .25rem;

            color: #3CB371;

            font-size: 1.25rem;
            font-weight: 600;

            text-align: center;
        }

        .subtitle {
            margin: 0 0 1.5rem;

            color: #666;

            font-size: .875rem;
            text-align: center;
        }

        /* Alertas */
        .alert {
            margin-bottom: 1rem;
            padding: .75rem 1rem;

            border-radius: 6px;

            font-size: .875rem;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
        }

        /* Formulário */
        label {
            display: block;

            margin-bottom: .25rem;

            font-size: .875rem;
            font-weight: 600;
        }

        input {
            display: block;

            width: 100%;
            height: 42px;

            margin-bottom: 1rem;
            padding: .5rem .75rem;

            border: 1px solid #ccc;
            border-radius: 6px;

            background: #fff;

            font-family: inherit;
            font-size: .95rem;

            transition:
                border-color .15s ease,
                box-shadow .15s ease;
        }

        input:focus {
            outline: none;

            border-color: #3CB371;

            box-shadow: 0 0 0 2px rgba(60, 179, 113, .15);
        }

        /* Senha */
        .password-wrapper {
            position: relative;
        }

        .password-wrapper input {
            padding-right: 48px;
        }

        .toggle-password {
            position: absolute;

            top: 0;
            right: 0;

            width: 42px;
            height: 42px;

            padding: 0;

            border: 0;
            background: transparent;

            color: #666;

            font-size: 18px;

            cursor: pointer;

            display: flex;
            align-items: center;
            justify-content: center;

            transition: color .15s ease;
        }

        .toggle-password:hover {
            background: transparent;
            color: #3CB371;
        }

        .toggle-password:focus {
            outline: none;
        }

        .hint {
            margin: -.75rem 0 1rem;

            color: #888;

            font-size: .75rem;
            line-height: 1.4;
        }

        /* Botões */
        .btn {
            display: block;

            width: 100%;
            min-height: 42px;

            padding: .65rem 1rem;

            border: 0;
            border-radius: 6px;

            font-family: inherit;
            font-size: 1rem;
            font-weight: 500;

            text-align: center;
            text-decoration: none;

            cursor: pointer;

            transition:
                background-color .15s ease,
                transform .05s ease;
        }

        .btn-primary {
            background: #3CB371;
            color: #fff;
        }

        .btn-primary:hover {
            background: #2E8B57;
        }

        .btn-primary:active {
            transform: translateY(1px);
        }

        .btn-secondary {
            background: #3CB371;
            color: #fff;
        }

        .btn-secondary:hover {
            background: #2E8B57;
        }

        /* Espaçamento entre botões */
        .form-actions {
            margin-top: 1rem;
        }

        /* Turnstile */
        .turnstile {
            margin-bottom: 1rem;
        }

        /* Mobile */
        @media (max-width: 480px) {
            body {
                padding: 1rem;
            }

            .card {
                margin: 1rem auto;
                padding: 1.5rem;
            }

            .logo {
                max-width: 190px;
            }
        }
    </style>

    <script>
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);

            const isPassword = input.type === 'password';

            input.type = isPassword ? 'text' : 'password';

            button.textContent = isPassword ? '🙈' : '👁';

            button.setAttribute(
                'aria-label',
                isPassword ? 'Ocultar senha' : 'Mostrar senha'
            );
        }

        document.addEventListener('DOMContentLoaded', function () {
            const cpf = document.getElementById('cpf');

            cpf.addEventListener('input', function () {
                this.value = this.value
                    .replace(/\D/g, '')
                    .slice(0, 11);
            });
        });
    </script>
</head>

<body>

    <main class="card">

        {{-- Logo --}}
        <img
            src="{{ asset('img/Logo-Garanhuns.png') }}"
            alt="IFPE Campus Garanhuns"
            class="logo"
        >

        {{-- Cabeçalho --}}
        <h1>Recuperação de Senha</h1>

        <p class="subtitle">
            Disponível apenas dentro da rede corporativa.
        </p>

        {{-- Mensagem de sucesso --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Mensagens de erro --}}
        @if ($errors->any())
            <div class="alert alert-error">
                @foreach ($errors->all() as $error)
                    {{ $error }}

                    @if (!$loop->last)
                        <br>
                    @endif
                @endforeach
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('password-recovery.store') }}"
        >
            @csrf

            {{-- Matrícula --}}
            <label for="matricula">
                Matrícula
            </label>

            <input
                type="text"
                id="matricula"
                name="matricula"
                value="{{ old('matricula') }}"
                autocomplete="username"
                required
                autofocus
            >

            {{-- CPF --}}
            <label for="cpf">
                CPF
            </label>

            <input
                type="text"
                id="cpf"
                name="cpf"
                placeholder="00000000000"
                value="{{ old('cpf') }}"
                inputmode="numeric"
                pattern="[0-9]{11}"
                maxlength="11"
                autocomplete="off"
                required
            >

            {{-- Data de nascimento --}}
            <label for="data_nascimento">
                Data de nascimento
            </label>

            <input
                type="date"
                id="data_nascimento"
                name="data_nascimento"
                value="{{ old('data_nascimento') }}"
                autocomplete="bday"
                required
            >

            {{-- Nova senha --}}
            <label for="password">
                Nova senha
            </label>

            <div class="password-wrapper">
                <input
                    type="password"
                    id="password"
                    name="password"
                    autocomplete="new-password"
                    required
                >

                <button
                    type="button"
                    class="toggle-password"
                    onclick="togglePassword('password', this)"
                    aria-label="Mostrar senha"
                    title="Mostrar senha"
                >
                    👁
                </button>
            </div>

            <p class="hint">
                Mínimo 8 caracteres, com maiúscula, minúscula, número e símbolo.
            </p>

            {{-- Confirmação da senha --}}
            <label for="password_confirmation">
                Confirme a nova senha
            </label>

            <div class="password-wrapper">
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    autocomplete="new-password"
                    required
                >

                <button
                    type="button"
                    class="toggle-password"
                    onclick="togglePassword('password_confirmation', this)"
                    aria-label="Mostrar senha"
                    title="Mostrar senha"
                >
                    👁
                </button>
            </div>

            {{-- Cloudflare Turnstile --}}
            <div class="turnstile">
                <div
                    class="cf-turnstile"
                    data-sitekey="{{ config('turnstile.sitekey') }}"
                ></div>
            </div>

            {{-- Ações --}}
            <button
                type="submit"
                class="btn btn-primary"
            >
                Alterar senha
            </button>

            <div class="form-actions">
                <a
                    href="http://login.labs.garanhuns.intranet/"
                    class="btn btn-secondary"
                >
                    Voltar para o login
                </a>
            </div>

        </form>

    </main>

</body>
</html>