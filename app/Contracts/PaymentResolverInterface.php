<?php

declare(strict_types=1);

namespace App\Contracts;

interface PaymentResolverInterface
{
    public function resolve(string $methodCode): PaymentMethodInterface;

    /** @return array<string, array{code: string, label: string}> */
    public function supportedMethods(): array;
}
