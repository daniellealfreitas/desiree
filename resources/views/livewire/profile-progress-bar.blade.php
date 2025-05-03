<section id="progress-bar" class="max-w-lg mx-auto mt-6">
    <flux:text>Preenchimento de perfil: {{ $profileCompletion }}%</flux:text>
    <div class="mb-2 flex h-5 overflow-hidden rounded text-xs border border-gray-400 bg-gray-100">
        <div 
            style="width: {{ $profileCompletion }}%;" 
            class="{{ $progressColor }} transition-all duration-700 ease-in-out">
        </div>
    </div>
</section>