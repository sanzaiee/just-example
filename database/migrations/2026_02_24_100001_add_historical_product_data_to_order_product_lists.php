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
        Schema::table('order_product_lists', function (Blueprint $table) {
            // Add historical product data fields
            // These fields capture product information at the time of order
            // This ensures order history is preserved even if products are updated or deleted
            $table->string('product_name')->after('product_id')->nullable();
            $table->string('product_code')->after('product_name')->nullable();
            $table->text('product_description')->after('product_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_product_lists', function (Blueprint $table) {
            // Remove the historical product data fields
            $table->dropColumn([
                'product_name',
                'product_code',
                'product_description',
            ]);
        });
    }
};