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
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('parent_category_id')->nullable()->constrained('categories')->onDelete('cascade'); // Self-referencing foreign key
            $table->string('title', 100)->index(); 
            $table->string('slug', 120)->unique(); 
            $table->unsignedTinyInteger('level')->default(0); 
            $table->unsignedSmallInteger('order')->default(0);
            $table->string('image')->nullable(); 
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('meta_title', 200)->nullable();
            $table->text('meta_description')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};