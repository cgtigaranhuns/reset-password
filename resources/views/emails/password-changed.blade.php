<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>IFPE Campus Garanhuns - Alteração de Senha</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #006633;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .logo {
            max-width: 300px;
          
            height: auto;
        }

        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
            margin-top: 20px;
        }

        h2 {
            color: #006633;
        }

        .success-box {
            background-color: #eaf7ef;
            border-left: 4px solid #006633;
            padding: 12px 15px;
            margin: 20px 0;
            border-radius: 3px;
        }

        .warning-box {
            background-color: #fff4e5;
            border-left: 4px solid #be300d;
            padding: 12px 15px;
            margin: 20px 0;
            border-radius: 3px;
        }
    </style>
</head>

<body>

    <div class="header">

        <img
            src="{{ asset('https://sisreq.garanhuns.ifpe.edu.br/img/Logo-Garanhuns.png') }}"
            alt="IFPE Campus Garanhuns"
            class="logo"
            style="max-width:300px; width:auto; height:auto;"
        >

        <h1>Instituto Federal de Pernambuco</h1>
        <h2>Campus Garanhuns</h2>

    </div>

    <div class="content">

        <p>
            Olá, <strong>{{ $fullName }}</strong>!
        </p>

        <div class="success-box">
            <strong>Sua senha foi alterada com sucesso.</strong>
            <br>
            Você já pode utilizar sua nova senha para acessar os sistemas institucionais, os computadores e a internet do Campus Garanhuns.
        </div>

        <p>
            A alteração foi realizada por meio do sistema de recuperação de senha
            do IFPE Campus Garanhuns.
        </p>

        

        <p>
            Por motivos de segurança, <strong>nunca</strong> compartilhe sua senha com outras pessoas.
        </p>

        <div class="warning-box">
            Caso não tenha realizado a alteração de sua senha, entre em contato
            imediatamente com a CGTI do IFPE Campus Garanhuns.
        </div>
    </div>

    <div class="footer">

        <p>
            <em>
                <span style="color:rgb(190, 48, 13);">
                    Esta é uma mensagem automática do sistema de recuperação
                    de senha do IFPE - Campus Garanhuns.
                </span>
            </em>
        </p>

        <p>
            Por favor, não responda este e-mail.
            Em caso de dúvidas ou problemas relacionados ao acesso aos sistemas
            institucionais, entre em contato com a
            <strong>Coordenação de Gestão de Tecnologia da Informação (CGTI)</strong>
            pelo e-mail:
        </p>

        <p style="text-align: center; margin-top: 15px;">
            <a
                href="mailto:cgti@garanhuns.ifpe.edu.br"
                style="color: #006633; font-weight: bold; text-decoration: none;"
            >
                cgti@garanhuns.ifpe.edu.br
            </a>
        </p>
        <p>
            IFPE - Campus Garanhuns |
            R. Francisco Braga - Indiano, Garanhuns - PE |
            CEP: 55.298-320
        </p>

    </div>

</body>

</html>