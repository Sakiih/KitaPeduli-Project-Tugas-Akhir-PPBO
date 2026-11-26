<?php

declare(strict_types=1);

namespace App\Models;

use DomainException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Donation extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';

    public const STATUS_SUCCESS = 'success';

    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'campaign_id',
        'user_id',
        'donor_name',
        'donor_email',
        'donor_phone',
        'amount',
        'donor_message',
        'is_anonymous',
        'status',
        'amen_count',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'is_anonymous' => 'boolean',
            'amen_count' => 'integer',
        ];
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(DonationReaction::class);
    }

    public function scopeSuccessful(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_SUCCESS);
    }

    public function isGuest(): bool
    {
        return $this->user_id === null;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isSuccessful(): bool
    {
        return $this->status === self::STATUS_SUCCESS;
    }

    public function publicDonorName(): string
    {
        return $this->is_anonymous ? 'Orang Baik (Anonim)' : $this->donor_name;
    }

    public function markAsSuccessful(): void
    {
        if ($this->status !== self::STATUS_PENDING) {
            throw new DomainException('Hanya donasi berstatus pending yang dapat diubah menjadi success.');
        }

        $this->status = self::STATUS_SUCCESS;
        $this->save();
    }

    public function markAsCancelled(): void
    {
        if ($this->status === self::STATUS_SUCCESS) {
            throw new DomainException('Donasi yang sudah berhasil tidak dapat dibatalkan.');
        }

        $this->status = self::STATUS_CANCELLED;
        $this->save();
    }

    public function refreshAmenCount(): void
    {
        $this->amen_count = $this->reactions()->count();
        $this->save();
    }

    /** @return array<string, mixed> */
    public function toPublicPrayerArray(?int $currentUserId = null): array
    {
        $hasAmened = false;
        if ($currentUserId !== null) {
            $hasAmened = $this->relationLoaded('reactions')
                ? $this->reactions->contains('user_id', $currentUserId)
                : $this->reactions()->where('user_id', $currentUserId)->exists();
        }

        return [
            'id' => $this->id,
            'campaign_id' => $this->campaign_id,
            'donor_name' => $this->publicDonorName(),
            'is_anonymous' => (bool) $this->is_anonymous,
            'amount' => (float) $this->amount,
            'formatted_amount' => 'Rp '.number_format((float) $this->amount, 0, ',', '.'),
            'donor_message' => $this->donor_message,
            'amen_count' => (int) $this->amen_count,
            'has_amened' => $hasAmened,
            'created_at' => $this->created_at?->toIso8601String(),
            'time_ago' => $this->created_at?->diffForHumans(),
        ];
    }
}
