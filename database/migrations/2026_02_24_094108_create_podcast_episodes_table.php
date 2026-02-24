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
        Schema::create('podcast_episodes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Podcast::class)->constrained()->cascadeOnDelete();
            $table->string('title')->unique();
            $table->string('slug')->unique();
            $table->string('url')->nullable();
            $table->string('duration')->nullable();
            $table->integer('likes')->default(0);
            $table->integer('views')->default(0);
            $table->integer('comments')->default(0);
            $table->integer('shares')->default(0);
            $table->integer('downloads')->default(0);
            $table->integer('favorites')->default(0);
            $table->string('video_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('podcast_episodes');
    }
};
