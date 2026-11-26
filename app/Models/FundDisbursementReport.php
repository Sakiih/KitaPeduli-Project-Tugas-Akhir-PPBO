<?php

declare(strict_types=1);

namespace App\Models;

use DomainException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FundDisbursementReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'created_by',
        'title',
        'amount',
        'recipient_name',
        'purpose',
        'description',
        'document_image',
        'disbursed_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'disbursed_at' => 'date',
        ];
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function documentUrl(): ?string
    {
        if (! $this->document_image) {
            return null;
        }

        if (str_starts_with($this->document_image, 'http://') || str_starts_with($this->document_image, 'https://') || str_starts_with($this->document_image, '/')) {
            return $this->document_image;
        }

        if (str_starts_with($this->document_image, 'images/')) {
            return asset($this->document_image);
        }

        return asset('storage/'.$this->document_image);
    }

    public static function availableBalanceForCampaign(int $campaignId, ?int $excludeReportId = null): float
    {
        $totalCollected = (float) Donation::where('campaign_id', $campaignId)
            ->where('status', Donation::STATUS_SUCCESS)
            ->sum('amount');

        $totalDisbursed = (float) self::where('campaign_id', $campaignId)
            ->when($excludeReportId !== null, fn ($q) => $q->where('id', '!=', $excludeReportId))
            ->sum('amount');

        return max(0.0, $totalCollected - $totalDisbursed);
    }

    /** @param array<string, mixed> $data */
    public static function recordForCampaign(int $campaignId, User $admin, array $data, ?UploadedFile $documentFile = null): self
    {
        $amount = (float) ($data['amount'] ?? 0);
        if ($amount <= 0) {
            throw new DomainException('Nominal pencairan dana harus bernilai positif.');
        }

        return DB::transaction(function () use ($campaignId, $admin, $data, $amount, $documentFile): self {
            Campaign::where('id', $campaignId)->lockForUpdate()->firstOrFail();

            $available = self::availableBalanceForCampaign($campaignId);
            if ($amount > $available) {
                $formattedAvailable = 'Rp '.number_format($available, 0, ',', '.');
                throw new DomainException("Nominal pencairan melebihi saldo kampanye yang tersedia ({$formattedAvailable}).");
            }

            $documentPath = $documentFile instanceof UploadedFile
                ? $documentFile->store('disbursement_reports', 'public')
                : null;

            return self::create([
                'campaign_id' => $campaignId,
                'created_by' => $admin->id,
                'title' => trim((string) $data['title']),
                'amount' => $amount,
                'recipient_name' => trim((string) $data['recipient_name']),
                'purpose' => trim((string) $data['purpose']),
                'description' => (string) $data['description'],
                'document_image' => $documentPath,
                'disbursed_at' => $data['disbursed_at'],
            ]);
        });
    }

    /** @param array<string, mixed> $data */
    public static function updateRecord(self $report, array $data, ?UploadedFile $documentFile = null): self
    {
        $amount = (float) ($data['amount'] ?? 0);
        if ($amount <= 0) {
            throw new DomainException('Nominal pencairan dana harus bernilai positif.');
        }

        return DB::transaction(function () use ($report, $data, $amount, $documentFile): self {
            Campaign::where('id', $report->campaign_id)->lockForUpdate()->firstOrFail();
            $lockedReport = self::where('id', $report->id)->lockForUpdate()->firstOrFail();

            $available = self::availableBalanceForCampaign((int) $lockedReport->campaign_id, (int) $lockedReport->id);
            if ($amount > $available) {
                $formattedAvailable = 'Rp '.number_format($available, 0, ',', '.');
                throw new DomainException("Nominal pencairan melebihi saldo kampanye yang tersedia ({$formattedAvailable}).");
            }

            if ($documentFile instanceof UploadedFile) {
                if ($lockedReport->document_image && Storage::disk('public')->exists($lockedReport->document_image)) {
                    Storage::disk('public')->delete($lockedReport->document_image);
                }
                $lockedReport->document_image = $documentFile->store('disbursement_reports', 'public');
            }

            $lockedReport->fill([
                'title' => trim((string) $data['title']),
                'amount' => $amount,
                'recipient_name' => trim((string) $data['recipient_name']),
                'purpose' => trim((string) $data['purpose']),
                'description' => (string) $data['description'],
                'disbursed_at' => $data['disbursed_at'],
            ]);
            $lockedReport->save();

            return $lockedReport->fresh();
        });
    }

    public static function deleteRecord(self $report): void
    {
        DB::transaction(function () use ($report): void {
            $lockedReport = self::where('id', $report->id)->lockForUpdate()->first();
            if (! $lockedReport) {
                return;
            }

            $documentImage = $lockedReport->document_image;
            $lockedReport->delete();

            if ($documentImage && Storage::disk('public')->exists($documentImage)) {
                Storage::disk('public')->delete($documentImage);
            }
        });
    }
}
