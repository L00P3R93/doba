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
        Schema::create('audio_ads', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('advertiser', 100)->nullable();
            $table->string('headline', 255)->nullable();
            $table->string('cta_text', 50)->nullable();
            $table->text('cta_url')->nullable();
            $table->text('audio_url');
            $table->text('banner_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('priority')->default(1);
            $table->unsignedInteger('daily_limit')->nullable();
            $table->unsignedInteger('impressions')->default(0);
            $table->string('target_level')->default('general');
            $table->string('target_uid', 50)->nullable();
            $table->unsignedInteger('clicks')->default(0);
            $table->unsignedInteger('completions')->default(0);
            $table->unsignedInteger('skips')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('target_level');
            $table->index('is_active');
            $table->index(['target_level', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audio_ads');
    }
};