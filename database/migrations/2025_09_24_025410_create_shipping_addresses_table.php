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
        Schema::create('shipping_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('type',100)->nullable();
            $table->string('name',100)->nullable();
            $table->string('email',80)->nullable();
            $table->string('address',100)->nullable();
            $table->string('street',80)->nullable();
            $table->string('city',80)->nullable();
            $table->string('tole',80)->nullable();
            $table->string('house_no',80)->nullable();
            $table->string('phone',50)->nullable();
            $table->string('description',200)->nullable();
            $table->boolean('active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_addresses');
    }
};
