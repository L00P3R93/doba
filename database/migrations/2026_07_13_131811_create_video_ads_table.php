<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('video_ads', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('advertiser', 100);
            $table->string('headline', 150);
            $table->string('cta_text', 50);
            $table->string('video_url', 500);
            $table->enum('ad_type', ['interstitial', 'rewarded']);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('priority')->default(1);
            $table->unsignedInteger('daily_limit')->nullable();
            $table->unsignedInteger('max_impressions')->nullable();
            $table->unsignedInteger('impressions')->default(0);
            $table->unsignedInteger('clicks')->default(0);
            $table->unsignedInteger('completions')->default(0);
            $table->unsignedInteger('skips')->default(0);
            $table->decimal('price_per_impression', 10, 2)->nullable();
            $table->string('target_level', 20)->nullable();
            $table->string('target_uid', 50)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('target_level');
            $table->index('is_active');
            $table->index(['target_level', 'is_active']);
            $table->index('ad_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_ads');
    }
};