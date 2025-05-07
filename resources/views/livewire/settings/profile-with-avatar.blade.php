<?php

use App\Models\UserPhoto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Volt\Component;
use Livewire\WithFileUploads; // Add WithFileUploads trait
use Illuminate\Support\Facades\Storage;

new class extends Component {
    use WithFileUploads; // Use the trait

    public $avatar;

    /**
     * Update the user's avatar.
     */
    public function updateAvatar(): void
    {
        try {
            $user = Auth::user();

            if (!$user) {
                throw new \Exception('Authenticated user not found.');
            }

            $validated = $this->validate([
                'avatar' => ['required', 'image', 'mimes:jpg,png', 'max:5120'], // Allow JPG/PNG and increase size limit to 5MB
            ]);

            if ($this->avatar) {
                // Store the file and get the path
                $avatarPath = $this->avatar->store('avatars', 'public');

                if (!$avatarPath) {
                    throw new \Exception('Failed to store the avatar.');
                }

                // Save avatar in user_photos table
                UserPhoto::create([
                    'user_id' => $user->id,
                    'photo_path' => $avatarPath,
                ]);
            }

            $this->dispatch('avatar-updated');
        } catch (\Throwable $e) {
            Log::error('Error updating avatar: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString(),
            ]);

            session()->flash('error', __('Ocorreu um erro ao atualizar a foto. Tente novamente.'));
        }
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Avatar')" :subheading="__('Atualizar sua foto de perfil')">
        <form wire:submit.prevent="updateAvatar" class="my-6 w-full space-y-6">
            <div>
                <x-file-upload wire:model="avatar" :label="__('Avatar')" accept="image/png, image/jpeg" icon="user" :iconVariant="$avatar ? 'solid' : 'outline'" />
                @if (auth()->user() && auth()->user()->userPhotos()->latest()->first())
                    <img src="{{ Storage::url(auth()->user()->userPhotos()->latest()->first()->photo_path) }}" alt="Avatar" class="mt-4 w-20 h-20 rounded-full">
                @endif
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Salvar') }}</flux:button>
                </div>

                <x-action-message class="me-3" on="avatar-updated">
                    {{ __('Foto atualizada.') }}
                </x-action-message>

                @if (session('error'))
                    <flux:text class="mt-2 font-medium !dark:text-red-400 !text-red-600">
                        {{ session('error') }}
                    </flux:text>
                @endif
            </div>
        </form>
    </x-settings.layout>
</section>
