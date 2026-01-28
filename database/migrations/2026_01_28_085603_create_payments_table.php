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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Customer::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Subscription::class)->nullable()->constrained()->cascadeOnDelete();
            $table->string('transaction_id')->unique()->index();
            $table->string('type')->nullable();
            $table->string('transaction_time')->index();
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('short_code')->nullable();
            $table->string('bill_ref_no')->index();
            $table->string('msisdn')->nullable();
            $table->string('name')->nullable();
            $table->string('status')->default('completed'); // completed, failed
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
