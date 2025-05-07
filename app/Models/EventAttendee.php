<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventAttendee extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'status',
        'ticket_code',
        'payment_status',
        'payment_method',
        'payment_id',
        'amount_paid',
        'paid_at',
        'checked_in_at',
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'paid_at' => 'datetime',
        'checked_in_at' => 'datetime',
    ];

    /**
     * Get the event that the attendee is registered for.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the user that is attending the event.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate a unique ticket code.
     */
    public static function generateTicketCode(): string
    {
        $code = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
        
        // Ensure the code is unique
        while (self::where('ticket_code', $code)->exists()) {
            $code = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
        }
        
        return $code;
    }

    /**
     * Mark the attendee as checked in.
     */
    public function checkIn(): void
    {
        $this->update([
            'status' => 'attended',
            'checked_in_at' => now(),
        ]);
    }

    /**
     * Mark the payment as completed.
     */
    public function markPaymentCompleted(string $paymentId, string $paymentMethod, float $amountPaid): void
    {
        $this->update([
            'payment_status' => 'completed',
            'payment_id' => $paymentId,
            'payment_method' => $paymentMethod,
            'amount_paid' => $amountPaid,
            'paid_at' => now(),
            'status' => 'confirmed',
        ]);

        // Generate ticket code if not already generated
        if (!$this->ticket_code) {
            $this->update([
                'ticket_code' => self::generateTicketCode(),
            ]);
        }
    }

    /**
     * Mark the payment as failed.
     */
    public function markPaymentFailed(): void
    {
        $this->update([
            'payment_status' => 'failed',
        ]);
    }

    /**
     * Cancel the registration.
     */
    public function cancel(): void
    {
        $this->update([
            'status' => 'cancelled',
        ]);
    }

    /**
     * Refund the payment.
     */
    public function refund(): void
    {
        $this->update([
            'payment_status' => 'refunded',
            'status' => 'cancelled',
        ]);
    }
}
