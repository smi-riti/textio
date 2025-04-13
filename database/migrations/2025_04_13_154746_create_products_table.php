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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('slug')->unique();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('brand_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('unit_price', 10, 2);
            $table->text('description')->nullable();
            $table->string('thumbnail_img')->nullable();
            $table->integer('current_stock')->default(0);
            $table->boolean('published')->default(true);
            $table->decimal('discount', 10, 2)->nullable();
            $table->enum('discount_type', ['flat', 'percent'])->nullable();
            $table->string('tags')->nullable();
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->decimal('shipping_cost', 10, 2)->nullable();
            $table->integer('min_qty')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
