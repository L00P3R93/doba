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
        Schema::create('banner_ads', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('headline');
            $table->string('cta_text', 50);
            $table->string('target_level');
            $table->foreignId('target_county_id')->nullable()->constrained('counties')->onDelete('set null');
            $table->foreignId('target_sub_county_id')->nullable()->constrained('sub_counties')->onDelete('set null');
            $table->foreignId('target_ward_id')->nullable()->constrained('wards')->onDelete('set null');
            $table->string('image_url')->nullable();
            $table->decimal('base_price_per_impression', 8, 2);
            $table->decimal('price_per_impression', 8, 2);
            $table->decimal('budget', 10, 2);
            $table->unsignedInteger('max_impressions')->nullable();
            $table->unsignedInteger('impressions')->default(0);
            $table->unsignedInteger('clicks')->default(0);
            $table->string('status')->default('pending');
            $table->boolean('is_active')->default(false);
            $table->unsignedInteger('priority')->default(1);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('target_level');
            $table->index('status');
            $table->index('is_active');
            $table->index(['target_level', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banner_ads');
    }
};
