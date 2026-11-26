<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Donation;
use App\Models\Payment;

interface PaymentMethodInterface
{
    public function getCode(): string;

    public function getLabel(): string;

    /** @return array<string, mixed> */
    public function createInstruction(Payment $payment, Donation $donation): array;
}
