<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            // Remove the old foreign key
            $table->dropForeign(['product_variant_id']);
            
            // Add the correct foreign key
            $table->foreignId('product_variant_combination_id')
                ->nullable()
                ->constrained('product_variant_combinations')
                ->onDelete('cascade')
                ->after('product_id');
                
            // Remove the old column
            $table->dropColumn('product_variant_id');
        });
    }

    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign(['product_variant_combination_id']);
            $table->dropColumn('product_variant_combination_id');
            
            $table->foreignId('product_variant_id')
                ->nullable()
                ->constrained('product_variants')
                ->onDelete('cascade');
        });
    }
};