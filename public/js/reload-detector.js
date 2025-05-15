/**
 * Reload Detector
 * 
 * This script helps identify when and why page reloads are happening.
 * It logs information about page loads, navigation events, and potential
 * causes of reloads.
 */

(function() {
    // Store the timestamp when the page was loaded
    const pageLoadTime = Date.now();
    
    // Log initial page load
    console.log('[Reload Detector] Page loaded at:', new Date().toISOString());
    
    // Track navigation events
    if (window.Livewire) {
        document.addEventListener('livewire:initialized', () => {
            // Track Livewire navigation events
            Livewire.hook('message.sent', (message, component) => {
                console.log('[Reload Detector] Livewire message sent:', message.updateQueue);
            });
            
            Livewire.hook('message.failed', (message, component) => {
                console.error('[Reload Detector] Livewire message failed:', message);
            });
            
            Livewire.hook('message.received', (message, component) => {
                console.log('[Reload Detector] Livewire message received');
            });
            
            // Track Livewire navigation events
            window.addEventListener('livewire:navigating', () => {
                console.log('[Reload Detector] Livewire navigation started');
            });
            
            window.addEventListener('livewire:navigated', () => {
                console.log('[Reload Detector] Livewire navigation completed');
            });
        });
    }
    
    // Track page visibility changes
    document.addEventListener('visibilitychange', () => {
        console.log('[Reload Detector] Page visibility changed:', document.visibilityState);
    });
    
    // Track before unload events
    window.addEventListener('beforeunload', (event) => {
        console.log('[Reload Detector] Page is about to unload');
        
        // Calculate how long the page was open
        const pageOpenDuration = (Date.now() - pageLoadTime) / 1000;
        console.log(`[Reload Detector] Page was open for ${pageOpenDuration.toFixed(2)} seconds`);
    });
    
    // Track polling events by monkey patching fetch and XMLHttpRequest
    const originalFetch = window.fetch;
    window.fetch = function(...args) {
        const url = args[0]?.url || args[0];
        if (typeof url === 'string' && url.includes('livewire/update')) {
            console.log('[Reload Detector] Livewire update request detected via fetch:', url);
        }
        return originalFetch.apply(this, args);
    };
    
    const originalXHROpen = XMLHttpRequest.prototype.open;
    XMLHttpRequest.prototype.open = function(...args) {
        const url = args[1];
        if (typeof url === 'string' && url.includes('livewire/update')) {
            console.log('[Reload Detector] Livewire update request detected via XHR:', url);
        }
        return originalXHROpen.apply(this, args);
    };
    
    // Track Alpine.js initialization
    if (window.Alpine) {
        console.log('[Reload Detector] Alpine.js already loaded on script execution');
    }
    
    // Check for multiple Alpine.js instances
    const checkAlpineInterval = setInterval(() => {
        if (window.Alpine && window._alpineInitialized) {
            console.log('[Reload Detector] Alpine.js initialized');
            clearInterval(checkAlpineInterval);
        }
    }, 100);
    
    // Log when the document is fully loaded
    window.addEventListener('load', () => {
        console.log('[Reload Detector] Window load event fired');
    });
    
    // Log when the DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            console.log('[Reload Detector] DOMContentLoaded event fired');
        });
    } else {
        console.log('[Reload Detector] DOM already loaded on script execution');
    }
})();
