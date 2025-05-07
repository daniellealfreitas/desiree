<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'cover_image',
        'creator_id',
        'privacy',
        'posts_require_approval',
        'is_featured',
        'members_count',
    ];

    protected $casts = [
        'posts_require_approval' => 'boolean',
        'is_featured' => 'boolean',
        'members_count' => 'integer',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($group) {
            // Generate slug from name if not provided
            if (empty($group->slug)) {
                $group->slug = Str::slug($group->name);
            }
            
            // Ensure slug uniqueness
            $count = 1;
            $originalSlug = $group->slug;
            
            while (static::where('slug', $group->slug)->exists()) {
                $group->slug = $originalSlug . '-' . $count++;
            }
        });

        static::created(function ($group) {
            // Add creator as admin member
            $group->members()->attach($group->creator_id, [
                'role' => 'admin',
                'is_approved' => true,
                'joined_at' => now(),
            ]);

            // Add points for creating a group
            UserPoint::addPoints(
                $group->creator_id,
                'group_created',
                20,
                "Criou o grupo: {$group->name}",
                $group->id,
                Group::class
            );
        });
    }

    /**
     * Get the creator of the group.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Get the members of the group.
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_user')
                    ->withPivot('role', 'is_approved', 'joined_at')
                    ->withTimestamps();
    }

    /**
     * Get the admins of the group.
     */
    public function admins(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_user')
                    ->wherePivot('role', 'admin')
                    ->withPivot('joined_at')
                    ->withTimestamps();
    }

    /**
     * Get the moderators of the group.
     */
    public function moderators(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_user')
                    ->wherePivot('role', 'moderator')
                    ->withPivot('joined_at')
                    ->withTimestamps();
    }

    /**
     * Get the posts in the group.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get the invitations for the group.
     */
    public function invitations(): HasMany
    {
        return $this->hasMany(GroupInvitation::class);
    }

    /**
     * Check if a user is a member of the group.
     */
    public function isMember(User $user): bool
    {
        return $this->members()->where('user_id', $user->id)->where('is_approved', true)->exists();
    }

    /**
     * Check if a user is an admin of the group.
     */
    public function isAdmin(User $user): bool
    {
        return $this->members()->where('user_id', $user->id)
                    ->where('is_approved', true)
                    ->where('role', 'admin')
                    ->exists();
    }

    /**
     * Check if a user is a moderator of the group.
     */
    public function isModerator(User $user): bool
    {
        return $this->members()->where('user_id', $user->id)
                    ->where('is_approved', true)
                    ->where('role', 'moderator')
                    ->exists();
    }

    /**
     * Check if a user can manage the group.
     */
    public function canManage(User $user): bool
    {
        return $this->isAdmin($user) || $this->isModerator($user);
    }

    /**
     * Get the URL for the group's image.
     */
    public function getImageUrlAttribute(): string
    {
        return $this->image
            ? asset('storage/' . $this->image)
            : asset('images/default-group.jpg');
    }

    /**
     * Get the URL for the group's cover image.
     */
    public function getCoverImageUrlAttribute(): string
    {
        return $this->cover_image
            ? asset('storage/' . $this->cover_image)
            : asset('images/default-group-cover.jpg');
    }
}
