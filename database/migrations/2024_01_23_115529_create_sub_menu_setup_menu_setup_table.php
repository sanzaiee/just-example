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
        Schema::create('sub_menu_setup_menu_setup', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sub_menu_setup_id');
            $table->foreign('sub_menu_setup_id')->references('id')->on('sub_menu_setups')->onDelete('cascade');
            $table->unsignedBigInteger('menu_setup_id');
            $table->foreign('menu_setup_id')->references('id')->on('menu_setups')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_menu_setup_menu_setup');
    }
};
