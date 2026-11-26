<?php

declare(strict_types=1);

namespace App\Models;

use DomainException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    public const METHOD_QRIS = 'qris';

    public const METHOD_BANK_TRANSFER = 'bank_transfer';

    public const STATUS_PENDING = 'pending';

    public const STATUS_PAID = 'paid';

    protected $fillable = [
        'donation_id',
        'payment_method',
        'reference',
        'access_token_hash',
        'amount',
        'status',
        'instruction_payload',
        'paid_at',
    ];

    protected $hidden = [
        'access_token_hash',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'instruction_payload' => 'array',
            'paid_at' => 'datetime',
        ];
    }

    public function donation(): BelongsTo
    {
        return $this->belongsTo(Donation::class);
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    public function verifyAccessToken(?string $plainToken): bool
    {
        if (empty($plainToken) || empty($this->access_token_hash)) {
            return false;
        }

        return hash_equals($this->access_token_hash, hash('sha256', $plainToken));
    }

    public function markAsPaid(): void
    {
        if ($this->status !== self::STATUS_PENDING) {
            throw new DomainException('Transaksi pembayaran ini sudah dikonfirmasi sebelumnya atau tidak berstatus pending.');
        }

        $this->status = self::STATUS_PAID;
        $this->paid_at = now();
        $this->save();
    }
}
