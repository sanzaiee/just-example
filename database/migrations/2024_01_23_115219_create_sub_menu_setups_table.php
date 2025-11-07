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
        Schema::create('sub_menu_setups', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->nullable();
            $table->text('title')->nullable();
            $table->text('action')->nullable();
            $table->text('attribute')->nullable();
            $table->text('target')->nullable();
            $table->text('tooltip')->nullable();
            $table->text('icon')->nullable();
            $table->integer('position')->nullable();
            $table->tinyInteger('enabled')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_menu_setups');
    }
};
