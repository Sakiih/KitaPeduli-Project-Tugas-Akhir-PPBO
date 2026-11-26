<?php

declare(strict_types=1);

namespace App\Payments;

use App\Contracts\PaymentMethodInterface;
use App\Models\Donation;
use App\Models\Payment;

abstract class PaymentMethod implements PaymentMethodInterface
{
    public const SIMULATION_DISCLAIMER = 'SIMULASI / DEMO: Bukan pembayaran nyata';

    protected string $code;

    protected string $label;

    public function getCode(): string
    {
        return $this->code;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function formatCurrency(float $amount): string
    {
        return 'Rp '.number_format($amount, 0, ',', '.');
    }

    /** @return array<string, mixed> */
    protected function buildBaseMetadata(Payment $payment, Donation $donation): array
    {
        $amount = (float) $payment->amount;

        return [
            'method_code' => $this->getCode(),
            'method_label' => $this->getLabel(),
            'is_simulation' => true,
            'simulation_notice' => self::SIMULATION_DISCLAIMER,
            'reference' => $payment->reference,
            'amount' => $amount,
            'formatted_amount' => $this->formatCurrency($amount),
            'donor_name' => $donation->donor_name,
            'campaign_title' => $donation->campaign?->title ?? 'Kampanye KitaPeduli',
        ];
    }

    /** @return array<string, mixed> */
    abstract public function createInstruction(Payment $payment, Donation $donation): array;
}
