<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Removes shipping_address_id from orders to avoid cascade issues when
     * deleting user shipping addresses. Shipping is stored in order_delivery_addresses.
     * Adds is_store_pickup to preserve pickup vs delivery behaviour.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('is_store_pickup')->default(false)->after('order_status');
        });

        // Preserve pickup flag: orders that used shipping_address_id = 1 (store pickup)
        DB::table('orders')
            ->where('shipping_address_id', 1)
            ->update(['is_store_pickup' => true]);

        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['shipping_address_id']);
            $table->dropColumn('shipping_address_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('shipping_address_id')->nullable()->after('order_status');
            $table->foreign('shipping_address_id')->references('id')->on('shipping_addresses');
        });

        // Restore pickup orders to shipping_address_id = 1 (data loss: other IDs not restored)
        DB::table('orders')
            ->where('is_store_pickup', true)
            ->update(['shipping_address_id' => 1]);

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('is_store_pickup');
        });
    }
};
