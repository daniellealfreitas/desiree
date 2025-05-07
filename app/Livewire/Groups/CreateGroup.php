<?php

namespace App\Livewire\Groups;

use App\Models\Group;
use App\Models\UserPoint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateGroup extends Component
{
    use WithFileUploads;

    public $name = '';
    public $description = '';
    public $privacy = 'public';
    public $image;
    public $coverImage;
    public $postsRequireApproval = false;

    protected $rules = [
        'name' => 'required|string|min:3|max:255',
        'description' => 'nullable|string|max:1000',
        'privacy' => 'required|in:public,private,secret',
        'image' => 'nullable|image|max:2048',
        'coverImage' => 'nullable|image|max:2048',
        'postsRequireApproval' => 'boolean',
    ];

    public function create()
    {
        $this->validate();

        $slug = Str::slug($this->name);
        $count = 1;
        $originalSlug = $slug;
        
        while (Group::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $data = [
            'name' => $this->name,
            'slug' => $slug,
            'description' => $this->description,
            'privacy' => $this->privacy,
            'creator_id' => Auth::id(),
            'posts_require_approval' => $this->postsRequireApproval,
        ];

        // Handle image uploads
        if ($this->image) {
            $data['image'] = $this->image->store('groups/images', 'public');
        }

        if ($this->coverImage) {
            $data['cover_image'] = $this->coverImage->store('groups/covers', 'public');
        }

        $group = Group::create($data);

        // Add points for creating a group
        UserPoint::addPoints(
            Auth::id(),
            'group_created',
            20,
            "Criou o grupo: {$group->name}",
            $group->id,
            Group::class
        );

        session()->flash('success', 'Grupo criado com sucesso!');
        
        return redirect()->route('grupos.show', $group->slug);
    }

    public function render()
    {
        return view('livewire.groups.create-group');
    }
}
