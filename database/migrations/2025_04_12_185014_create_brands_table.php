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
        Schema::create('brands', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name', 100)->index(); // Brand name, indexed for searches
            $table->string('slug', 120)->unique(); // Unique slug for URLs
            $table->string('logo')->nullable(); // Brand logo/image
            $table->text('description')->nullable(); // Brand description
            $table->boolean('is_active')->default(true); // Active/inactive status
            $table->string('meta_title', 200)->nullable(); // SEO meta title
            $table->text('meta_description')->nullable(); // SEO meta description
            $table->unsignedBigInteger('created_by')->nullable(); // User who created the brand
            $table->unsignedBigInteger('updated_by')->nullable(); // User who updated the brand
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            $table->unsignedSmallInteger('order')->default(0); // For custom sorting
            $table->timestamps(); // created_at and updated_at
            $table->softDeletes(); // Soft deletion
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
