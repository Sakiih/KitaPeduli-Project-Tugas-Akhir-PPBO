<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('campaigns')->restrictOnDelete();
            // Nullable agar transaksi Guest tetap tersimpan tanpa akun pengguna.
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('donor_name');
            $table->string('donor_email')->nullable();
            $table->string('donor_phone', 30)->nullable();
            $table->decimal('amount', 15, 2);
            $table->text('donor_message')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->string('status', 20)->default('pending')->index();
            $table->unsignedInteger('amen_count')->default(0);
            $table->timestamps();

            $table->index(['campaign_id', 'status']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
