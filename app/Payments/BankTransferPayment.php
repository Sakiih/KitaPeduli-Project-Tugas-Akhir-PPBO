<?php

declare(strict_types=1);

namespace App\Payments;

use App\Models\Donation;
use App\Models\Payment;

class BankTransferPayment extends PaymentMethod
{
    protected string $code = Payment::METHOD_BANK_TRANSFER;

    protected string $label = 'Transfer Bank / ATM (Simulasi Demo)';

    /** @return array<string, mixed> */
    public function createInstruction(Payment $payment, Donation $donation): array
    {
        $base = $this->buildBaseMetadata($payment, $donation);
        $virtualAccount = $this->generateVirtualAccountNumber($payment);

        return array_merge($base, [
            'instruction_title' => 'Instruksi Transfer Bank / ATM Simulasi',
            'summary' => 'Periksa informasi rekening simulasi di bawah ini, lalu tekan tombol Simulasikan Transfer Bank Berhasil.',
            'details' => [
                'bank_name' => 'Bank Nasional Simulasi (BNS Demo)',
                'bank_code' => '999 (Kode Bank Demo)',
                'account_number' => $virtualAccount,
                'account_holder' => 'Yayasan KitaPeduli Indonesia (Simulasi)',
                'transfer_note' => 'Donasi '.$payment->reference,
            ],
            'steps' => [
                'Nomor rekening di atas adalah rekening simulasi demo, jangan melakukan transfer uang sungguhan.',
                'Periksa kesesuaian nominal transfer ('.$this->formatCurrency((float) $payment->amount).') dan nomor referensi ('.$payment->reference.').',
                'Simulasi ini tidak memerlukan unggah bukti transfer maupun verifikasi manual Admin.',
                'Tekan tombol Simulasikan Transfer Bank Berhasil untuk mengonfirmasi pembayaran simulasi.',
            ],
            'confirm_button_label' => 'Simulasikan Transfer Bank Berhasil',
        ]);
    }

    private function generateVirtualAccountNumber(Payment $payment): string
    {
        $numericHash = preg_replace('/\D/', '', (string) crc32($payment->reference));
        $suffix = str_pad(substr((string) $numericHash, 0, 10), 10, '8', STR_PAD_LEFT);

        return '8800 '.substr($suffix, 0, 4).' '.substr($suffix, 4, 6);
    }
}
