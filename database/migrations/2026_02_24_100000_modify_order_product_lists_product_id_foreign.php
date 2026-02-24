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
            // Drop the foreign key constraint with cascade delete
            $table->dropForeign(['product_id']);

            // Re-add foreign key with restrict instead of cascade
            // This prevents accidental deletion of products that have orders
            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_product_lists', function (Blueprint $table) {
            // Drop the restricted foreign key
            $table->dropForeign(['product_id']);

            // Re-add the cascade delete (revert to original behavior)
            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('cascade');
        });
    }
};