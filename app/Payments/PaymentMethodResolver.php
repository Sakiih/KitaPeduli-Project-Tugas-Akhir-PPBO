<?php

declare(strict_types=1);

namespace App\Payments;

use App\Contracts\PaymentMethodInterface;
use App\Contracts\PaymentResolverInterface;
use InvalidArgumentException;

class PaymentMethodResolver implements PaymentResolverInterface
{
    /** @var array<string, PaymentMethodInterface> */
    private array $methods = [];

    /** @param iterable<PaymentMethodInterface> $paymentMethods */
    public function __construct(iterable $paymentMethods = [])
    {
        foreach ($paymentMethods as $method) {
            $this->register($method);
        }
    }

    public function register(PaymentMethodInterface $method): void
    {
        $this->methods[$method->getCode()] = $method;
    }

    public function resolve(string $methodCode): PaymentMethodInterface
    {
        if (! isset($this->methods[$methodCode])) {
            throw new InvalidArgumentException("Metode pembayaran [{$methodCode}] tidak didukung.");
        }

        return $this->methods[$methodCode];
    }

    public function supportedMethods(): array
    {
        $result = [];
        foreach ($this->methods as $code => $method) {
            $result[$code] = [
                'code' => $method->getCode(),
                'label' => $method->getLabel(),
            ];
        }

        return $result;
    }
}
