<?php

declare(strict_types=1);

namespace App\Payments;

use App\Models\Donation;
use App\Models\Payment;

class QrisPayment extends PaymentMethod
{
    protected string $code = Payment::METHOD_QRIS;

    protected string $label = 'QRIS (Simulasi Demo)';

    /** @return array<string, mixed> */
    public function createInstruction(Payment $payment, Donation $donation): array
    {
        $base = $this->buildBaseMetadata($payment, $donation);
        $demoPayload = $this->generateDemoQrPayload($payment);

        return array_merge($base, [
            'instruction_title' => 'Instruksi Pembayaran QRIS Simulasi',
            'summary' => 'Periksa detail QRIS demo di bawah ini, lalu tekan tombol Simulasikan Pembayaran QRIS Berhasil.',
            'details' => [
                'merchant_name' => 'KitaPeduli Crowdfunding (Simulasi Demo)',
                'nmid_demo' => 'ID-DEMO-KITAPEDULI-'.substr(md5($payment->reference), 0, 8),
                'terminal_id' => 'SIM-QR-01',
                'qr_payload' => $demoPayload,
                'supported_apps' => 'Aplikasi e-wallet dan mobile banking (mode simulasi)',
            ],
            'steps' => [
                'Halaman ini menggunakan QRIS simulasi demo untuk keperluan demonstrasi akademik PPBO.',
                'Periksa nominal donasi ('.$this->formatCurrency((float) $payment->amount).') dan kode referensi transaksi ('.$payment->reference.').',
                'Anda tidak perlu memindai kode QR menggunakan aplikasi perbankan atau e-wallet sungguhan.',
                'Tekan tombol Simulasikan Pembayaran QRIS Berhasil untuk menyelesaikan pembayaran simulasi.',
            ],
            'confirm_button_label' => 'Simulasikan Pembayaran QRIS Berhasil',
        ]);
    }

    // Payload demo internal, bukan standar EMVCo finansial nyata.
    private function generateDemoQrPayload(Payment $payment): string
    {
        return sprintf(
            'KITAPEDULI-SIMULATION-QRIS|REF:%s|AMT:%s|NOTICE:DEMO_ONLY_NOT_REAL_PAYMENT',
            $payment->reference,
            number_format((float) $payment->amount, 2, '.', '')
        );
    }
}
