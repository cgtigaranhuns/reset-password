{{-- resources/views/password-recovery/show.blade.php --}}
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Recuperação de Senha</title>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    <style>
        body { font-family: system-ui, sans-serif; background: #f4f5f7; margin: 0; padding: 2rem; }
        .card { max-width: 420px; margin: 2rem auto; background: #fff; border-radius: 8px;
                box-shadow: 0 1px 4px rgba(0,0,0,.1); padding: 2rem; }
        h1 { font-size: 1.25rem; margin-bottom: .25rem; }
        p.subtitle { color: #666; font-size: .875rem; margin-top: 0; margin-bottom: 1.5rem; }
        label { display: block; font-size: .875rem; font-weight: 600; margin-bottom: .25rem; }
        input { width: 100%; padding: .5rem .75rem; border: 1px solid #ccc; border-radius: 6px;
                margin-bottom: 1rem; box-sizing: border-box; font-size: .95rem; }
        button { width: 100%; padding: .65rem; background: #1d4ed8; color: #fff; border: none;
                 border-radius: 6px; font-size: 1rem; cursor: pointer; }
        button:hover { background: #1e40af; }
        .alert-error { background: #fee2e2; color: #991b1b; padding: .75rem 1rem; border-radius: 6px;
                       font-size: .875rem; margin-bottom: 1rem; }
        .alert-success { background: #dcfce7; color: #166534; padding: .75rem 1rem; border-radius: 6px;
                         font-size: .875rem; margin-bottom: 1rem; }
        .hint { font-size: .75rem; color: #888; margin-top: -0.75rem; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Recuperação de Senha</h1>
        <p class="subtitle">Disponível apenas dentro da rede corporativa.</p>

        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert-error">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password-recovery.store') }}">
            @csrf

            <label for="enrollment">Matrícula</label>
            <input type="text" id="matricula" name="matricula" value="{{ old('enrollment') }}" required autofocus>

            <label for="cpf">CPF</label>
            <input type="text" id="cpf" name="cpf" placeholder="000.000.000-00" required>
            
            <label for="data_nascimento">Data de nascimento</label>
            <input type="date" id="data_nascimento" name="data_nascimento" value="{{ old('data_nascimento') }}" required>

            <label for="password">Nova senha</label>
            <input type="password" id="password" name="password" required>
            <p class="hint">Mínimo 8 caracteres, com maiúscula, minúscula, número e símbolo.</p>

            <label for="password_confirmation">Confirme a nova senha</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>

            <div class="cf-turnstile" data-sitekey="{{ config('turnstile.sitekey') }}" style="margin-bottom: 1rem;"></div> 

            <button type="submit">Alterar senha</button>
        </form>
    </div>
</body>
</html>