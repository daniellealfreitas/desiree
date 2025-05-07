<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'user_id',
        'invited_by',
        'status',
        'responded_at',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
    ];

    /**
     * Get the group that the invitation is for.
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Get the user that was invited.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user that sent the invitation.
     */
    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    /**
     * Accept the invitation.
     */
    public function accept(): void
    {
        $this->update([
            'status' => 'accepted',
            'responded_at' => now(),
        ]);

        // Add the user to the group
        $this->group->members()->attach($this->user_id, [
            'role' => 'member',
            'is_approved' => true,
            'joined_at' => now(),
        ]);

        // Increment the members count
        $this->group->increment('members_count');

        // Create a notification for the inviter
        Notification::create([
            'user_id' => $this->invited_by,
            'sender_id' => $this->user_id,
            'type' => 'group_invitation_accepted',
            'group_id' => $this->group_id
        ]);

        // Add points for joining a group
        UserPoint::addPoints(
            $this->user_id,
            'group_joined',
            5,
            "Entrou no grupo: {$this->group->name}",
            $this->group_id,
            Group::class
        );
    }

    /**
     * Decline the invitation.
     */
    public function decline(): void
    {
        $this->update([
            'status' => 'declined',
            'responded_at' => now(),
        ]);

        // Create a notification for the inviter
        Notification::create([
            'user_id' => $this->invited_by,
            'sender_id' => $this->user_id,
            'type' => 'group_invitation_declined',
            'group_id' => $this->group_id
        ]);
    }
}
