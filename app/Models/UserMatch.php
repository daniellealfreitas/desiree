<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserMatch extends Model
{
    protected $fillable = [
        'user_id',
        'target_user_id',
        'liked',
        'is_matched',
        'matched_at'
    ];

    protected $casts = [
        'liked' => 'boolean',
        'is_matched' => 'boolean',
        'matched_at' => 'datetime',
    ];

    /**
     * Get the user that initiated the match.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the target user of the match.
     */
    public function targetUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }
}
