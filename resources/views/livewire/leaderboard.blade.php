<?php
use App\Models\User;
use Illuminate\Support\Facades\Auth;
?>

<div id="ranking" class="pb-6 border border-neutral-200 dark:border-neutral-700 relative rounded-lg shadow-md">
    <h3 class="text-white bg-zinc-700 p-3 rounded-t-lg font-semibold">Ranking</h3>
    <div class="p-3 space-y-3">
        @forelse($topUsers as $index => $user)
            <div class="flex items-center justify-between bg-gray-50 dark:bg-zinc-800 p-3 rounded-lg border border-gray-200 dark:border-zinc-700">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-6 h-6 bg-{{ $index < 3 ? ['yellow', 'gray', 'amber'][$index] : 'gray' }}-500 text-white rounded-full text-xs font-bold">
                        {{ $index + 1 }}º
                    </div>
                    <div class="relative">
                        <img src="{{ $user->avatar ?? asset('images/users/avatar.jpg') }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full object-cover">
                        <livewire:user-status-indicator :userId="$user->id" />
                    </div>
                    <div>
                        <a href="/{{ $user->username }}" class="font-semibold hover:underline text-sm text-gray-300">{{ $user->name }}</a>
                        <p class="text-xs text-gray-500">{{ '@' . $user->username }}</p>
                    </div>
                </div>
                <div class="text-sm font-semibold text-gray-300">
                    {{ $user->ranking_points }} pts
                </div>
            </div>
        @empty
            <div class="text-center py-4">
                <p class="text-gray-500">Nenhum usuário no ranking ainda</p>
            </div>
        @endforelse
    </div>
</div>
