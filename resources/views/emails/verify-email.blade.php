<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificação de Email - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #374151;
            background-color: #f9fafb;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 1.875rem;
            font-weight: 700;
        }
        .content {
            padding: 2rem;
        }
        .welcome-text {
            font-size: 1.125rem;
            margin-bottom: 1.5rem;
            color: #1f2937;
        }
        .verification-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            padding: 0.875rem 2rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 1rem;
            margin: 1.5rem 0;
            transition: transform 0.2s;
        }
        .verification-button:hover {
            transform: translateY(-1px);
        }
        .info-box {
            background-color: #f3f4f6;
            border-left: 4px solid #667eea;
            padding: 1rem;
            margin: 1.5rem 0;
            border-radius: 0 6px 6px 0;
        }
        .footer {
            background-color: #f9fafb;
            padding: 1.5rem;
            text-align: center;
            font-size: 0.875rem;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }
        .footer a {
            color: #667eea;
            text-decoration: none;
        }
        .alternative-link {
            word-break: break-all;
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 1rem;
        }
        @media (max-width: 600px) {
            .container {
                margin: 0;
                border-radius: 0;
            }
            .content {
                padding: 1.5rem;
            }
            .header {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ config('app.name') }}</h1>
            <p style="margin: 0.5rem 0 0 0; opacity: 0.9;">Bem-vindo à nossa comunidade!</p>
        </div>
        
        <div class="content">
            <p class="welcome-text">
                Olá <strong>{{ $user->name }}</strong>,
            </p>
            
            <p>
                Obrigado por se registrar no {{ config('app.name') }}! Para completar seu cadastro e começar a aproveitar todos os recursos da nossa plataforma, você precisa verificar seu endereço de email.
            </p>
            
            <div style="text-align: center; margin: 2rem 0;">
                <a href="{{ $verificationUrl }}" class="verification-button">
                    Verificar Meu Email
                </a>
            </div>
            
            <div class="info-box">
                <p style="margin: 0; font-weight: 600;">📧 Por que verificar meu email?</p>
                <ul style="margin: 0.5rem 0 0 0; padding-left: 1.25rem;">
                    <li>Garantir a segurança da sua conta</li>
                    <li>Receber notificações importantes</li>
                    <li>Recuperar sua senha se necessário</li>
                    <li>Acessar todos os recursos da plataforma</li>
                </ul>
            </div>
            
            <p>
                Se você não conseguir clicar no botão acima, copie e cole o link abaixo no seu navegador:
            </p>
            
            <div class="alternative-link">
                {{ $verificationUrl }}
            </div>
            
            <p style="margin-top: 2rem; font-size: 0.875rem; color: #6b7280;">
                <strong>Importante:</strong> Este link de verificação expira em 60 minutos por motivos de segurança.
            </p>
        </div>
        
        <div class="footer">
            <p>
                Se você não criou uma conta no {{ config('app.name') }}, pode ignorar este email com segurança.
            </p>
            <p>
                © {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.<br>
                <a href="{{ config('app.url') }}">{{ config('app.url') }}</a>
            </p>
        </div>
    </div>
</body>
</html>
