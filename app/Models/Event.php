<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'date',
        'start_time',
        'end_time',
        'image',
        'cover_image',
        'price',
        'capacity',
        'location',
        'address',
        'city',
        'state',
        'zip_code',
        'is_featured',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'price' => 'decimal:2',
        'capacity' => 'integer',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            // Generate slug from name if not provided
            if (empty($event->slug)) {
                $event->slug = Str::slug($event->name);
            }

            // Ensure slug uniqueness
            $count = 1;
            $originalSlug = $event->slug;

            while (static::where('slug', $event->slug)->exists()) {
                $event->slug = $originalSlug . '-' . $count++;
            }
        });
    }

    /**
     * Get the creator of the event.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the attendees of the event.
     */
    public function attendees()
    {
        return $this->belongsToMany(User::class, 'event_attendees')
                    ->withPivot('status', 'ticket_code', 'payment_status', 'payment_method', 'payment_id', 'amount_paid', 'paid_at', 'checked_in_at')
                    ->withTimestamps();
    }

    /**
     * Get the URL for the event's image.
     */
    public function getImageUrlAttribute(): string
    {
        return $this->image
            ? asset('storage/' . $this->image)
            : asset('images/default-event.jpg');
    }

    /**
     * Get the URL for the event's cover image.
     */
    public function getCoverImageUrlAttribute(): string
    {
        return $this->cover_image
            ? asset('storage/' . $this->cover_image)
            : asset('images/default-event-cover.jpg');
    }

    /**
     * Get the formatted date of the event.
     */
    public function getFormattedDateAttribute(): string
    {
        return Carbon::parse($this->date)->format('d/m/Y');
    }

    /**
     * Get the formatted start time of the event.
     */
    public function getFormattedStartTimeAttribute(): string
    {
        return Carbon::parse($this->start_time)->format('H:i');
    }

    /**
     * Get the formatted end time of the event.
     */
    public function getFormattedEndTimeAttribute(): string
    {
        return $this->end_time ? Carbon::parse($this->end_time)->format('H:i') : '';
    }

    /**
     * Get the formatted price of the event.
     */
    public function getFormattedPriceAttribute(): string
    {
        if ($this->price == 0) {
            return 'Grátis';
        }

        return 'R$ ' . number_format($this->price, 2, ',', '.');
    }

    /**
     * Check if the event is free.
     */
    public function getIsFreeAttribute(): bool
    {
        return $this->price == 0;
    }

    /**
     * Check if the event has passed.
     */
    public function getHasPassedAttribute(): bool
    {
        return Carbon::parse($this->date)->isPast();
    }

    /**
     * Check if the event is today.
     */
    public function getIsTodayAttribute(): bool
    {
        return Carbon::parse($this->date)->isToday();
    }

    /**
     * Check if the event is in the future.
     */
    public function getIsFutureAttribute(): bool
    {
        return Carbon::parse($this->date)->isFuture();
    }

    /**
     * Check if the event is sold out.
     */
    public function getIsSoldOutAttribute(): bool
    {
        if (!$this->capacity) {
            return false;
        }

        return $this->attendees()->where('event_attendees.status', '!=', 'cancelled')->count() >= $this->capacity;
    }

    /**
     * Get the number of available spots.
     */
    public function getAvailableSpotsAttribute(): int
    {
        if (!$this->capacity) {
            return PHP_INT_MAX;
        }

        $registered = $this->attendees()->where('event_attendees.status', '!=', 'cancelled')->count();

        return max(0, $this->capacity - $registered);
    }

    /**
     * Get the day of the week for the event.
     */
    public function getDayOfWeekAttribute(): string
    {
        $dayOfWeek = Carbon::parse($this->date)->dayOfWeek;

        $days = [
            0 => 'Domingo',
            1 => 'Segunda-feira',
            2 => 'Terça-feira',
            3 => 'Quarta-feira',
            4 => 'Quinta-feira',
            5 => 'Sexta-feira',
            6 => 'Sábado',
        ];

        return $days[$dayOfWeek];
    }

    /**
     * Scope a query to only include active events.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include featured events.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to only include upcoming events.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', now()->format('Y-m-d'));
    }

    /**
     * Scope a query to only include past events.
     */
    public function scopePast($query)
    {
        return $query->where('date', '<', now()->format('Y-m-d'));
    }

    /**
     * Scope a query to only include events on specific days of the week.
     */
    public function scopeOnDaysOfWeek($query, array $days)
    {
        // SQLite uses strftime('%w') which returns 0-6 (0=Sunday, 6=Saturday)
        // Convert MySQL DAYOFWEEK (1-7, 1=Sunday) to SQLite format (0-6)
        $sqliteDays = array_map(function($day) {
            return $day - 1;
        }, $days);

        return $query->whereRaw("strftime('%w', date) IN (" . implode(',', $sqliteDays) . ")");
    }

    /**
     * Scope a query to only include events on Wednesdays, Fridays, and Saturdays.
     */
    public function scopeOnEventDays($query)
    {
        // SQLite: 0=Sunday, 1=Monday, ..., 6=Saturday
        // So Wednesday=3, Friday=5, Saturday=6
        return $query->whereRaw("strftime('%w', date) IN (3, 5, 6)"); // Wednesday, Friday, Saturday
    }
}
