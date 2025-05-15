<button 
    wire:click="toggleLike"
    class="flex flex-col items-center"
>
    <svg 
        class="w-8 h-8 {{ $isLiked ? 'text-red-500 fill-current' : 'text-white' }}"
        fill="none" 
        stroke="currentColor" 
        viewBox="0 0 24 24"
    >
        <path 
            stroke-linecap="round" 
            stroke-linejoin="round" 
            stroke-width="2" 
            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
        ></path>
    </svg>
    <span class="text-white text-xs mt-1">{{ $likesCount }}</span>
</button>
