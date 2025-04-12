<div>
    <button wire:click="toggleFollow" class="px-4 py-2 bg-blue-500 text-white rounded">
        {{ $isFollowing ? __('Unfollow') : __('Follow') }}
    </button>
</div>
