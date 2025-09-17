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
        Schema::table('products', function (Blueprint $table) {
             // store dimensions in lowercase as requested
            $table->integer('weight')->default(500)->after('discount_price');  // grams
            $table->integer('length')->default(10)->after('weight');           // cm
            $table->integer('breadth')->default(10)->after('length');         // cm
            $table->integer('height')->default(5)->after('breadth');   
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['weight','length','breadth','height']);

        });
    }
};
