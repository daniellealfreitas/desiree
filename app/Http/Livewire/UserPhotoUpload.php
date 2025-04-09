<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class UserPhotoUpload extends Component
{
    use WithFileUploads;

    public $photo;

    public function uploadPhoto()
    {
        $this->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = auth()->user();

        // Delete old photo if exists
        if ($user->photo) {
            Storage::delete('public/' . $user->photo);
        }

        // Store new photo
        $path = $this->photo->store('photos', 'public');
        $user->photo = $path; // Save the path to the database
        $user->save(); // Persist the changes to the database

        session()->flash('success', 'Photo uploaded successfully!');
    }

    public function render()
    {
        return view('livewire.user-photo-upload', [
            'userPhoto' => auth()->user()->photo ? asset('storage/' . auth()->user()->photo) : null,
        ]);
    }
}
