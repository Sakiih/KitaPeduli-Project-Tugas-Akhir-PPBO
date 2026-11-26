<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Contracts\PaymentMethodInterface;
use App\Contracts\PaymentResolverInterface;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Payment;
use App\Payments\BankTransferPayment;
use App\Payments\PaymentMethod;
use App\Payments\QrisPayment;
use DomainException;
use InvalidArgumentException;
use ReflectionClass;
use Tests\TestCase;

class PaymentOopAndSolidTest extends TestCase
{
    public function test_payment_method_is_abstract_and_implements_interface(): void
    {
        $reflection = new ReflectionClass(PaymentMethod::class);

        $this->assertTrue($reflection->isAbstract());
        $this->assertTrue($reflection->implementsInterface(PaymentMethodInterface::class));
    }

    public function test_qris_and_bank_transfer_inherit_from_payment_method(): void
    {
        $qris = new QrisPayment;
        $bank = new BankTransferPayment;

        $this->assertInstanceOf(PaymentMethod::class, $qris);
        $this->assertInstanceOf(PaymentMethod::class, $bank);
        $this->assertSame('qris', $qris->getCode());
        $this->assertSame('bank_transfer', $bank->getCode());
    }

    public function test_polymorphism_create_instruction_produces_distinct_simulation_instructions(): void
    {
        $campaign = new Campaign(['title' => 'Kampanye Pendidikan Demo']);
        $donation = new Donation([
            'donor_name' => 'Andi Pratama',
            'amount' => 150000,
        ]);
        $donation->setRelation('campaign', $campaign);

        $qrisPaymentModel = new Payment([
            'payment_method' => 'qris',
            'reference' => 'KP-TEST-QRIS01',
            'amount' => 150000,
        ]);

        $bankPaymentModel = new Payment([
            'payment_method' => 'bank_transfer',
            'reference' => 'KP-TEST-BANK01',
            'amount' => 150000,
        ]);

        /** @var PaymentResolverInterface $resolver */
        $resolver = app(PaymentResolverInterface::class);

        $qrisInstruction = $resolver->resolve('qris')->createInstruction($qrisPaymentModel, $donation);
        $bankInstruction = $resolver->resolve('bank_transfer')->createInstruction($bankPaymentModel, $donation);

        $this->assertTrue($qrisInstruction['is_simulation']);
        $this->assertTrue($bankInstruction['is_simulation']);
        $this->assertSame(PaymentMethod::SIMULATION_DISCLAIMER, $qrisInstruction['simulation_notice']);
        $this->assertSame('qris', $qrisInstruction['method_code']);
        $this->assertSame('bank_transfer', $bankInstruction['method_code']);

        // Pastikan pemanggilan polimorfik menghasilkan detail berbeda per metode.
        $this->assertArrayHasKey('qr_payload', $qrisInstruction['details']);
        $this->assertArrayHasKey('account_number', $bankInstruction['details']);
    }

    public function test_resolver_rejects_unsupported_payment_method(): void
    {
        /** @var PaymentResolverInterface $resolver */
        $resolver = app(PaymentResolverInterface::class);

        $this->expectException(InvalidArgumentException::class);
        $resolver->resolve('crypto');
    }

    public function test_encapsulation_on_donation_and_payment_models(): void
    {
        $anonymousDonation = new Donation([
            'donor_name' => 'Nama Asli Rahasia',
            'is_anonymous' => true,
            'status' => Donation::STATUS_PENDING,
        ]);

        $this->assertSame('Orang Baik (Anonim)', $anonymousDonation->publicDonorName());

        $plainToken = 'secret-guest-token-123';
        $payment = new Payment([
            'status' => Payment::STATUS_PAID,
            'access_token_hash' => hash('sha256', $plainToken),
        ]);

        $this->assertTrue($payment->verifyAccessToken($plainToken));
        $this->assertFalse($payment->verifyAccessToken('wrong-token'));

        // Perubahan status harus ditolak jika transaksi sudah berstatus paid.
        $this->expectException(DomainException::class);
        $payment->markAsPaid();
    }
}
