/**
 * Script para testar notificações de mensagens
 * 
 * Este script adiciona um botão escondido na página que, quando clicado,
 * simula o recebimento de uma nova mensagem para testar o sistema de notificações.
 */
document.addEventListener('DOMContentLoaded', function() {
    // Criar botão de teste
    const testButton = document.createElement('button');
    testButton.id = 'test-message-notification-button';
    testButton.textContent = 'Testar Notificação de Mensagem';
    testButton.style.position = 'fixed';
    testButton.style.bottom = '10px';
    testButton.style.right = '10px';
    testButton.style.zIndex = '9999';
    testButton.style.padding = '8px 16px';
    testButton.style.backgroundColor = '#6b46c1';
    testButton.style.color = 'white';
    testButton.style.border = 'none';
    testButton.style.borderRadius = '4px';
    testButton.style.cursor = 'pointer';
    
    // Adicionar botão ao corpo da página
    document.body.appendChild(testButton);
    
    // Adicionar evento de clique
    testButton.addEventListener('click', function() {
        console.log('Testando notificação de mensagem...');
        
        // Verificar se o componente MessageNotifier existe
        if (Livewire.getByName('message-notifier')) {
            console.log('Componente message-notifier encontrado, enviando notificação de teste');
            
            // Chamar o método testMessageNotification do componente
            Livewire.getByName('message-notifier')[0].call('testMessageNotification');
        } else {
            console.error('Componente message-notifier não encontrado');
            
            // Tentar enviar via toast-notification diretamente
            if (Livewire.getByName('toast-notification')) {
                console.log('Enviando notificação diretamente para toast-notification');
                
                Livewire.getByName('toast-notification')[0].call('showToast',
                    'Usuário Teste: Esta é uma mensagem de teste',
                    'message',
                    5000,
                    null,
                    1
                );
            } else {
                console.error('Nenhum componente de notificação encontrado');
            }
        }
    });
});
