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
        Schema::table('orders', function (Blueprint $table) {
             $table->enum('status', [
                'pending','processing','completed','canceled',
                'shipped','delivered','returned'
            ])->default('pending')->change();

            // Remove columns that belong to payments/shiprocket (we'll use separate tables)
            if (Schema::hasColumn('orders', 'payment_status')) {
                $table->dropColumn('payment_status');
            }
            if (Schema::hasColumn('orders', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
            if (Schema::hasColumn('orders', 'tracking_number')) {
                $table->dropColumn('tracking_number');
            }

            // Add return/cancellation support in orders
            $table->enum('return_status', ['requested','approved','rejected','processed'])->nullable()->after('coupon_code');
            $table->text('return_reason')->nullable()->after('return_status');
            $table->timestamp('return_requested_at')->nullable()->after('return_reason');

            $table->timestamp('cancelled_at')->nullable()->after('return_requested_at');
            $table->text('cancellation_reason')->nullable()->after('cancelled_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
             // rollback: change status back and drop newly added columns
            $table->enum('status', ['pending','processing','completed','canceled'])->default('pending')->change();

            $table->dropColumn([
                'return_status','return_reason','return_requested_at',
                'cancelled_at','cancellation_reason'
            ]);

            // add back old payment/tracking columns (best-effort fallback)
            $table->enum('payment_status', ['paid','unpaid','refunded'])->default('unpaid');
            $table->string('payment_method')->nullable();
            $table->string('tracking_number')->nullable();
        });
    }
};
