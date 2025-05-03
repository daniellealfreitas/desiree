<?php

use function Livewire\Volt\{state, computed};
use App\Models\Post;

state(['showModal' => false, 'selectedImage' => '']);

$posts = computed(function () {
    return Post::where('image', '!=', null)
        ->latest()
        ->take(10)
        ->get();
});

$openModal = function($image) {
    $this->selectedImage = $image;
    $this->showModal = true;
};

$closeModal = function() {
    $this->showModal = false;
};

?>

<div>
    <h3 class="text-white bg-gray-800 p-3 mt-4 rounded-t-lg font-semibold">Últimas Imagens</h3>
    
    <div class="grid grid-cols-2 gap-2 p-3">
        @foreach($this->posts as $post)
            <div class="cursor-pointer" wire:click="openModal('{{ Storage::url($post->image) }}')">
                <img src="{{ Storage::url($post->image) }}" 
                     class="w-full h-32 object-cover rounded-lg hover:opacity-75 transition"
                     alt="Post image">
            </div>
        @endforeach
    </div>

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="relative">
                <button wire:click="closeModal" 
                        class="absolute -top-8 right-0 text-white hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <img src="{{ $selectedImage }}" class="max-h-[80vh] max-w-[90vw] rounded-lg">
            </div>
        </div>
    @endif
</div>
