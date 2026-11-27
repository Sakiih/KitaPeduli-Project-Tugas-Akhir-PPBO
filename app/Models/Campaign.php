<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use InvalidArgumentException;

class Campaign extends Model
{
    use HasFactory;

    public const STATUS_ACTIVE = 'active';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_INACTIVE = 'inactive';

    protected $fillable = [
        'category_id',
        'created_by',
        'title',
        'slug',
        'short_description',
        'story',
        'banner_image',
        'target_amount',
        'collected_amount',
        'donor_count',
        'start_date',
        'end_date',
        'organizer_name',
        'organizer_type',
        'organizer_description',
        'is_featured',
        'is_verified_organizer',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'target_amount' => 'decimal:2',
            'collected_amount' => 'decimal:2',
            'donor_count' => 'integer',
            'start_date' => 'date',
            'end_date' => 'date',
            'is_featured' => 'boolean',
            'is_verified_organizer' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function successfulDonations(): HasMany
    {
        return $this->hasMany(Donation::class)->where('status', Donation::STATUS_SUCCESS);
    }

    public function updates(): HasMany
    {
        return $this->hasMany(CampaignUpdate::class)->orderByDesc('published_at');
    }

    public function disbursementReports(): HasMany
    {
        return $this->hasMany(FundDisbursementReport::class)->orderByDesc('disbursed_at');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function isAcceptingDonations(): bool
    {
        if ($this->status !== self::STATUS_ACTIVE) {
            return false;
        }

        if ($this->end_date instanceof Carbon && $this->end_date->copy()->endOfDay()->isPast()) {
            return false;
        }

        return true;
    }

    public function progressPercentage(): int
    {
        $target = (float) $this->target_amount;
        if ($target <= 0) {
            return 0;
        }

        $percentage = (int) round(((float) $this->collected_amount / $target) * 100);

        return max(0, min(100, $percentage));
    }

    public function remainingDays(): int
    {
        if (! $this->end_date instanceof Carbon) {
            return 0;
        }

        $today = Carbon::today();
        if ($this->end_date->lt($today)) {
            return 0;
        }

        return (int) $today->diffInDays($this->end_date);
    }

    // Saldo kampanye hanya dihitung dari donasi berstatus success dengan pembayaran paid.
    public function recalculateCollectedAmount(): void
    {
        $stats = $this->donations()
            ->where('status', Donation::STATUS_SUCCESS)
            ->where('amount', '>', 0)
            ->whereHas('payment', fn (Builder $q) => $q->where('status', Payment::STATUS_PAID))
            ->selectRaw('COALESCE(SUM(amount), 0) as total_amount, COUNT(*) as total_donors')
            ->first();

        $this->collected_amount = $stats ? $stats->total_amount : 0;
        $this->donor_count = $stats ? (int) $stats->total_donors : 0;
        $this->save();
    }

    public function changeStatus(string $newStatus): void
    {
        if (! in_array($newStatus, [self::STATUS_ACTIVE, self::STATUS_COMPLETED, self::STATUS_INACTIVE], true)) {
            throw new InvalidArgumentException("Status kampanye tidak valid: {$newStatus}");
        }

        $this->status = $newStatus;
        $this->save();
    }

    public function bannerUrl(): string
    {
        if (! $this->banner_image) {
            return asset('images/campaigns/default-banner.svg');
        }

        if (str_starts_with($this->banner_image, 'http://') || str_starts_with($this->banner_image, 'https://') || str_starts_with($this->banner_image, '/')) {
            return $this->banner_image;
        }

        if (str_starts_with($this->banner_image, 'images/')) {
            return asset($this->banner_image);
        }

        return asset('storage/'.$this->banner_image);
    }
}
