<div wire:poll.600s="checkFriendsStatus">
    @if(count($newOnlineFriends) > 0)
        <div
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => { show = false }, 5000)"
            class="fixed bottom-4 right-4 z-50 max-w-sm"
        >
            @foreach($newOnlineFriends as $friend)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 mb-2 flex items-center space-x-3 border-l-4 border-green-500 transform transition-transform duration-300 hover:scale-105">
                    <div class="relative flex-shrink-0">
                        <img src="{{ $friend['avatar'] ? asset($friend['avatar']) : asset('images/default-avatar.jpg') }}" class="w-10 h-10 rounded-full object-cover">
                        <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white dark:border-gray-800"></span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $friend['name'] }}</p>
                        <p class="text-xs text-green-600 dark:text-green-400">Acabou de ficar online</p>
                    </div>
                    <button
                        @click="show = false"
                        class="ml-auto text-gray-400 hover:text-gray-500 dark:hover:text-gray-300"
                    >
                        <x-flux::icon name="x-mark" class="w-4 h-4" />
                    </button>
                </div>
            @endforeach
        </div>
    @endif
</div>
