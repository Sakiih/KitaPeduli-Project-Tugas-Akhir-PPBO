<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fund_disbursement_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('campaigns')->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->decimal('amount', 15, 2);
            $table->string('recipient_name');
            $table->string('purpose');
            $table->longText('description');
            $table->string('document_image')->nullable();
            $table->date('disbursed_at');
            $table->timestamps();

            $table->index(['campaign_id', 'disbursed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fund_disbursement_reports');
    }
};
