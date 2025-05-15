# JavaScript Resources in the Project

## Main Application JavaScript
- `resources/js/app.js` - Main application JavaScript file loaded via Vite
- `resources/js/notification-handler.js` - Handles notifications for Livewire events
- `resources/css/app.css` - Main CSS file loaded via Vite

## Debug Scripts (Can be removed)
- `public/js/cart-debug.js` - Debugging script for cart functionality
- `public/js/reload-detector.js` - Detects and logs page reloads
- `public/js/page-reload-fix.js` - Attempts to prevent unnecessary page reloads
- `public/js/cart-events-fix.js` - Fixes for cart event propagation issues
- `public/js/direct-cart-handler.js` - Alternative cart handling via AJAX

## Functional Scripts (Should be kept)
- `public/js/auto-geolocation.js` - Handles automatic geolocation detection
- `public/js/bootstrap.min.js` - Bootstrap JavaScript library
- `public/js/jquery.min.js` - jQuery library
- `public/js/jquery.sticky.js` - jQuery sticky plugin
- `public/js/click-scroll.js` - Handles click scrolling
- `public/js/custom.js` - Custom JavaScript functions

## Commented Out in vite.config.js
The following scripts are already commented out in vite.config.js:
```javascript
// 'resources/js/alpine-early-init.js', // Carregar primeiro para aplicar o patch
// 'public/js/reload-detector.js', // Detector de recargas
// 'public/js/page-reload-fix.js', // Correção para recargas
// 'public/js/cart-events-fix.js', // Correção para eventos do carrinho
// 'public/js/direct-cart-handler.js' // Manipulador direto para o carrinho
```

## Framework Scripts
- `@vite(['resources/css/app.css', 'resources/js/app.js'])` - Vite bundled resources
- `@fluxScripts` - Flux UI scripts
- `@livewireScripts` - Livewire scripts

## Debug Scripts in Layouts
In `components/layouts/app/header.blade.php`:
```html
<!-- Script de depuração do carrinho -->
<script src="{{ asset('js/cart-debug.js') }}"></script>
```

In `components/layouts/app/sidebar.blade.php`:
```javascript
// Function to trigger XP popup
window.triggerXpPopup = function(points) {
    // Create a popup element dynamically
    const popup = document.createElement('div');
    popup.textContent = `+${points} XP!`;
    popup.className = 'fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-white text-4xl font-bold bg-blue-500 px-6 py-3 rounded-lg shadow-lg animate-pulse';
    document.body.appendChild(popup);

    // Remove popup after 2 seconds
    setTimeout(() => {
        popup.remove();
    }, 2000);
};

// Add keyboard shortcut to trigger animations
document.addEventListener('keydown', function(event) {
    if (event.key === 'F10') {
        // Trigger both animations when F10 is pressed
        window.triggerConfetti();
        window.triggerXpPopup(50); // Example: 50 XP
    }
});
```

```javascript
// Adicionar listener para eventos do Livewire
document.addEventListener('livewire:initialized', () => {
    console.log('[Cart Debug] Livewire inicializado, adicionando listeners para depuração');

    // Listener para o evento add-to-cart
    Livewire.on('add-to-cart', (data) => {
        console.log('[Cart Debug] Evento add-to-cart recebido:', data);
    });

    // Listener para o evento cart-updated
    Livewire.on('cart-updated', () => {
        console.log('[Cart Debug] Evento cart-updated recebido');

        // Forçar atualização da página após 1 segundo
        setTimeout(() => {
            console.log('[Cart Debug] Atualizando a página para refletir as mudanças no carrinho');
            window.location.reload();
        }, 1000);
    });
});
```

## Recommendations for Removal
1. Remove `cart-debug.js` script tag from header.blade.php
2. Remove debug console.log statements and forced page reloads in sidebar.blade.php
3. Keep the XP popup functionality if it's a feature, but remove the F10 keyboard shortcut for triggering it
4. Remove or comment out the debug scripts in public/js directory if they're not needed
