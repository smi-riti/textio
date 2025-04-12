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
            $table->id(); // Primary key
            $table->string('name', 200)->index();
            $table->string('added_by', 6)->default('admin');
            $table->foreignId('user_id')->constrained()->onDelete("cascade");
            $table->foreignId('category_id')->constrained()->onDelete("cascade");
            $table->foreignId('brand_id')->nullable()->constrained()->onDelete("cascade");
            // $table->foreignId('product_photos_id')->constrained()->onDelete("cascade");
            $table->string('thumbnail_img', 100)->nullable();
            $table->string('video_provider', 20)->nullable();
            $table->string('video_link', 100)->nullable();
            $table->string('tags', 500)->index()->nullable();
            $table->longText('description')->nullable();
            $table->double('unit_price', 20, 2)->index();
            $table->double('purchase_price', 20, 2)->nullable();
            $table->double('auction_success_price', 20, 2)->nullable();
            $table->boolean('variant_product')->default(0);
            $table->string('attributes', 1000)->default('[]');
            $table->mediumText('choice_options')->nullable();
            $table->mediumText('colors')->nullable();
            $table->text('variations')->nullable();
            $table->boolean('todays_deal')->default(0);
            $table->boolean('published')->default(1);
            $table->tinyInteger('approved')->default(1);
            $table->string('stock_visibility_state', 10)->default('quantity');
            $table->boolean('cash_on_delivery')->default(0);
            $table->integer('featured')->default(0);
            $table->integer('seller_featured')->default(0);
            $table->integer('current_stock')->default(0);
            $table->string('unit', 20)->nullable();
            $table->double('weight', 8, 2)->default(0.00);
            $table->double('size', 8, 2)->default(0.00);
            $table->integer('min_qty')->default(1);
            $table->integer('low_stock_quantity')->nullable();
            $table->double('discount', 20, 2)->nullable();
            $table->string('discount_type', 10)->nullable();
            $table->integer('discount_start_date')->nullable();
            $table->integer('discount_end_date')->nullable();
            $table->integer('auction_end_date')->nullable();
            $table->string('shipping_type', 200)->default('product_wise_shipping');
            $table->double('shipping_cost', 20, 2)->default(0.00);
            $table->boolean('is_quantity_multiplied')->default(1);
            $table->integer('est_shipping_days')->nullable();
            $table->integer('num_of_sale')->default(0);
            $table->mediumText('meta_title')->nullable();
            $table->longText('meta_description')->nullable();
            $table->string('meta_img', 255)->nullable();
            $table->string('pdf', 255)->nullable();
            $table->string('barcode_path', 500)->nullable();
            $table->mediumText('slug');
            $table->double('rating', 8, 2)->default(0.00);
            $table->string('barcode', 255)->nullable();
            $table->boolean('digital')->default(0);
            $table->boolean('auction_product')->default(0);
            $table->string('file_name', 255)->nullable();
            $table->string('file_path', 255)->nullable();
            $table->string('external_link', 500)->nullable();
            $table->string('external_link_btn', 255)->default('Buy Now');
            $table->boolean('wholesale_product')->default(0);
            $table->enum('boosting_status', ['Active', 'InActive'])->default('InActive');
            $table->string('boosting_type', 50)->nullable();
            $table->date('boosting_plan_end')->nullable();
            $table->integer('boosting_click')->nullable();
            $table->integer('click')->default(0);
            $table->string('warranty_days', 50)->nullable();
            $table->float('warranty_cost')->nullable();
            $table->integer('warranty_status')->default(0);
            $table->integer('product_type')->default(1); // 1: default, 2: donation, 3: auction
            $table->enum('auction_product_status', ['Open', 'Close', 'Sold'])->default('Open');
            $table->timestamps(); // created_at and updated_at
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
