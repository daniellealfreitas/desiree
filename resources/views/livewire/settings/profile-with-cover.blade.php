<?php

use App\Models\UserCoverPhoto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    use WithFileUploads;

    public $cover;

    /**
     * Update the user's cover photo.
     */
    public function updateCover(): void
    {
        try {
            $user = Auth::user();

            if (!$user) {
                throw new \Exception('Authenticated user not found.');
            }

            $validated = $this->validate([
                'cover' => ['required', 'image', 'mimes:jpg,png', 'max:5120'], // Allow JPG/PNG and increase size limit to 5MB
            ]);

            if ($this->cover) {
                // Store the file and get the path
                $coverPath = $this->cover->store('covers', 'public');

                if (!$coverPath) {
                    throw new \Exception('Failed to store the cover photo.');
                }

                // Save cover photo in user_cover_photos table
                UserCoverPhoto::create([
                    'user_id' => $user->id,
                    'photo_path' => $coverPath,
                ]);
            }

            $this->dispatch('cover-updated');
        } catch (\Throwable $e) {
            Log::error('Error updating cover photo: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString(),
            ]);

            session()->flash('error', __('Ocorreu um erro ao atualizar a capa. Tente novamente.'));
        }
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Capa')" :subheading="__('Atualizar sua foto de capa')">
        <form wire:submit.prevent="updateCover" class="my-6 w-full space-y-6">
            <div>
                <x-file-upload wire:model="cover" :label="__('Capa')" accept="image/png, image/jpeg" icon="photo" :iconVariant="$cover ? 'solid' : 'outline'" />
                @if (auth()->user() && auth()->user()->userCoverPhotos()->latest()->first())
                    <img src="{{ Storage::url(auth()->user()->userCoverPhotos()->latest()->first()->photo_path) }}" alt="Capa" class="mt-4 w-full h-40 rounded">
                @endif
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Salvar') }}</flux:button>
                </div>

                <x-action-message class="me-3" on="cover-updated">
                    {{ __('Capa atualizada.') }}
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
