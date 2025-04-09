<div>
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="uploadPhoto">
        <label for="photo">Upload Photo:</label>
        <input type="file" wire:model="photo" id="photo" accept="image/*">
        @error('photo') <span class="error">{{ $message }}</span> @enderror
        <button type="submit">Upload</button>
    </form>

    @if ($userPhoto)
        <img src="{{ $userPhoto }}" alt="User Photo" width="150">
    @endif
</div>
