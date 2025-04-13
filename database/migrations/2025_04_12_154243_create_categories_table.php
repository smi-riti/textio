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
            $table->string('image')->nullable(); 
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('meta_title', 200)->nullable();
            $table->text('meta_description')->nullable();            
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