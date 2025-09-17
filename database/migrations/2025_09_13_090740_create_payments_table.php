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
            $table->foreignId('order_id')->constrained()->onDelete('cascade');

            // Payment basics
            $table->string('payment_method'); // 'razorpay' or 'cod'
            $table->string('currency', 10)->default('INR');
            $table->decimal('amount', 15, 2);

            // status: pending until verified for online, 'pending' for COD until collected
            $table->enum('payment_status', ['pending','paid','failed','refunded'])->default('pending');
            $table->timestamp('payment_date')->nullable();

            // Razorpay fields for verification & record-keeping
            $table->string('razorpay_payment_id')->nullable();
            $table->string('razorpay_order_id')->nullable();
            $table->string('razorpay_signature')->nullable();

            // extra notes (json) for gateway meta
            $table->json('notes')->nullable();
            $table->timestamps();
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
