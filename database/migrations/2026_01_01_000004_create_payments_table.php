<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donation_id')->unique()->constrained('donations')->cascadeOnDelete();
            $table->string('payment_method', 30)->index();
            $table->string('reference', 60)->unique();
            $table->string('access_token_hash', 64);
            $table->decimal('amount', 15, 2);
            $table->string('status', 20)->default('pending')->index();
            $table->json('instruction_payload')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
