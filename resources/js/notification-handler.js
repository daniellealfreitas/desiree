/**
 * Notification handler for Livewire 3 events
 *
 * This script listens for Livewire events and dispatches them to the notification component
 */
document.addEventListener('livewire:init', function () {
    console.log('Notification handler initialized');

    // Listen for Livewire notify events using Livewire 3 syntax
    Livewire.on('notify', (data) => {
        console.log('Notification received from Livewire:', data);

        // Create a notification object from the data
        const notification = {
            message: data.message || 'Nova notificação',
            type: data.type || 'info',
            timeout: data.timeout || 3000,
            avatar: data.avatar || null,
            sender_id: data.sender_id || null
        };

        console.log('Dispatching notification to window:', notification);

        // Dispatch the notification to the window
        window.dispatchEvent(new CustomEvent('notify', {
            detail: notification
        }));
    });

    // Also add a direct test method to the window for debugging
    window.testNotification = function (type = 'info') {
        console.log('Testing notification manually');
        window.dispatchEvent(new CustomEvent('notify', {
            detail: {
                message: 'Teste de notificação manual',
                type: type,
                timeout: 5000
            }
        }));
    };
});
