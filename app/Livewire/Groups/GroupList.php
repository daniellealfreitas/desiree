<?php

namespace App\Livewire\Groups;

use App\Models\Group;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class GroupList extends Component
{
    use WithPagination;

    public $search = '';
    public $filter = 'all';
    public $privacy = 'all';
    public $sort = 'newest';

    protected $queryString = [
        'search' => ['except' => ''],
        'filter' => ['except' => 'all'],
        'privacy' => ['except' => 'all'],
        'sort' => ['except' => 'newest'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilter()
    {
        $this->resetPage();
    }

    public function updatingPrivacy()
    {
        $this->resetPage();
    }

    public function updatingSort()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Group::query();
        
        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }
        
        // Apply membership filter
        if (Auth::check()) {
            if ($this->filter === 'my') {
                $query->whereHas('members', function ($q) {
                    $q->where('user_id', Auth::id());
                });
            } elseif ($this->filter === 'joined') {
                $query->whereHas('members', function ($q) {
                    $q->where('user_id', Auth::id())
                      ->where('is_approved', true);
                });
            } elseif ($this->filter === 'pending') {
                $query->whereHas('members', function ($q) {
                    $q->where('user_id', Auth::id())
                      ->where('is_approved', false);
                });
            } elseif ($this->filter === 'created') {
                $query->where('creator_id', Auth::id());
            } elseif ($this->filter === 'not-joined') {
                $query->whereDoesntHave('members', function ($q) {
                    $q->where('user_id', Auth::id());
                });
            }
        }
        
        // Apply privacy filter
        if ($this->privacy !== 'all') {
            $query->where('privacy', $this->privacy);
        } else {
            // Don't show secret groups in the public listing unless the user is a member
            if (!Auth::check()) {
                $query->where('privacy', '!=', 'secret');
            } else {
                $query->where(function ($q) {
                    $q->where('privacy', '!=', 'secret')
                      ->orWhereHas('members', function ($q2) {
                          $q2->where('user_id', Auth::id());
                      });
                });
            }
        }
        
        // Apply sorting
        if ($this->sort === 'newest') {
            $query->latest();
        } elseif ($this->sort === 'oldest') {
            $query->oldest();
        } elseif ($this->sort === 'popular') {
            $query->orderBy('members_count', 'desc');
        } elseif ($this->sort === 'alphabetical') {
            $query->orderBy('name', 'asc');
        }
        
        $groups = $query->paginate(12);
        
        return view('livewire.groups.group-list', [
            'groups' => $groups,
        ]);
    }
}
