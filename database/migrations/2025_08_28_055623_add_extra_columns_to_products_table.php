<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_customizable')->default(false)->after('status');
            $table->json('print_area')->nullable()->after('is_customizable');
            $table->string('meta_title')->nullable()->after('print_area');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->boolean('featured')->default(false)->after('meta_description');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'is_customizable',
                'print_area',
                'meta_title',
                'meta_description',
                'featured',
            ]);
        });
    }
};
