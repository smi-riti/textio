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
        Schema::create('shiprocket_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');

            // Shiprocket-specific response fields
            $table->string('shiprocket_order_id')->nullable()->index();
            $table->string('shipment_id')->nullable()->index();
            $table->string('awb_code')->nullable()->index();
            $table->string('courier_company_id')->nullable();
            $table->date('estimated_delivery_date')->nullable();

            // timestamps for logistic events
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->text('delivery_notes')->nullable();

            // local status mirror of shipment lifecycle
            $table->enum('status', [
                'pending','confirmed','shipped','in_transit',
                'out_for_delivery','delivered','cancelled','returned'
            ])->default('pending');

            // store raw payload for debugging / admin
            $table->json('raw_payload')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shiprocket_orders');
    }
};
