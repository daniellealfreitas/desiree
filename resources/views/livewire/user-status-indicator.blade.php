<span 
    wire:poll.20s="refreshStatus"
    class="absolute left-1 top-1 transform translate-x-1/4 -translate-y-1/4 w-3 h-3 rounded-full border-1 border-white 
        @if($status=='online') bg-green-500 
        @elseif($status=='away') bg-yellow-400 
        @else bg-red-500 
        @endif
        shadow"
    title="{{ ucfirst($status) }}"
    aria-label="status do usuário"
></span>