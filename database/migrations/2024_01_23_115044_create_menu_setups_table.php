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
        Schema::create('menu_setups', function (Blueprint $table) {
            $table->id();
            $table->text('title')->nullable();
            $table->text('route')->nullable();
            $table->text('target')->nullable();
            $table->text('tooltip')->nullable();
            $table->text('icon')->nullable();
            $table->integer('position')->nullable();
            $table->tinyInteger('mega_menu')->default(0);
            $table->tinyInteger('footer_menu')->default(0);
            $table->tinyInteger('enabled')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_setups');
    }
};
