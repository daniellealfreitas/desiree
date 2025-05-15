/**
 * Notification handler for Livewire events
 * 
 * This script listens for Livewire events and dispatches them to the notification component
 */
document.addEventListener('DOMContentLoaded', function() {
    // Listen for Livewire notify events
    window.addEventListener('notify', event => {
        const notification = event.detail;
        window.dispatchEvent(new CustomEvent('notify', {
            detail: {
                message: notification.message,
                type: notification.type || 'info',
                timeout: notification.timeout || 3000
            }
        }));
    });
});
