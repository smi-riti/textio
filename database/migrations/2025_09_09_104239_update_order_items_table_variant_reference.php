<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Add the new column
            $table->foreignId('product_variant_combination_id')
                ->nullable()
                ->constrained('product_variant_combinations')
                ->onDelete('cascade')
                ->after('product_id');

            // Remove old columns (after data migration)
            $table->dropForeign(['color_variant_id']);
            $table->dropForeign(['size_variant_id']);
            $table->dropColumn(['color_variant_id', 'size_variant_id']);
        });
    }

    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Add back old columns
            $table->foreignId('color_variant_id')->nullable()->constrained('product_variants')->onDelete('cascade');
            $table->foreignId('size_variant_id')->nullable()->constrained('product_variants')->onDelete('cascade');

            // Remove new column
            $table->dropForeign(['product_variant_combination_id']);
            $table->dropColumn('product_variant_combination_id');
        });
    }
};
