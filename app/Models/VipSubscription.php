<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class VipSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_days',
        'amount',
        'status',
        'stripe_session_id',
        'stripe_payment_id',
        'activated_at',
        'expires_at',
    ];

    protected $casts = [
        'amount' => 'float',
        'plan_days' => 'integer',
        'activated_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the user that owns the subscription
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the subscription is active
     */
    public function isActive()
    {
        return $this->status === 'active' && $this->expires_at > Carbon::now();
    }

    /**
     * Check if the subscription has expired
     */
    public function hasExpired()
    {
        return $this->status === 'active' && $this->expires_at <= Carbon::now();
    }

    /**
     * Get the remaining days of the subscription
     */
    public function getRemainingDays()
    {
        if (!$this->isActive()) {
            return 0;
        }

        return Carbon::now()->diffInDays($this->expires_at);
    }
}
