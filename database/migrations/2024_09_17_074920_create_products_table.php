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
            $table->unsignedBigInteger('shop_id');
            $table->unsignedBigInteger('shopify_product_id')->unique();
            $table->string('title')->nullable();
            $table->string('handle')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->nullable();
            $table->text('supplier')->nullable();
            $table->text('tags')->nullable();
            $table->text('product_type')->nullable();
            $table->text('image_url')->nullable();
            $table->json('images')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('shop_id')->references('id')->on('users')->onDelete('CASCADE');
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
