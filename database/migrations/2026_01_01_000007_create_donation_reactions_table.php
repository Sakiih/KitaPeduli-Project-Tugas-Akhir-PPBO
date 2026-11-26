<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donation_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donation_id')->constrained('donations')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            // Batasi maksimal satu reaksi Aamiin per pengguna terdaftar pada satu doa donasi.
            $table->unique(['donation_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donation_reactions');
    }
};
