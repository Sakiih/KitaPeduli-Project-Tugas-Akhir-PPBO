<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaign_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('campaigns')->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->longText('content');
            $table->string('image_path')->nullable();
            $table->timestamp('published_at')->useCurrent();
            $table->timestamps();

            $table->index(['campaign_id', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_updates');
    }
};
