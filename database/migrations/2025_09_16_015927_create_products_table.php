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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('brand_id')->nullable();
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');

            $table->unsignedBigInteger('tag_id')->nullable();
            $table->foreign('tag_id')->references('id')->on('tags');

            $table->string('name')->nullable();
            $table->string('slug')->unique();
            $table->string('code')->nullable();

            $table->string('video_url')->nullable();
            $table->text('short')->nullable();
            $table->longText('description')->nullable();

            $table->integer('price')->default(0);
            $table->integer('strike_price')->default(0);
            $table->double('tax')->default(0);
            $table->integer('delivery_charges')->default(0);

            $table->integer('view_count')->default(0);

            $table->string('warrenty_period')->nullable();
            $table->text('warrenty_policy')->nullable();
            $table->string('warrenty_type')->nullable();

            $table->boolean('on_sale')->default(1);
            $table->boolean('best_rated')->default(1);
            $table->boolean('feature')->default(1);
            $table->boolean('home_delivery')->default(0);
            $table->boolean('stock')->default(1);
            $table->boolean('status')->default(1);

            $table->text('meta_title')->nullable();
            $table->text('meta_keyword')->nullable();
            $table->text('meta_description')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
