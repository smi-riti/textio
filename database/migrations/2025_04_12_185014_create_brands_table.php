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
            $table->string('name', 100)->index(); 
            $table->string('slug', 120)->unique(); 
            $table->string('logo')->nullable(); 
            $table->text('description')->nullable(); 
            $table->boolean('is_active')->default(true); 
            $table->string('meta_title', 200)->nullable();
            $table->text('meta_description')->nullable(); 
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
