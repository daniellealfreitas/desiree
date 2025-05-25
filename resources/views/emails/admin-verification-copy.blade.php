<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>[ADMIN] Novo Usuário Registrado - {{ config('app.name') }}</title>
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
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 1.875rem;
            font-weight: 700;
        }
        .admin-badge {
            background-color: rgba(255, 255, 255, 0.2);
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 0.5rem;
            display: inline-block;
        }
        .content {
            padding: 2rem;
        }
        .user-info {
            background-color: #f3f4f6;
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1.5rem 0;
        }
        .user-info h3 {
            margin: 0 0 1rem 0;
            color: #1f2937;
            font-size: 1.125rem;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            padding: 0.5rem 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        .info-label {
            font-weight: 600;
            color: #374151;
        }
        .info-value {
            color: #6b7280;
        }
        .verification-link {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 6px;
            padding: 1rem;
            margin: 1.5rem 0;
        }
        .verification-link h4 {
            margin: 0 0 0.5rem 0;
            color: #92400e;
        }
        .verification-link a {
            color: #d97706;
            word-break: break-all;
            font-size: 0.875rem;
        }
        .footer {
            background-color: #f9fafb;
            padding: 1.5rem;
            text-align: center;
            font-size: 0.875rem;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
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
            .info-row {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ config('app.name') }}</h1>
            <div class="admin-badge">CÓPIA ADMINISTRATIVA</div>
            <p style="margin: 0.5rem 0 0 0; opacity: 0.9;">Novo usuário registrado</p>
        </div>
        
        <div class="content">
            <p>
                <strong>Olá Administrador,</strong>
            </p>
            
            <p>
                Um novo usuário se registrou na plataforma {{ config('app.name') }} e recebeu um email de verificação. Abaixo estão os detalhes do registro:
            </p>
            
            <div class="user-info">
                <h3>📋 Informações do Usuário</h3>
                
                <div class="info-row">
                    <span class="info-label">Nome:</span>
                    <span class="info-value">{{ $user->name }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $user->email }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Username:</span>
                    <span class="info-value">{{ $user->username }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Role:</span>
                    <span class="info-value">{{ ucfirst($user->role) }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Data de Registro:</span>
                    <span class="info-value">{{ $user->created_at->format('d/m/Y H:i:s') }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Status de Verificação:</span>
                    <span class="info-value">
                        @if($user->hasVerifiedEmail())
                            ✅ Verificado
                        @else
                            ⏳ Pendente
                        @endif
                    </span>
                </div>
            </div>
            
            <div class="verification-link">
                <h4>🔗 Link de Verificação Enviado:</h4>
                <a href="{{ $verificationUrl }}">{{ $verificationUrl }}</a>
                <p style="margin: 0.5rem 0 0 0; font-size: 0.875rem; color: #92400e;">
                    <strong>Nota:</strong> Este link expira em 60 minutos.
                </p>
            </div>
            
            <p style="margin-top: 2rem; font-size: 0.875rem; color: #6b7280;">
                <strong>Ações Recomendadas:</strong>
            </p>
            <ul style="font-size: 0.875rem; color: #6b7280;">
                <li>Monitorar se o usuário completa a verificação</li>
                <li>Verificar se não há atividade suspeita</li>
                <li>Dar boas-vindas ao usuário após a verificação</li>
            </ul>
        </div>
        
        <div class="footer">
            <p>
                Esta é uma notificação automática do sistema de registro.<br>
                {{ config('app.name') }} - {{ date('Y') }}
            </p>
        </div>
    </div>
</body>
</html>
