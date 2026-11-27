<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('short_description', 500)->nullable();
            $table->longText('story');
            $table->string('banner_image')->nullable();
            $table->decimal('target_amount', 15, 2);
            $table->decimal('collected_amount', 15, 2)->default(0);
            $table->unsignedInteger('donor_count')->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->index();
            // Data pengelola dikelola langsung oleh Admin, bukan role akun terpisah.
            $table->string('organizer_name');
            $table->string('organizer_type', 100)->nullable();
            $table->text('organizer_description')->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_verified_organizer')->default(true);
            $table->string('status', 20)->default('active')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
